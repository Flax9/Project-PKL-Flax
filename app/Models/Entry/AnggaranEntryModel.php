<?php

namespace App\Models\Entry;

use CodeIgniter\Model;

class AnggaranEntryModel extends Model
{
    protected $config;

    public function __construct()
    {
        parent::__construct();
        $this->config = config('DataMapping');
        $this->table = $this->config->tables['anggaran'];
        $this->allowedFields = $this->config->allowedFields['anggaran'];
    }

    public function importData($file)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            // Ambil sheet dari Config
            $sheet = null;
            $targetSheetName = $this->config->sheets['anggaran'];
            foreach ($spreadsheet->getSheetNames() as $sheetName) {
                if (strcasecmp($sheetName, $targetSheetName) === 0) {
                    $sheet = $spreadsheet->getSheetByName($sheetName);
                    break;
                }
            }
            
            // STRICT VALIDATION: If sheet not found, return error
            if (!$sheet) {
                return [
                    'status' => 'error', 
                    'message' => "Sheet dengan nama '$targetSheetName' tidak ditemukan dalam file (Case-Insensitive)."
                ];
            }

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
            $schema = $this->config->headers['anggaran_required'];
            $requiredHeaders = array_keys($schema); // Get keys only
            $missing = [];
            
            foreach ($requiredHeaders as $req) {
                if (!isset($map[strtolower($req)])) {
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
                             // Remove non-numeric chars except dot and minus
                             // Handle "1.000,00" format if necessary (Indonesian format often uses comma as decimal)
                             // For now assuming standard or raw numeric from Excel
                             return (float) filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                         case 'integer':
                         case 'int':
                             return (int) filter_var($val, FILTER_SANITIZE_NUMBER_INT);
                         case 'string':
                         default:
                             return trim(htmlspecialchars((string)$val));
                     }
                 };
                 
                 // 1. Tahun
                 $tahunVal = $getVal('tahun') ?? $currentYear;
                 
                 // 2. Target TW
                 $targetTwIdx = $map['% target tw'] ?? $map['target tw'] ?? null;
                 $targetTwRaw = ($targetTwIdx !== null) ? $r[$targetTwIdx] : 0;
                 $targetTw = $sanitize('% Target TW', $targetTwRaw);
 
                 // 3. Capaian Target
                 $capTargetIdx = $map['capaian terhadap target tw'] ?? $map['capaian target tw'] ?? null;
                 $capTargetRaw = ($capTargetIdx !== null) ? $r[$capTargetIdx] : 0;
                 $capTarget = $sanitize('CAPAIAN TERHADAP TARGET TW', $capTargetRaw);
                 
                 // 4. Capaian Realisasi
                 $capRealIdx = $map['capaian realisasi'] ?? null;
                 $capRealRaw = ($capRealIdx !== null) ? $r[$capRealIdx] : 0;
                 $capReal = $sanitize('Capaian Realisasi', $capRealRaw);
 
                 // 5. Regular Fields
                 $paguRaw = $getVal('pagu');
                 $realisasiRaw = $getVal('realisasi');

                 $data[] = [
                    'tahun'             => (int)$tahunVal,
                    'bulan'             => $sanitize('Bulan', $getVal('bulan')),
                    'no_ro'             => $sanitize('No. RO', $getVal('no. ro')),
                    'ro'                => $sanitize('RO', $getVal('ro')),
                    'program'           => $sanitize('PROGRAM/KEGIATAN', $getVal('program/kegiatan')),
                    'pagu'              => $sanitize('PAGU', $paguRaw),
                    'realisasi'         => $sanitize('REALISASI', $realisasiRaw),
                    'capaian_realisasi' => $capReal,
                    'target_tw'         => $targetTw,
                    'capaian_target_tw' => $capTarget,
                    'kategori_tw'       => $sanitize('Kategori TW', $getVal('kategori tw'))
                 ];
            }

            return ['status' => 'success', 'data' => $data, 'count' => count($data)];

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
            $newEntries = [];
            $processedKeys = [];

            foreach ($data as $row) {
                // Key: Tahun + Bulan + No. RO
                $tahun = $row['tahun'];
                $bulan = $row['bulan'];
                $noRo  = $row['no_ro'];
                
                $uniqueKey = "{$tahun}_{$bulan}_{$noRo}";

                // Delete Logic: Delete ONLY if not yet deleted in this batch
                if (!isset($processedKeys[$uniqueKey])) {
                    $builder->where('Tahun', $tahun)
                            ->where('Bulan', $bulan)
                            ->where('`No. RO`', $noRo, false) 
                            ->delete();
                    $processedKeys[$uniqueKey] = true;
                }
                
                // Prepare for batch insert
                $newEntries[] = [
                    'No. RO'            => $row['no_ro'],
                    'RO'                => $row['ro'],
                    'PROGRAM/KEGIATAN'  => $row['program'],
                    'PAGU'              => $row['pagu'],
                    'REALISASI'         => $row['realisasi'],
                    'Capaian Realisasi' => $row['capaian_realisasi'],
                    'Target TW'         => $row['target_tw'],
                    'CAPAIAN_TARGET_TW' => $row['capaian_target_tw'],
                    'Kategori TW'       => $row['kategori_tw'],
                    'Bulan'             => $row['bulan'],
                    'Tahun'             => $row['tahun']
                ];
            }

            // Perform Batch Insert
            if (!empty($newEntries)) {
                $builder->insertBatch($newEntries);
            }

            if ($db->transStatus() === false) {
                $db->transRollback();
                return ['status' => 'error', 'message' => 'Transaction failed'];
            }

            $db->transCommit();
            return ['status' => 'success'];

        } catch (\Throwable $e) {
             $db->transRollback();
             return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getMasterAnggaran()
    {
        $db = \Config\Database::connect();
        return $db->table($this->config->tables['master_anggaran'])
                  ->select('no_ro, ro, program_kegiatan')
                  ->orderBy('no_ro', 'ASC')
                  ->get()
                  ->getResultArray();
    }
}
