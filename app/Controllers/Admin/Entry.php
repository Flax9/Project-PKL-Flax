<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Entry extends BaseController
{
    // --- 1. GATEWAY & AUTHENTICATION ---

    public function verify()
    {
        return view('admin/entry/verify');
    }

    public function checkAuth()
    {
        $db = \Config\Database::connect();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $db->table('users')->where('username', $username)->get()->getRow();

        if ($user && $password === $user->password) {
            session()->set([
                'isLoggedIn' => true,
                'username'   => $user->username,
                'role'       => $user->role
            ]);
            session()->setFlashdata('access_granted', true);

            if ($user->role === 'perencana') {
                return redirect()->to('admin/entry/monitoring'); 
            } else {
                return redirect()->to('admin/entry/selection');
            }
        }
        return redirect()->back()->with('error', 'Username atau Password salah!');
    }

    public function index()
    {
        if (!session()->getFlashdata('access_granted')) {
            return redirect()->to('admin/entry/verify');
        }
        return redirect()->to('admin/entry/selection');
    }

    public function selection()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('admin/entry/verify');
        }

        $data = [
            'activeMenu' => 'data_entry',
            'title'      => 'Pilih Metode Pengelolaan'
        ];

        return view('admin/entry/selection', $data);
    }

    // --- 2. HALAMAN INPUT DATA (RUTIN) ---

    public function rutin()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('admin/entry/verify');
        }
        
        $db = \Config\Database::connect();
        
        // 1. Query Fungsi (Tetap)
        $list_fungsi = $db->table('capaian_iku')
                        ->select('Fungsi')
                        ->distinct()
                        ->get()
                        ->getResult();
        
        // 2. Query List IKU (PERBAIKAN SINTAKS DISINI)
        $list_iku = $db->table('capaian_iku')
                      // Perhatikan koma ada DI DALAM tanda kutip
                        ->select('CONCAT("IKU ", `No. IKU`) as no_iku, `No. Indikator` as no_indikator', false)
                       ->distinct()
                       ->orderBy('no_indikator', 'ASC') // Gunakan alias 'no_indikator' agar aman
                       ->get()
                       ->getResultArray();

        // 3. Query List Nama Indikator (PERBAIKAN SINTAKS DISINI)
        $list_nama_indikator = $db->table('capaian_iku')
                                ->select('`Nama Indikator` as nama_indikator, `No. Indikator` as no_indikator', false)
                                ->distinct()
                                ->orderBy('no_indikator', 'ASC')
                                ->get()
                                ->getResultArray();

        $data = [
            'activeMenu'  => 'data_entry',
            'title'       => 'Input Realisasi Rutin',
            'list_fungsi' => $list_fungsi,
            'list_iku'    => [],
            'list_nama_indikator' => []

        ];

        return view('admin/entry/index', $data);
    }

    // --- Deteksi IKU tersedia berdasarkan tahun terpilih ---
    public function get_iku_by_tahun($tahun = null)
    {
        /* ==================================================
        VALIDASI REQUEST
        ================================================== */

        // ❌ SEBELUMNYA:
        // - isAJAX() gagal karena header tidak dikirim
        // - request di-return 404
        //
        // ✅ SEKARANG:
        // - JS mengirim header AJAX → isAJAX() TRUE
        if (!$this->request->isAJAX() || empty($tahun)) {
            return $this->response->setStatusCode(404);
        }

        $db = \Config\Database::connect();

        // Dynamic table berdasarkan tahun
        $table = 'database_iku_'.$tahun;

        try {
            /* ==================================================
            ❌ SEBELUMNYA:
            - Query Builder + kolom "No.IKU"
            - DISTINCT + ORDER BY sering error diam-diam
            ================================================== */

            // ✅ SOLUSI RESILIENT (TAHAN BANTING)
            // Coba pakai "No.IKU" (tanpa spasi) dulu
            try {
                $sql = "SELECT DISTINCT `No.IKU` AS no_iku, CONCAT('IKU ', `No.IKU`) AS iku_label, `Nama Indikator` AS nama_indikator FROM `$table` ORDER BY `No.IKU` ASC";
                $query = $db->query($sql);
            } catch (\Throwable $e_first) {
                // Jika error, coba pakai "No. IKU" (pakai spasi)
                log_message('warning', 'Query No.IKU gagal, mencoba No. IKU. Error: ' . $e_first->getMessage());
                
                $sql = "SELECT DISTINCT `No. IKU` AS no_iku, CONCAT('IKU ', `No. IKU`) AS iku_label, `Nama Indikator` AS nama_indikator FROM `$table` ORDER BY `No. IKU` ASC";
                $query = $db->query($sql);
            }

            // Log hasil untuk debugging
            $result = $query->getResultArray();
            log_message('error', 'DEBUG RESULT ' . $tahun . ': Ditemukan ' . count($result) . ' baris.');

            return $this->response->setJSON($result);

        } catch (\Throwable $e) {

            // Logging untuk debugging fatal
            log_message('error', '[IKU AJAX FATAL] ' . $e->getMessage());
            
            // Return error JSON agar JS bisa alert
            return $this->response->setJSON([
                'error' => true,
                'message' => $e->getMessage()
            ])->setStatusCode(200); // Set 200 biar JS memproses response-nya
        }
    }


    // --- 3. PROSES PENYIMPANAN DATA (RAW SQL – OPSI 2) ---

    public function simpan_iku_batch()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $jsonItem  = $this->request->getPost('bulk_data');
        $dataArray = json_decode($jsonItem, true);

        if (empty($dataArray)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data kosong'
            ]);
        }

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

            return $this->response->setJSON([
                'status' => 'success',
                'count'  => count($dataArray)
            ]);

        } catch (\Throwable $e) {

            $db->transRollback();

            log_message('error', $e->getMessage());

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // --- 4. IMPORT EXCEL/CSV FOR IKU ---
    public function import_iku()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $file = $this->request->getFile('file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'File tidak valid atau tidak ditemukan'
            ]);
        }

        try {
            // Log file info for debugging
            log_message('info', '[IMPORT IKU] File received: ' . $file->getName() . ', Size: ' . $file->getSize() . ', Type: ' . $file->getMimeType());
            
            // Load PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());

            // Validasi: Hanya terima sheet bernama "Capaian IKU"
            $sheetNames = $spreadsheet->getSheetNames();
            log_message('info', '[IMPORT IKU] Available sheets: ' . implode(', ', $sheetNames));
            
            if (!in_array('Capaian IKU', $sheetNames)) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Sheet "Capaian IKU" tidak ditemukan. Sheet yang tersedia: ' . implode(', ', $sheetNames)
                ]);
            }

            $sheet = $spreadsheet->getSheetByName('Capaian IKU');
            $highestRow = $sheet->getHighestRow();
            $data = [];

            // Expected header (semicolon-delimited in Excel, but PhpSpreadsheet reads as separate columns)
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
                // Log the actual headers for debugging
                log_message('error', '[IMPORT IKU] Header mismatch. Expected: ' . json_encode($expectedHeaders) . ', Got: ' . json_encode($headerRow));
                
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Format header tidak sesuai. Expected: ' . implode(', ', $expectedHeaders) . '. Got: ' . implode(', ', $headerRow)
                ]);
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



            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $data,
                'count'  => count($data)
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[IMPORT IKU ERROR] ' . $e->getMessage());
            
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal memproses file: ' . $e->getMessage()
            ]);
        }
    }

    // --- 5. NKO FUNCTIONALITY ---
    
    // Import NKO
    public function import_nko()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $file = $this->request->getFile('file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File invalid']);
        }

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
            $required = ['bulan', 'total capaian', 'total iku', 'nko']; // Removed 'tahun'
            $missing = [];

            foreach ($required as $req) {
                 // Check partial match or exact match
                 if (!isset($map[$req])) {
                     $found = false;
                     foreach($map as $k => $i) {
                         if ($k == $req) { $found = true; break; }
                     }
                     if (!$found) $missing[] = $req;
                 }
            }

            if (!empty($missing)) {
                 return $this->response->setJSON([
                    'status' => 'error', 
                    'message' => 'Kolom tidak ditemukan: ' . implode(', ', $missing) . '. Pastikan header minimal: Bulan;Total Capaian;Total IKU;NKO'
                ]);
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

            return $this->response->setJSON(['status' => 'success', 'data' => $data, 'count' => count($data)]);

        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Simpan NKO Batch
    public function simpan_nko_batch()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $json = $this->request->getPost('bulk_data');
        $data = json_decode($json, true);

        if (empty($data)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data kosong']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('nko');

        try {
            // Hapus data lama (overwrite per bulan/tahun)
            foreach ($data as $row) {
                 // Check column names with spaces: use backticks just in case, or trust CI.
                 // CI4 usually escapes keys automatically.
                 // Let's rely on standard keys first.

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
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
        
        return $this->response->setJSON(['status' => 'success']);
    }

    // --- 6. ANGGARAN FUNCTIONALITY ---
    
    // Import Anggaran
    public function import_anggaran()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $file = $this->request->getFile('file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File invalid']);
        }

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

            return $this->response->setJSON(['status' => 'success', 'data' => $data, 'count' => count($data)]);

        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Simpan Anggaran Batch
    public function simpan_anggaran_batch()
    {
         if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $json = $this->request->getPost('bulk_data');
        if (!$json) return $this->response->setJSON(['status' => 'error', 'message' => 'Data kosong']);

        $data = json_decode($json, true);
        if (empty($data)) return $this->response->setJSON(['status' => 'error', 'message' => 'Data kosong']);

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
             return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }
    // Get Master Anggaran for Dropdown
    public function get_master_anggaran()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        
        $db = \Config\Database::connect();
        $data = $db->table('master_anggaran')
                   ->select('no_ro, ro, program_kegiatan')
                   ->orderBy('no_ro', 'ASC')
                   ->get()
                   ->getResultArray();
                   
        return $this->response->setJSON($data);
    }

    // ========== CAPAIAN OUTPUT METHODS ==========
    
    // Get Master RO for Capaian Output Dropdown
    public function get_master_capaian_output()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        
        try {
            $db = \Config\Database::connect();
            $data = $db->table('database_ro')
                       ->select('`No. RO`, RO, `Rincian Output`, `Kertas Kerja ro`, `Manual RO`', false)
                       ->orderBy('`No. RO`', 'ASC', false)
                       ->get()
                       ->getResultArray();
            
            log_message('debug', 'Capaian Output data count: ' . count($data));
            
            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            log_message('error', 'Error in get_master_capaian_output: ' . $e->getMessage());
            return $this->response->setJSON(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    // Import Excel for Capaian Output
    public function import_capaian_output()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid']);
        }

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
                    'rincian_output'               => $getVal('rincian output') ?? '',
                    'no_ro'                        => $getVal('no. ro') ?? $getVal('no.ro') ?? 0,
                    'keterangan_ro'                => $getVal('keterangan ro') ?? '',
                    'fungsi'                       => $getVal('fungsi') ?? '',
                    'target_persen_bulan'          => $getVal('target % bulan') ?? $getVal('target persen bulan') ?? 0,
                    'realisasi'                    => $getVal('realisasi') ?? 0,
                    'persen_realisasi'             => $getVal('% realisasi') ?? $getVal('persen realisasi') ?? 0,
                    'realisasi_kumulatif'          => $getVal('realisasi kumulatif') ?? 0,
                    'persen_realisasi_kumulatif'   => $getVal('% realisasi kumulatif') ?? $getVal('persen realisasi kumulatif') ?? 0,
                    'capaian'                      => $getVal('capaian') ?? 0,
                    'kategori'                     => $getVal('kategori') ?? '',
                    'target_tahun'                 => $getVal('target tahun') ?? 0,
                    'kategori_belanja'             => $getVal('kategori belanja') ?? '',
                    'realisasi_kumulatif_persen'   => $getVal('realisasi kumulatif %') ?? $getVal('realisasi kumulatif persen') ?? 0
                 ];
            }

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data,
                'count' => count($data)
            ]);

        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Batch Save Capaian Output
    public function simpan_capaian_output_batch()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $bulkJson = $this->request->getPost('bulk_data');
        if (!$bulkJson) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data kosong']);
        }

        $data = json_decode($bulkJson, true);
        if (!is_array($data) || count($data) === 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Format data salah']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('capaian_output');
        
        try {
            foreach ($data as $row) {
                // Delete Logic: Delete by Tahun + Bulan + No. RO
                $builder->where('Tahun', $row['tahun'])
                        ->where('Bulan', $row['bulan'])
                        ->where('`No. RO`', $row['no_ro'], false)
                        ->delete();
                
                // Insert (Raw SQL)
                $sql = "INSERT INTO capaian_output 
                        (`Rincian Output`, `No. RO`, `Keterangan RO`, `Fungsi`, `No. Bulan`, `Bulan`, `Target % Bulan`, `Realisasi`, `% Realisasi`, `Realisasi Kumulatif`, `salah % Realisasi Kumulatif`, `Capaian`, `Kategori`, `Target tahun`, `Kategori Belanja`, `Realisasi Kumulatif %`, `Tahun`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $res = $db->query($sql, [
                    $row['kode_ro'] ?? $row['rincian_output'], // Request: RO mengisi kolom Rincian Output
                    $row['no_ro'],
                    $row['keterangan_ro'] ?? '', 
                    $row['fungsi'] ?? '',        
                    $row['no_bulan'],
                    $row['bulan'],
                    $row['target_persen_bulan'],
                    $row['realisasi'],
                    $row['persen_realisasi'],
                    $row['realisasi_kumulatif'],
                    $row['salah_persen_realisasi_kumulatif'],
                    $row['capaian'],
                    $row['kategori'],
                    $row['target_tahun'],
                    $row['kategori_belanja'],
                    $row['realisasi_kumulatif_persen'],
                    $row['tahun']
                ]);

                 if (!$res) {
                    $err = $db->error();
                    throw new \Exception("Gagal insert: " . $err['message']);
                }
            }
        } catch (\Throwable $e) {
             return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }
}