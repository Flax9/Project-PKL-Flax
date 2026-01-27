<?php

namespace App\Models\Entry;

use CodeIgniter\Model;

class CapaianOutputEntryModel extends Model
{
    protected $table = 'capaian_output';
    protected $allowedFields = [
        'Tahun', 'Bulan', 'No. Bulan', 'Rincian Output', 'No. RO', 
        'Keterangan RO', 'Fungsi', 'Target % Bulan', 'Realisasi', 
        '% Realisasi', 'Realisasi Kumulatif', 'salah % Realisasi Kumulatif', 
        'Capaian', 'Kategori', 'Target tahun', 'Kategori Belanja', 
        'Realisasi Kumulatif %'
    ];

    public function importData($file)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            
            // Look for sheet named "Capaian Output"
            $sheet = null;
            foreach ($spreadsheet->getAllSheets() as $s) {
                if (strtolower(trim($s->getTitle())) === 'capaian output') {
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
            // Debug Header Map
            log_message('error', 'Import Excel Headers: ' . json_encode(array_keys($map)));

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
                 
                 // Tahun
                 $tahunVal = $getVal('tahun') ?? $currentYear;
                 
                 $data[] = [
                    'tahun'                        => $tahunVal,
                    'bulan'                        => $getVal('bulan') ?? '',
                    'no_bulan'                     => $getVal('no. bulan') ?? $getVal('no bulan') ?? 0,
                    'rincian_output'               => $getVal('keterangan ro') ?? $getVal('nama ro') ?? $getVal('uraian') ?? '', // Updated: Prioritize 'Keterangan RO'
                    'no_ro'                        => $getVal('no. ro') ?? $getVal('no.ro') ?? 0,
                    'kode_ro'                      => $getVal('rincian output') ?? $getVal('ro') ?? $getVal('kode ro') ?? '', 
                    'keterangan_ro'                => $getVal('keterangan ro') ?? '',
                    'fungsi'                       => $getVal('fungsi') ?? '',
                    'target_persen_bulan'          => $getVal('target % bulan') ?? $getVal('target persen bulan') ?? 0,
                    'realisasi'                    => $getVal('realisasi') ?? 0,
                    'persen_realisasi'             => $getVal('% realisasi') ?? $getVal('%realisasi') ?? $getVal('persen realisasi') ?? 0,
                    'realisasi_kumulatif'          => $getVal('realisasi kumulatif') ?? 0,
                    'salah_persen_realisasi_kumulatif'   => $getVal('salah % realisasi kumulatif') ?? $getVal('salah %realisasi kumulatif') ?? $getVal('% realisasi kumulatif') ?? $getVal('%realisasi kumulatif') ?? 0, // RESTORE SALAH VAR
                    'capaian'                      => $getVal('capaian') ?? 0,
                    'kategori'                     => $getVal('kategori') ?? '',
                    'target_tahun'                 => $getVal('target tahun') ?? 0,
                    'kategori_belanja'             => $getVal('kategori belanja') ?? '',
                    'realisasi_kumulatif_persen'   => $getVal('realisasi kumulatif %') ?? $getVal('realisasi kumulatif persen') ?? 0
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
        
        $sql = "INSERT INTO capaian_output 
                (Tahun, Bulan, `No. Bulan`, `Rincian Output`, `No. RO`, `Keterangan RO`, Fungsi, `Target % Bulan`, Realisasi, `% Realisasi`, `Realisasi Kumulatif`, `salah % Realisasi Kumulatif`, Capaian, Kategori, `Target tahun`, `Kategori Belanja`, `Realisasi Kumulatif %`) 
                VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $db->transBegin();
        
        try {
            foreach ($data as $row) {
                // FALLBACK: If 'rincian_output' is empty, use 'kode_ro' (some layouts swap them)
                // or just keep explicit mapping from frontend
                $ro_text = !empty($row['kode_ro']) ? $row['kode_ro'] : ($row['rincian_output'] ?? '');

                // Ensure 'rincian_output' (description) is prioritized from 'keterangan_ro' logic if available in mapping
                $deskripsi = $row['rincian_output'] ?? ''; // This now holds 'Keterangan RO' from Excel import mapping

                // Handling for 'keterangan_ro' field specifically if strictly needed by DB column
                // In Excel mapping: 'keterangan_ro' -> 'keterangan ro'
                $ket_ro = $row['keterangan_ro'] ?? '';

                $db->query($sql, [
                    $row['tahun'] ?? date('Y'),
                    $row['bulan'] ?? '',
                    $row['no_bulan'] ?? 0,
                    $deskripsi, // Rincian Output column
                    $row['no_ro'] ?? 0,
                    $ket_ro, // Keterangan RO column
                    $row['fungsi'] ?? '', // Handle missing fungsi
                    $row['target_persen_bulan'] ?? 0,
                    $row['realisasi'] ?? 0,
                    $row['persen_realisasi'] ?? 0,
                    $row['realisasi_kumulatif'] ?? 0,
                    $row['salah_persen_realisasi_kumulatif'] ?? 0,
                    $row['capaian'] ?? 0,
                    $row['kategori'] ?? '',
                    $row['target_tahun'] ?? 0,
                    $row['kategori_belanja'] ?? '',
                    $row['realisasi_kumulatif_persen'] ?? 0
                ]);
            }
            
            $db->transCommit();
            return ['status' => 'success'];
            
        } catch (\Exception $e) {
            $db->transRollback();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getMasterCapaianOutput()
    {
        try {
            $db = \Config\Database::connect();
            $data = $db->table('database_ro')
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
