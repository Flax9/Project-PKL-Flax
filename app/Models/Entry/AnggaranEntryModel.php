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
    }

    protected $allowedFields = [
        'No. RO', 'RO', 'PROGRAM/KEGIATAN', 'PAGU', 'REALISASI',
        'Capaian Realisasi', 'Target TW', 'CAPAIAN_TARGET_TW',
        'Kategori TW', 'Bulan', 'Tahun'
    ];

    public function importData($file)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            // Coba ambil sheet 'Anggaran'
            $sheet = null;
            $targetSheetName = 'Anggaran';
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
                 
                 // 1. Tahun
                 $tahunVal = $getVal('tahun') ?? $currentYear;
                 
                 // 2. Target TW (Might be "% Target TW")
                 // Check map keys for partial matching if exact fail
                 $targetTwIdx = $map['% target tw'] ?? $map['target tw'] ?? null;
                 $targetTw = ($targetTwIdx !== null) ? $r[$targetTwIdx] : 0;

                 // 3. Capaian Target (Might be "CAPAIAN TERHADAP TARGET TW")
                 $capTargetIdx = $map['capaian terhadap target tw'] ?? $map['capaian target tw'] ?? null;
                 $capTarget = ($capTargetIdx !== null) ? $r[$capTargetIdx] : 0;
                 
                 // 4. Capaian Realisasi
                 $capRealIdx = $map['capaian realisasi'] ?? null;
                 $capReal = ($capRealIdx !== null) ? $r[$capRealIdx] : 0;

                 $data[] = [
                    'tahun'             => $tahunVal,
                    'bulan'             => $getVal('bulan') ?? '',
                    'no_ro'             => $getVal('no. ro') ?? 0,
                    'ro'                => $getVal('ro') ?? '',
                    'program'           => $getVal('program/kegiatan') ?? '',
                    'pagu'              => $getVal('pagu') ?? 0,
                    'realisasi'         => $getVal('realisasi') ?? 0,
                    'capaian_realisasi' => $capReal,
                    'target_tw'         => $targetTw,
                    'capaian_target_tw' => $capTarget,
                    'kategori_tw'       => $getVal('kategori tw') ?? ''
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
