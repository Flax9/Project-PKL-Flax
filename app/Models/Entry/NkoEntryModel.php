<?php

namespace App\Models\Entry;

use CodeIgniter\Model;

class NkoEntryModel extends Model
{
    protected $table = 'nko';
    protected $allowedFields = ['Tahun', 'Bulan', 'Total Capaian', 'Total IKU', 'NKO'];

    public function importData($file)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            
            // Coba ambil sheet bernama 'NKO' (case-insensitive)
            $sheet = null;
            foreach ($spreadsheet->getSheetNames() as $sheetName) {
                if (strcasecmp($sheetName, 'NKO') === 0) {
                    $sheet = $spreadsheet->getSheetByName($sheetName);
                    break;
                }
            }

            // Jika tidak ada sheet 'NKO', gunakan sheet yang aktif
            if (!$sheet) {
                $sheet = $spreadsheet->getActiveSheet();
            }

            $highestRow = $sheet->getHighestRow();

            // Header: Bulan;Total Capaian;Total IKU;NKO;Tahun
            $headerRow = $sheet->rangeToArray('A1:Z1', null, true, false)[0];
            
            // Map header positions
            $map = [];
            foreach ($headerRow as $idx => $val) {
                if (empty($val)) continue;
                $key = trim(strtolower($val));
                $map[$key] = $idx;
            }

            // Required columns (Tahun is now optional)
            $required = ['bulan', 'total capaian', 'total iku', 'nko']; 
            $missing = [];

            foreach ($required as $req) {
                 if (!isset($map[$req])) {
                     $found = false;
                     foreach($map as $k => $i) {
                         if ($k == $req) { $found = true; break; }
                     }
                     if (!$found) $missing[] = $req;
                 }
            }

            if (!empty($missing)) {
                 throw new \Exception('Kolom tidak ditemukan: ' . implode(', ', $missing) . '. Pastikan header minimal: Bulan;Total Capaian;Total IKU;NKO');
            }

            $currentYear = date('Y');

            $data = [];
            for($row=2; $row<=$highestRow; $row++) {
                // Read row by index
                $r = $sheet->rangeToArray("A{$row}:Z{$row}", null, true, false)[0];
                
                // Check if empty row
                $allEmpty = true;
                foreach($required as $req) {
                    if(!empty($r[$map[$req]])) $allEmpty = false;
                }
                if($allEmpty) continue;

                // Tahun priority: 
                // 1. Column 'tahun' if exists
                // 2. Default to Current Year
                $tahunVal = isset($map['tahun']) ? ($r[$map['tahun']] ?? $currentYear) : $currentYear;
                if(empty($tahunVal)) $tahunVal = $currentYear;

                $data[] = [
                    'bulan'         => $r[$map['bulan']] ?? '',
                    'total_capaian' => $r[$map['total capaian']] ?? 0,
                    'total_iku'     => $r[$map['total iku']] ?? 0,
                    'nko'           => $r[$map['nko']] ?? 0,
                    'tahun'         => $tahunVal
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
        $builder = $db->table('nko');

        try {
            // Hapus data lama (overwrite per bulan/tahun)
            foreach ($data as $row) {
                 // 1. Delete old data
                 $builder->where('Tahun', $row['tahun'])
                         ->where('Bulan', $row['bulan']);
                 $builder->delete();
                 
                 // 2. Insert new data (Raw SQL to handle spaces in column names)
                 $sql = "INSERT INTO nko (Tahun, Bulan, `Total Capaian`, `Total IKU`, NKO) VALUES (?, ?, ?, ?, ?)";
                 $res = $db->query($sql, [
                    $row['tahun'],
                    $row['bulan'],
                    $row['total_capaian'],
                    $row['total_iku'],
                    $row['nko']
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
}
