<?php

namespace App\Models\Entry;

use CodeIgniter\Model;

class CapaianOutputEntryModel extends Model
{
    protected $config;

    public function __construct()
    {
        parent::__construct();
        $this->config = config('DataMapping');
        $this->table = $this->config->tables['capaian_output'];
        $this->allowedFields = $this->config->allowedFields['capaian_output'];
    }

    public function importData($file)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            
            // Ambil sheet dari Config
            $sheet = null;
            $targetSheetName = $this->config->sheets['capaian_output'];
            foreach ($spreadsheet->getAllSheets() as $s) {
                if (strcasecmp(trim($s->getTitle()), $targetSheetName) === 0) {
                    $sheet = $s;
                    break;
                }
            }
            if (!$sheet) $sheet = $spreadsheet->getActiveSheet();

            $highestRow = $sheet->getHighestRow();
            $headerRow = $sheet->rangeToArray('A1:Z1', null, true, false)[0];
            
            // Map header positions
            $map = [];
            foreach ($headerRow as $idx => $val) {
                if (empty($val)) continue;
                $key = trim(strtolower($val));
                $map[$key] = $idx;
            }

            // Validasi Header Minimal (Updated for Associative Array)
            $schema = $this->config->headers['capaian_output_required'];
            $requiredHeaders = array_keys($schema);
            $missing = [];
            
            foreach ($requiredHeaders as $req) {
                // Check exact or with % symbol
                $searchKey = strtolower($req);
                if (!isset($map[$searchKey])) {
                    $missing[] = $req;
                }
            }

            if (!empty($missing)) {
                throw new \Exception('Format header tidak sesuai. Kolom berikut tidak ditemukan: ' . implode(', ', $missing));
            }


            // Required columns (Flexible mapping)
            $data = [];
            $currentYear = date('Y');

            for($row=2; $row<=$highestRow; $row++) {
                 $r = $sheet->rangeToArray("A{$row}:Z{$row}", null, true, false)[0];
                 
                 // Skip empty rows
                 if(empty(array_filter($r))) continue;
                 
                 // Helper to get val by key
                 $getVal = function($k) use ($map, $r) {
                     return isset($map[$k]) ? $r[$map[$k]] : null;
                 };

                 // Helper Sanitizer based on Schema
                 $sanitize = function($key, $val) use ($schema) {
                     $type = $schema[$key] ?? 'string';
                     if ($val === null) return $val;
                     
                     switch($type) {
                         case 'decimal':
                         case 'float':
                             return (float) filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                         case 'integer':
                         case 'int':
                             return (int) filter_var($val, FILTER_SANITIZE_NUMBER_INT);
                         case 'string':
                         default:
                             return trim(htmlspecialchars((string)$val));
                     }
                 };
                 
                 // Tahun
                 $tahunVal = $getVal('tahun') ?? $currentYear;
                 
                 $data[] = [
                    'tahun'                        => (int)$tahunVal,
                    'bulan'                        => $sanitize('Bulan', $getVal('bulan')),
                    'no_bulan'                     => $sanitize('No. Bulan', $getVal('no. bulan') ?? $getVal('no bulan')),
                    'rincian_output'               => $sanitize('Rincian Output', $getVal('keterangan ro') ?? $getVal('nama ro') ?? $getVal('uraian')),
                    'no_ro'                        => $sanitize('No. RO', $getVal('no. ro') ?? $getVal('no.ro')),
                    'kode_ro'                      => $getVal('rincian output') ?? $getVal('ro') ?? $getVal('kode ro') ?? '', // Not in schema, keep raw or add to schema
                    'keterangan_ro'                => $sanitize('Keterangan RO', $getVal('keterangan ro')),
                    'fungsi'                       => $sanitize('Fungsi', $getVal('fungsi')),
                    'target_persen_bulan'          => $sanitize('Target %Bulan', $getVal('target % bulan') ?? $getVal('target persen bulan')),
                    'realisasi'                    => $sanitize('Realisasi', $getVal('realisasi')),
                    'persen_realisasi'             => $sanitize('%Realisasi', $getVal('% realisasi') ?? $getVal('%realisasi') ?? $getVal('persen realisasi')),
                    'realisasi_kumulatif'          => $sanitize('Realisasi Kumulatif', $getVal('realisasi kumulatif')),
                    'salah_persen_realisasi_kumulatif'   => $getVal('salah % realisasi kumulatif') ?? $getVal('salah %realisasi kumulatif') ?? $getVal('% realisasi kumulatif') ?? $getVal('%realisasi kumulatif') ?? 0,
                    'capaian'                      => $sanitize('Capaian', $getVal('capaian')),
                    'kategori'                     => $sanitize('Kategori', $getVal('kategori')),
                    'target_tahun'                 => $getVal('target tahun') ?? 0, // Not in schema
                    'kategori_belanja'             => $getVal('kategori belanja') ?? '', // Not in schema
                    'realisasi_kumulatif_persen'   => $getVal('realisasi kumulatif %') ?? $getVal('realisasi kumulatif persen') ?? 0 // Not in schema
                 ];
            }

