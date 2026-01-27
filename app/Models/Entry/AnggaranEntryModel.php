<?php

namespace App\Models\Entry;

use CodeIgniter\Model;

class AnggaranEntryModel extends Model
{
    protected $table = 'anggaran';
    protected $allowedFields = [
        'No. RO', 'RO', 'PROGRAM/KEGIATAN', 'PAGU', 'REALISASI',
        'Capaian Realisasi', 'Target TW', 'CAPAIAN_TARGET_TW',
        'Kategori TW', 'Bulan', 'Tahun'
    ];

    public function importData($file)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
             // Coba ambil sheet 'Anggaran' atau active
            $sheet = null;
            foreach ($spreadsheet->getSheetNames() as $sheetName) {
                if (strcasecmp($sheetName, 'Anggaran') === 0) {
                    $sheet = $spreadsheet->getSheetByName($sheetName);
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
        $builder = $db->table('anggaran');
        
        try {
            foreach ($data as $row) {
                // Delete Logic: Delete by No. RO + Month + Year to overwrite specific entries
                // Assuming efficient enough for batch
                $builder->where('Tahun', $row['tahun'])
                        ->where('Bulan', $row['bulan'])
                        ->where('`No. RO`', $row['no_ro'], false) // Manual backticks + disable escape
                        ->delete();
                
                // Insert (Raw SQL)
                $sql = "INSERT INTO anggaran 
                        (`No. RO`, `RO`, `PROGRAM/KEGIATAN`, `PAGU`, `REALISASI`, `Capaian Realisasi`, `Target TW`, `CAPAIAN_TARGET_TW`, `Kategori TW`, `Bulan`, `Tahun`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $res = $db->query($sql, [
                    $row['no_ro'],
                    $row['ro'],
                    $row['program'],
                    $row['pagu'],
                    $row['realisasi'],
                    $row['capaian_realisasi'],
                    $row['target_tw'],
                    $row['capaian_target_tw'],
                    $row['kategori_tw'],
                    $row['bulan'],
                    $row['tahun']
                ]);

                 if (!$res) {
                    $err = $db->error();
                    throw new \Exception("Gagal insert: " . $err['message']);
                }
            }
        } catch (\Throwable $e) {
             return ['status' => 'error', 'message' => $e->getMessage()];
        }

        return ['status' => 'success'];
    }

    public function getMasterAnggaran()
    {
        $db = \Config\Database::connect();
        return $db->table('master_anggaran')
                  ->select('no_ro, ro, program_kegiatan')
                  ->orderBy('no_ro', 'ASC')
                  ->get()
                  ->getResultArray();
    }
}
