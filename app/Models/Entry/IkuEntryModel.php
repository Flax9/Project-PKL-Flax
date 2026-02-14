<?php

namespace App\Models\Entry;

use CodeIgniter\Model;

class IkuEntryModel extends Model
{
    protected $config;

    public function __construct()
    {
        parent::__construct();
        $this->config = config('DataMapping');
        $this->table = $this->config->tables['capaian_iku'];
        $this->allowedFields = $this->config->allowedFields['iku'];
    }

    protected $primaryKey = 'id';

    public function importData($file)
    {
        try {
            // Load PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());

            // Validasi: Ambil sheet dari Config
            $sheetNames = $spreadsheet->getSheetNames();
            $targetSheet = $this->config->sheets['iku'];
            
            if (!in_array($targetSheet, $sheetNames)) {
                throw new \Exception("Sheet \"$targetSheet\" tidak ditemukan. Sheet yang tersedia: " . implode(', ', $sheetNames));
            }

            $sheet = $spreadsheet->getSheetByName($targetSheet);
            $highestRow = $sheet->getHighestRow();
            $data = [];

            // Read header row (row 1)
            $headerRow = $sheet->rangeToArray('A1:Z1', null, true, false)[0]; // Read up to Z for flexibility

            // Validasi Header (STRICT) - Updated for Dynamic Schema
            $schema = $this->config->headers['iku_import'];
            $expectedHeaders = array_keys($schema);
            
            // Check count match first
            if (count(array_filter($headerRow)) < count($expectedHeaders)) { // Use array_filter to count non-empty headers
                // throw new \Exception... (Optional: bisa diskip jika ingin fleksibel)
            }

            // Map and Validate
            $map = []; 
            $missing = [];
            
            foreach ($headerRow as $idx => $val) {
                if (empty($val)) continue;
                $key = trim($val); // Case sensitive for strict check? Or standardize?
                // Let's rely on standard search
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
                     return isset($map[strtolower($k)]) ? $r[$map[strtolower($k)]] : null;
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

                $data[] = [
                    'fungsi'                    => $sanitize('Fungsi', $getVal('Fungsi')),
                    'no_indikator'              => $sanitize('No. Indikator', $getVal('No. Indikator')),
                    'no_iku'                    => $sanitize('No. IKU', $getVal('No. IKU')),
                    'nama_indikator'            => $sanitize('Nama Indikator', $getVal('Nama Indikator')),
                    'no_bulan'                  => $sanitize('No. Bulan', $getVal('No. Bulan')),
                    'bulan'                     => $sanitize('Bulan', $getVal('Bulan')),
                    'target'                    => $sanitize('Target', $getVal('Target')),
                    'realisasi'                 => $sanitize('Realisasi', $getVal('Realisasi')),
                    'perf_bulan'                => $sanitize('Performa %Capaian Bulan', $getVal('Performa %Capaian Bulan')),
                    'kat_bulan'                 => $sanitize('Kategori Capaian Bulan', $getVal('Kategori Capaian Bulan')),
                    'perf_tahun'                => $sanitize('Performa %Capaian Tahun', $getVal('Performa %Capaian Tahun')),
                    'kat_tahun'                 => $sanitize('Kategori Capaian Tahun', $getVal('Kategori Capaian Tahun')),
                    'capaian_normalisasi_persen'=> $sanitize('Capaian Normalisasi', $getVal('Capaian Normalisasi')),
                    'capaian_normalisasi_angka' => $sanitize('Capaian normalisasi Angka', $getVal('Capaian normalisasi Angka')),
                    'tahun'                     => $sanitize('Tahun', $getVal('Tahun'))
                ];
            }

            return [
                'status' => 'success',
                'data'   => $data,
                'count'  => count($data)
            ];

        } catch (\Throwable $e) {
            return [
                'status'  => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function saveBatchData($dataArray)
    {
        $db = \Config\Database::connect();

        $table = $this->config->tables['capaian_iku'];
        $sql = "
            INSERT INTO $table (
                `Fungsi`,
                `No. Indikator`,
                `No. IKU`,
                `Nama Indikator`,
                `No. Bulan`,

                `Bulan`,
                `Target`,
                `Realisasi`,
                `Performa % Capaian Bulan`,
                `Kategori Capaian Bulan`,
                `Performa % Capaian Tahun`,
                `Kategori Capaian Tahun`,
                `Capaian Normalisasi`,
                `Capaian normalisasi Angka`,
                `Tahun`
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $db->transBegin();

        try {
            foreach ($dataArray as $row) {
                $db->query($sql, [
                    $row['fungsi'],
                    $row['no_indikator'],
                    $row['no_iku'],
                    $row['nama_indikator'],
                    $row['no_bulan'],

                    $row['bulan'],
                    $row['target'],
                    $row['realisasi'],
                    $row['perf_bulan'],
                    $row['kat_bulan'],
                    $row['perf_tahun'],
                    $row['kat_tahun'],
                    $row['capaian_normalisasi_persen'],
                    $row['capaian_normalisasi_angka'],
                    $row['tahun'],
                ]);
            }

            $db->transCommit();

            return [
                'status' => 'success',
                'count'  => count($dataArray)
            ];

        } catch (\Throwable $e) {
            $db->transRollback();
            return [
                'status'  => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get distinct list of functions
     */
    public function getListFungsi()
    {
        return $this->select('Fungsi')
                    ->distinct()
                    ->get()
                    ->getResult();
    }

    /**
     * Get distinct list of IKU labels
     */
    public function getListIku()
    {
        return $this->select('CONCAT("IKU ", `No. IKU`) as no_iku, `No. Indikator` as no_indikator', false)
                    ->distinct()
                    ->orderBy('no_indikator', 'ASC')
                    ->get()
                    ->getResultArray();
    }

    /**
     * Get distinct list of Indicators
     */
    public function getListNamaIndikator()
    {
        return $this->select('`Nama Indikator` as nama_indikator, `No. Indikator` as no_indikator', false)
                    ->distinct()
                    ->orderBy('no_indikator', 'ASC')
                    ->get()
                    ->getResultArray();
    }

    /**
     * Get IKU list by/year, handling dynamic table names
     */
    public function getIkuByTahun($tahun)
    {
        $db = \Config\Database::connect();
        $table = $this->config->tables['iku_year_prefix'] . $tahun;

        try {
            // Try resilient query logic
            try {
                $sql = "SELECT DISTINCT `No.IKU` AS no_iku, CONCAT('IKU ', `No.IKU`) AS iku_label, `Nama Indikator` AS nama_indikator FROM `$table` ORDER BY `No.IKU` ASC";
                $query = $db->query($sql);
            } catch (\Throwable $e_first) {
                // Fallback if formatting differs
                $sql = "SELECT DISTINCT `No. IKU` AS no_iku, CONCAT('IKU ', `No. IKU`) AS iku_label, `Nama Indikator` AS nama_indikator FROM `$table` ORDER BY `No. IKU` ASC";
                $query = $db->query($sql);
            }
            return $query->getResultArray();

        } catch (\Throwable $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}
