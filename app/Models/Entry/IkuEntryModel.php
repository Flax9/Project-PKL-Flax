<?php

namespace App\Models\Entry;

use CodeIgniter\Model;

class IkuEntryModel extends Model
{
    protected $table = 'capaian_iku';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'Fungsi', 'No. Indikator', 'No. IKU', 'Nama Indikator', 'No. Bulan',
        'Bulan', 'Target', 'Realisasi', 'Performa % Capaian Bulan',
        'Kategori Capaian Bulan', 'Performa % Capaian Tahun',
        'Kategori Capaian Tahun', 'Capaian Normalisasi',
        'Capaian normalisasi Angka', 'Tahun'
    ];

    public function importData($file)
    {
        try {
            // Load PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());

            // Validasi: Hanya terima sheet bernama "Capaian IKU"
            $sheetNames = $spreadsheet->getSheetNames();
            
            if (!in_array('Capaian IKU', $sheetNames)) {
                throw new \Exception('Sheet "Capaian IKU" tidak ditemukan. Sheet yang tersedia: ' . implode(', ', $sheetNames));
            }

            $sheet = $spreadsheet->getSheetByName('Capaian IKU');
            $highestRow = $sheet->getHighestRow();
            $data = [];

            // Expected header
            $expectedHeaders = [
                'Fungsi', 'No. Indikator', 'No. IKU', 'Nama Indikator', 'No. Bulan', 'Bulan',
                'Target', 'Realisasi', 'Performa %Capaian Bulan', 'Kategori Capaian Bulan',
                'Performa %Capaian Tahun', 'Kategori Capaian Tahun', 'Capaian Normalisasi',
                'Capaian normalisasi Angka', 'Tahun'
            ];

            // Read header row (row 1)
            $headerRow = $sheet->rangeToArray('A1:O1', null, true, false)[0];
            
            // Normalize headers for comparison (trim and lowercase)
            $normalizedHeaderRow = array_map(function($h) {
                return trim(strtolower($h ?? ''));
            }, $headerRow);
            
            $normalizedExpected = array_map(function($h) {
                return trim(strtolower($h));
            }, $expectedHeaders);
            
            // Validate headers
            if ($normalizedHeaderRow !== $normalizedExpected) {
                 throw new \Exception('Format header tidak sesuai. Expected: ' . implode(', ', $expectedHeaders) . '. Got: ' . implode(', ', $headerRow));
            }

            // Map bulan name to number
            $mapBulan = [
                'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
            ];

            // Read data rows (starting from row 2)
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray("A{$row}:O{$row}", null, true, false)[0];

                // Skip empty rows
                if (empty(array_filter($rowData))) {
                    continue;
                }

                // Map to queueData structure
                $data[] = [
                    'fungsi'                        => $rowData[0] ?? '',
                    'no_indikator'                  => $rowData[1] ?? '',
                    'no_iku'                        => $rowData[2] ?? '',
                    'nama_indikator'                => $rowData[3] ?? '',
                    'no_bulan'                      => $rowData[4] ?? ($mapBulan[$rowData[5]] ?? 0),
                    'bulan'                         => $rowData[5] ?? '',
                    'target'                        => $rowData[6] ?? 0,
                    'realisasi'                     => $rowData[7] ?? 0,
                    'perf_bulan'                    => $rowData[8] ?? 0,
                    'kat_bulan'                     => $rowData[9] ?? '',
                    'perf_tahun'                    => $rowData[10] ?? 0,
                    'kat_tahun'                     => $rowData[11] ?? '',
                    'capaian_normalisasi_persen'    => $rowData[12] ?? 0,
                    'capaian_normalisasi_angka'     => $rowData[13] ?? 0,
                    'tahun'                         => $rowData[14] ?? ''
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

        $sql = "
            INSERT INTO capaian_iku (
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
     * Get IKU list by/year, handling dynamic table names
     */
    public function getIkuByTahun($tahun)
    {
        $db = \Config\Database::connect();
        $table = 'database_iku_'.$tahun;

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