            return [
                'status' => 'success',
                'data' => $data,
                'count' => count($data)
            ];

        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function saveBatchData($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $db->transBegin();
        
        try {
            $processedKeys = [];
            
            $sql = "INSERT INTO {$this->table} (
                `Tahun`, `Bulan`, `No. Bulan`, `Rincian Output`, `No. RO`, `Keterangan RO`,
                `Fungsi`, `Target % Bulan`, `Realisasi`, `% Realisasi`, `Realisasi Kumulatif`,
                `salah % Realisasi Kumulatif`, `Capaian`, `Kategori`, `Target tahun`,
                `Kategori Belanja`, `Realisasi Kumulatif %`
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            foreach ($data as $row) {
                $tahun = $row['tahun'] ?? date('Y');
                $bulan = $row['bulan'] ?? '';
                $noRo  = $row['no_ro'] ?? 0;
                
                $uniqueKey = "{$tahun}_{$bulan}_{$noRo}";

                if (!isset($processedKeys[$uniqueKey])) {
                    $builder->where('Tahun', $tahun)
                            ->where('Bulan', $bulan)
                            ->where('`No. RO`', $noRo, false)
                            ->delete();
                    $processedKeys[$uniqueKey] = true;
                }

                $deskripsi = $row['rincian_output'] ?? ''; 
                $ket_ro    = $row['keterangan_ro'] ?? '';

                $db->query($sql, [
                    $tahun,
                    $bulan,
                    $row['no_bulan'] === '' ? 0 : ($row['no_bulan'] ?? 0),
                    $deskripsi,
                    $noRo,
                    $ket_ro,
                    $row['fungsi'] ?? '',
                    $row['target_persen_bulan'] === '' ? 0 : ($row['target_persen_bulan'] ?? 0),
                    $row['realisasi'] === '' ? 0 : ($row['realisasi'] ?? 0),
                    $row['persen_realisasi'] === '' ? 0 : ($row['persen_realisasi'] ?? 0),
                    $row['realisasi_kumulatif'] === '' ? 0 : ($row['realisasi_kumulatif'] ?? 0),
                    $row['salah_persen_realisasi_kumulatif'] === '' ? 0 : ($row['salah_persen_realisasi_kumulatif'] ?? 0),
                    $row['capaian'] === '' ? 0 : ($row['capaian'] ?? 0),
                    $row['kategori'] ?? '',
                    $row['target_tahun'] === '' ? 0 : ($row['target_tahun'] ?? 0),
                    $row['kategori_belanja'] ?? '',
                    $row['realisasi_kumulatif_persen'] === '' ? 0 : ($row['realisasi_kumulatif_persen'] ?? 0)
                ]);
            }
            
            if ($db->transStatus() === false) {
                $dbError = $db->error();
                $db->transRollback();
                return ['status' => 'error', 'message' => 'DB Error: ' . ($dbError['message'] ?? 'Unknown error')];
            }

            $db->transCommit();
            return ['status' => 'success'];
            
        } catch (\Throwable $e) {
            $db->transRollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getMasterCapaianOutput()
    {
        try {
            $db = \Config\Database::connect();
            $data = $db->table($this->config->tables['database_ro'])
                       ->select('`No. RO`, RO, `Rincian Output`, `Kertas Kerja ro`, `Manual RO`', false)
                       ->orderBy('`No. RO`', 'ASC', false)
                       ->get()
                       ->getResultArray();
                       
            return $data;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
