<?php

namespace App\Models\Entry;

use CodeIgniter\Model;

class NkoEntryModel extends Model
{
    protected $config;

    public function __construct()
    {
        parent::__construct();
        $this->config = config('DataMapping');
        $this->table = $this->config->tables['nko'];
    }

    protected $allowedFields = ['Tahun', 'Bulan', 'Total Capaian', 'Total IKU', 'NKO'];

    public function importData($file)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            
            // Ambil sheet dari Config
            $sheet = null;
            $targetSheetName = $this->config->sheets['nko'];
            foreach ($spreadsheet->getSheetNames() as $sheetName) {
                if (strcasecmp($sheetName, $targetSheetName) === 0) {
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

            // Required columns (from Config)
            $required = $this->config->headers['nko_required']; 
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
        $builder = $db->table($this->table);
        
        $db->transBegin();

        try {
            // Persiapkan data untuk batch insert
            $newEntries = [];
            foreach ($data as $row) {
                // DELETE old data based on unique key (Tahun + Bulan)
                // Note: Deleting inside loop is still necessary if we want to clear specific months
                $builder->where('Tahun', $row['tahun'])
                        ->where('Bulan', $row['bulan'])
                        ->delete();

                // Prepare new entry
                $newEntries[] = [
                    'Tahun'         => $row['tahun'],
                    'Bulan'         => $row['bulan'],
                    'Total Capaian' => $row['total_capaian'],
                    'Total IKU'     => $row['total_iku'],
                    'NKO'           => $row['nko']
                ];
            }

            // Perform Batch Insert if we have data
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
}
