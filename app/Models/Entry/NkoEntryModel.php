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
        $this->allowedFields = config('DataMapping')->allowedFields['nko'];
    }

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
            // Validasi Header (STRICT) - Updated for Dynamic Schema
            $schema = $this->config->headers['nko_required'];
            $expectedHeaders = array_keys($schema);
            
            // Map and Validate
            $map = []; 
            $missing = [];
            
            foreach ($headerRow as $idx => $val) {
                if (empty($val)) continue;
                $key = trim($val);
                $map[strtolower($key)] = $idx;
            }

            foreach ($expectedHeaders as $req) {
                 if (!isset($map[strtolower($req)])) {
                     $missing[] = $req;
                 }
            }

            if (!empty($missing)) {
                throw new \Exception("Header file tidak sesuai. Kolom hilang: " . implode(', ', $missing));
            }

            // Data Processing
            $data = [];
            
            for ($row = 2; $row <= $highestRow; $row++) {
                $r = $sheet->rangeToArray("A{$row}:Z{$row}", null, true, false)[0];
                
                if (empty(array_filter($r))) continue;

                // Helper Sanitizer based on Schema
                $getVal = function($k) use ($map, $r) {
                     // Normalize key to lower to match map keys
                     $normalizedKey = strtolower($k);
                     return isset($map[$normalizedKey]) ? $r[$map[$normalizedKey]] : null;
                };

                $sanitize = function($key, $val) use ($schema) {
                     $type = $schema[$key] ?? 'string';
                     if ($val === null) return $val;
                     
                     switch($type) {
                         case 'decimal':
                             return (float) filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                         case 'integer':
                             return (int) filter_var($val, FILTER_SANITIZE_NUMBER_INT);
                         case 'string':
                         default:
                             return trim(htmlspecialchars((string)$val));
                     }
                };
                
                // Get Tahun (Priority: Excel > Current Year)
                $tahunStr = $getVal('tahun');
                $tahunVal = !empty($tahunStr) ? $sanitize('tahun', $tahunStr) : date('Y');

                $data[] = [
                    'tahun'         => $tahunVal,
                    'bulan'         => $sanitize('bulan', $getVal('bulan')),
                    'total_capaian' => $sanitize('total capaian', $getVal('total capaian')),
                    'total_iku'     => $sanitize('total iku', $getVal('total iku')),
                    'nko'           => $sanitize('nko', $getVal('nko'))
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
            $sql = "INSERT INTO {$this->table} (`Tahun`, `Bulan`, `Total Capaian`, `Total IKU`, `NKO`) VALUES (?, ?, ?, ?, ?)";

            foreach ($data as $row) {
                // DELETE old data based on unique key (Tahun + Bulan)
                $builder->where('Tahun', $row['tahun'])
                        ->where('Bulan', $row['bulan'])
                        ->delete();

                $db->query($sql, [
                    $row['tahun'],
                    $row['bulan'],
                    $row['total_capaian'] === '' ? 0 : $row['total_capaian'],
                    $row['total_iku'] === '' ? 0 : $row['total_iku'],
                    $row['nko'] === '' ? 0 : $row['nko']
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
}
