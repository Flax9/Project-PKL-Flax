<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\Entry\IkuEntryModel;

class Pengajuan extends BaseController
{
    protected $pengajuanModel;
    protected $ikuModel;

    public function __construct()
    {
        $this->pengajuanModel = new PengajuanModel();
        $this->ikuModel = new IkuEntryModel();
    }

    // 1. GATEWAY PAGE (Menu Selection)
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('admin/entry/verify');
        }

        $data = [
            'activeMenu' => 'perubahan_data',
            'title' => 'Menu Perubahan Data',
            'role'  => session()->get('role') // 'user' or 'perencana'
        ];

        return view('admin/pengajuan/selection', $data);
    }

    // --------------------------------------------------------------------
    // PLANNER ACTIONS
    // --------------------------------------------------------------------

    public function validation()
    {
        // Enforce Role Check (Optional redundancy)
        if (session()->get('role') !== 'perencana') {
            return redirect()->to('admin/pengajuan'); // Or show 403
        }

        $status = $this->request->getGet('status') ?? 'all';

        $model = new \App\Models\PengajuanModel();
        $data = [
            'activeMenu' => 'perubahan_data',
            'title'    => 'Validasi Perubahan Data',
            'requests' => $model->getRequests(null, $status), // Pass status
            'activeStatus' => $status
        ];

        return view('admin/pengajuan/validation_list', $data);
    }

    public function detail($id = null)
    {
        if (!$id) {
            return redirect()->to('admin/pengajuan/validation');
        }

        $model = new \App\Models\PengajuanModel();
        $request = $model->find($id);

        if (!$request) {
            return redirect()->to('admin/pengajuan/validation')->with('error', 'Data tidak ditemukan.');
        }

        // Fetch SNAPSHOT Data (Nilai Semula) from Table 2
        $db = \Config\Database::connect();
        $original = $db->table('pengajuan_perubahan_original')
                       ->where('pengajuan_id', $id)
                       ->get()
                       ->getRowArray();
        
        $data = [
            'activeMenu' => 'perubahan_data',
            'title'   => 'Detail Validasi',
            'request' => $request,
            'original' => $original 
        ];

        return view('admin/pengajuan/validation_detail', $data);
    }

    // Helper: Sync Staging -> Master
    private function _sync_to_master($request)
    {
        $db = \Config\Database::connect();
        
        // 1. Fetch the SNAPSHOT to get the exact identity keys
        $snapshot = $db->table('pengajuan_perubahan_original')
                       ->where('pengajuan_id', $request['id'])
                       ->get()
                       ->getRowArray();

        if (!$snapshot) {
             $snapshot = $request;
        }

        // 2. Prepare Raw SQL to handle column names with %, spaces, and dots
        // Strategy Change: "No. Indikator" column is fragile (dots/spaces).
        // Instead, we target the row by ensuring 'Target' is NOT NULL (ignoring header rows)
        $sql = "UPDATE `capaian_iku` SET 
                `Target` = ?, 
                `Realisasi` = ?, 
                `Performa % Capaian Bulan` = ?, 
                `Kategori Capaian Bulan` = ?, 
                `Performa % Capaian Tahun` = ?, 
                `Kategori Capaian Tahun` = ?, 
                `Capaian Normalisasi` = ?, 
                `Capaian normalisasi Angka` = ?
                WHERE `Tahun` = ? 
                AND `Bulan` = ? 
                AND `Fungsi` = ? 
                AND (`Target` IS NOT NULL AND `Target` != '-' AND `Target` != '')
                AND (`No. IKU` = ? OR `No. IKU` = ?)";
        
        $ikuRaw = str_replace('IKU ', '', $snapshot['no_iku']);
                
        $binds = [
            $request['target'],
            $request['realisasi'],
            $request['perf_bulan'],
            $request['kat_bulan'],
            $request['perf_tahun'],
            $request['kat_tahun'],
            $request['cap_norm'],
            $request['cap_norm_angka'],
            $snapshot['tahun'],
            $snapshot['bulan'],
            $snapshot['fungsi'],
            // removed no_indikator
            $ikuRaw,          // Value 1
            'IKU ' . $ikuRaw  // Value 2
        ];

        // TEMPORARILY DISABLED TO ISOLATE ERROR
        $db->query($sql, $binds);
    }

    // 2. FORM PENGAJUAN (User View)
    public function submission()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('admin/entry/verify');
        }

        $db = \Config\Database::connect();

        // 1. Load List Fungsi (Reused from Entry)
        $list_fungsi = $db->table('capaian_iku')
                        ->select('Fungsi')
                        ->distinct()
                        ->get()
                        ->getResult();

        // 2. Load List IKU (Initial load, though AJAX is primary)
        $list_iku = $db->table('capaian_iku')
                        ->select('CONCAT("IKU ", `No. IKU`) as no_iku, `No. Indikator` as no_indikator', false)
                        ->distinct()
                        ->orderBy('no_indikator', 'ASC')
                        ->get()
                        ->getResultArray();
        
        // 3. Load List Indikator
        $list_nama_indikator = $db->table('capaian_iku')
                                ->select('`Nama Indikator` as nama_indikator, `No. Indikator` as no_indikator', false)
                                ->distinct()
                                ->orderBy('no_indikator', 'ASC')
                                ->get()
                                ->getResultArray();

        $data = [
            'activeMenu'  => 'perubahan_data',
            'title'       => 'Form Pengajuan Perubahan Data',
            'list_fungsi' => $list_fungsi,
            'list_iku'    => $list_iku,
            'list_nama_indikator' => $list_nama_indikator,
            'backUrl' => base_url('admin/pengajuan'),
            'backLabel' => 'Kembali ke Menu Perubahan Data'
        ];

        return view('admin/pengajuan/form_user', $data);
    }

    // 3. CHECK DATA (Validation before submission)
    public function check_data()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $tahun   = $this->request->getGet('tahun');
        $bulan   = $this->request->getGet('bulan');
        $fungsi  = $this->request->getGet('fungsi');
        $no_iku  = $this->request->getGet('no_iku');

        // Sanitize No. IKU (remove "IKU " prefix if exists)
        if ($no_iku) {
            $no_iku = str_replace('IKU ', '', $no_iku);
        }

        if (!$tahun || !$bulan || !$fungsi || !$no_iku) {
            return $this->response->setJSON(['status' => 'invalid', 'message' => 'Parameter tidak lengkap']);
        }

        try {
            $db = \Config\Database::connect();
            
            // Revert to 'capaian_iku' as explicitly requested
            $table = 'capaian_iku';
    
            // 1. Try EXACT match first
            // Note: We use manual chaining to ensure 'No. IKU' is treated as a single identifier
            $builder = $db->table($table);
            $builder->where('Tahun', $tahun);
            $builder->where('Bulan', $bulan);
            $builder->where('Fungsi', $fungsi);
            // Protect "No. IKU" with backticks and disable automatic escaping for this field
            // CRITICA: Since escaping is FALSE, we MUST manually quote the value!
            $builder->where('`No. IKU`', $db->escape($no_iku), false); 
            
            try {
                 $rows = $builder->get()->getResultArray();
            } catch (\Throwable $e) {
                 $rows = [];
            }
    
            // 2. If not found, try stripped "IKU " prefix (e.g. "1")
            if (empty($rows)) {
                $checkIku = str_replace('IKU ', '', $no_iku);
                
                $builder = $db->table($table);
                $builder->where('Tahun', $tahun);
                $builder->where('Bulan', $bulan);
                $builder->where('Fungsi', $fungsi);
                $builder->where('`No. IKU`', $db->escape($checkIku), false);

                 try {
                    $rows = $builder->get()->getResultArray();
                } catch (\Throwable $e) { $rows = []; }
            }
    
            // 3. If still not found, try adding "IKU " prefix (e.g. "IKU 1")
            if (empty($rows)) {
                $checkIku = 'IKU ' . str_replace('IKU ', '', $no_iku);
                
                $builder = $db->table($table);
                $builder->where('Tahun', $tahun);
                $builder->where('Bulan', $bulan);
                $builder->where('Fungsi', $fungsi);
                $builder->where('`No. IKU`', $db->escape($checkIku), false);

                 try {
                    $rows = $builder->get()->getResultArray();
                } catch (\Throwable $e) { $rows = []; }
            }
    
            if (!empty($rows)) {
                // Initialize option lists
                $options = [
                    'tahun' => [], 'bulan' => [], 'fungsi' => [], 'no_iku' => [], 'nama_indikator' => [],
                    'no_indikator' => [], 'no_bulan' => [], 'target' => [], 'realisasi' => [],
                    'perf_bulan' => [], 'kat_bulan' => [], 'perf_tahun' => [], 'kat_tahun' => [],
                    'cap_norm' => [], 'cap_norm_angka' => []
                ];

                // Helper to add unique values
                $addUnique = function(&$arr, $val) {
                    if ($val !== null && $val !== '' && !in_array($val, $arr)) {
                        $arr[] = $val;
                    }
                };

                foreach ($rows as $r) {
                    $addUnique($options['tahun'], $r['Tahun'] ?? $r['tahun'] ?? '-');
                    $addUnique($options['bulan'], $r['Bulan'] ?? $r['bulan'] ?? '-');
                    $addUnique($options['fungsi'], $r['Fungsi'] ?? $r['fungsi'] ?? '-');
                    $addUnique($options['no_iku'], $r['No. IKU'] ?? $r['No.IKU'] ?? $r['no_iku'] ?? '-');
                    $addUnique($options['nama_indikator'], $r['Nama Indikator'] ?? $r['NamaIndikator'] ?? $r['nama_indikator'] ?? '-');
                    
                    $addUnique($options['no_indikator'], $r['No. Indikator'] ?? $r['No.Indikator'] ?? $r['no_indikator'] ?? '-');
                    $addUnique($options['no_bulan'], $r['No. Bulan'] ?? $r['No.Bulan'] ?? $r['no_bulan'] ?? '-');
                    $addUnique($options['target'], $r['Target'] ?? $r['target'] ?? '-');
                    $addUnique($options['realisasi'], $r['Realisasi'] ?? $r['realisasi'] ?? '-');
                    $addUnique($options['perf_bulan'], $r['Performa % Capaian Bulan'] ?? $r['Performa %Capaian Bulan'] ?? $r['perf_bulan'] ?? '-');
                    $addUnique($options['kat_bulan'], $r['Kategori Capaian Bulan'] ?? $r['kat_bulan'] ?? '-');
                    $addUnique($options['perf_tahun'], $r['Performa % Capaian Tahun'] ?? $r['Performa %Capaian Tahun'] ?? $r['perf_tahun'] ?? '-');
                    $addUnique($options['kat_tahun'], $r['Kategori Capaian Tahun'] ?? $r['kat_tahun'] ?? '-');
                    $addUnique($options['cap_norm'], $r['Capaian Normalisasi'] ?? $r['capaian_normalisasi'] ?? '-');
                    $addUnique($options['cap_norm_angka'], $r['Capaian normalisasi Angka'] ?? $r['capaian_normalisasi_angka'] ?? '-');
                }

                // Sort options for better UX
                foreach ($options as &$opt) {
                    sort($opt);
                }

                return $this->response->setJSON([
                    'status' => 'found', 
                    'options' => $options
                ]);
            } else {
                // Debug: Get Last Query to show User
                $lastQuery = (string)$db->getLastQuery();
                
                return $this->response->setJSON([
                    'status' => 'not_found',
                    'debug'  => "Data tidak ditemukan di tabel '$table'.",
                    'query'  => $lastQuery, // Show EXACT SQL
                    'params' => "Tahun=$tahun, Bulan=$bulan, Fungsi=$fungsi, IKU=$no_iku"
                ]);
            }
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
        }

    }

    // 4. CHECK VALIDITY (Validate Full Data Tuple)
    public function check_validity()
    {
        if (!$this->request->isAJAX()) {
             return $this->response->setStatusCode(404);
        }

        $req = $this->request;
        $db = \Config\Database::connect();
        $table = 'capaian_iku';

        $builder = $db->table($table);
        
        // Helper for consistent robust where clause
        // We use $escape = false and manually quote identifiers and values
        // to prevent CI4 from mishandling columns with dots/spaces/symbols.
        $addWhere = function($col, $val) use ($builder, $db) {
            // value escaping: $db->escape() adds quotes 'val'
            // column quoting: manually add backticks `col`
            $builder->where("`$col`", $db->escape($val), false);
        };

        $addWhere('Tahun', $req->getGet('db_tahun'));
        $addWhere('Bulan', $req->getGet('db_bulan'));
        $addWhere('Fungsi', $req->getGet('db_fungsi'));
        $addWhere('No. IKU', $req->getGet('db_no_iku'));
        $addWhere('Nama Indikator', $req->getGet('db_nama_indikator'));
        
        $addWhere('No. Indikator', $req->getGet('db_no_indikator'));
        $addWhere('No. Bulan', $req->getGet('db_no_bulan'));
        $addWhere('Target', $req->getGet('db_target'));
        $addWhere('Realisasi', $req->getGet('db_realisasi'));
        
        $addWhere('Performa % Capaian Bulan', $req->getGet('db_perf_bulan'));
        $addWhere('Kategori Capaian Bulan', $req->getGet('db_kat_bulan'));
        $addWhere('Performa % Capaian Tahun', $req->getGet('db_perf_tahun'));
        $addWhere('Kategori Capaian Tahun', $req->getGet('db_kat_tahun'));
        $addWhere('Capaian Normalisasi', $req->getGet('db_cap_norm'));
        $addWhere('Capaian normalisasi Angka', $req->getGet('db_cap_norm_angka'));

        try {
            $count = $builder->countAllResults();
            
            // Debug if needed (uncomment to see query in response if error persists)
            //$lastQuery = $db->getLastQuery();
            
            if ($count > 0) {
                 return $this->response->setJSON(['status' => 'valid', 'message' => 'Data Valid! Kombinasi ditemukan di Database.']);
            } else {
                 return $this->response->setJSON(['status' => 'invalid', 'message' => 'Data Tidak Valid / Tidak Ditemukan Kombinasi Ini.']);
            }
        } catch (\Throwable $e) {
             return $this->response->setJSON([
                 'status' => 'error', 
                 'message' => $e->getMessage(),
                 'debug_query' => (string)$db->getLastQuery()
            ]);
        }
    }

    // 4. STORE (Handle User Submission)
    public function store()
    {
        // Allow both AJAX and Standard POST
        $db = \Config\Database::connect();
        
        // Validation Rules
        $rules = [
            'tahun'            => 'required',
            'bulan'            => 'required',
            'fungsi'           => 'required',
            'no_iku'           => 'required',
            'keterangan'       => 'required',
            'file_nota_dinas'  => [
                'rules' => 'uploaded[file_nota_dinas]|max_size[file_nota_dinas,5120]|ext_in[file_nota_dinas,pdf,doc,docx,jpg,jpeg,png]',
                'label' => 'Dokumen Bukti'
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle File Upload
        $file = $this->request->getFile('file_nota_dinas');
        $fileName = null;

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            // Move to public/uploads/nota_dinas (ensure folder exists)
            if (!is_dir(FCPATH . 'uploads/nota_dinas')) {
                mkdir(FCPATH . 'uploads/nota_dinas', 0777, true);
            }
            $file->move(FCPATH . 'uploads/nota_dinas', $newName);
            $fileName = 'uploads/nota_dinas/' . $newName;
        }

        // Prepare Data for 'pengajuan_perubahan' table
        $data = [
            'user_id'          => session()->get('id') ?? 1, // Fallback to 1 if no session for dev
            'tahun'            => $this->request->getPost('tahun'),
            'bulan'            => $this->request->getPost('bulan'),
            'fungsi'           => $this->request->getPost('fungsi'),
            'no_iku'           => $this->request->getPost('no_iku'),
            'nama_indikator'   => $this->request->getPost('nama_indikator'),
            'no_indikator'     => $this->request->getPost('no_indikator'),
            'no_bulan'         => $this->request->getPost('no_bulan'),
            
            // New Detailed Revision Columns
            'target'           => $this->request->getPost('target'),
            'realisasi'        => $this->request->getPost('realisasi'),
            'perf_bulan'       => $this->request->getPost('perf_bulan'),
            'kat_bulan'        => $this->request->getPost('kat_bulan'),
            'perf_tahun'       => $this->request->getPost('perf_tahun'),
            'kat_tahun'        => $this->request->getPost('kat_tahun'),
            'cap_norm'         => $this->request->getPost('cap_norm'),
            'cap_norm_angka'   => $this->request->getPost('cap_norm_angka'),
            
            // Legacy / Summary
            'jenis_revisi'     => $this->request->getPost('jenis_revisi'), 
            'keterangan'       => $this->request->getPost('keterangan'),
            
            'file_nota_dinas'  => $fileName,
            'tgl_upload_nota'  => date('Y-m-d H:i:s'),
            'status'           => 'diajukan',
            'created_at'       => date('Y-m-d H:i:s')
        ];

        // Using Query Builder since we don't have a specific Model loaded for this table yet
        try {
            $db->transStart();
            
            // 1. Insert Proposed Changes (Table 1)
            $db->table('pengajuan_perubahan')->insert($data);
            $newId = $db->insertID();

            // 2. Capture Snapshot of Original Data (Table 2)
            $ikuRaw = str_replace('IKU ', '', $data['no_iku']);
            $val1 = $db->escape($ikuRaw);
            $val2 = $db->escape('IKU ' . $ikuRaw);
            $valIndikator = $db->escape($data['no_indikator']);

            $builder = $db->table('capaian_iku');
            $builder->where('Tahun', $data['tahun'])
                    ->where('Bulan', $data['bulan'])
                    ->where('Fungsi', $data['fungsi'])
                    // Strategy Use: Filter by Non-NULL Target instead of fragile No. Indikator
                    ->where("(`Target` IS NOT NULL AND `Target` != '-' AND `Target` != '')", null, false)
                    ->where("(`No. IKU` = $val1 OR `No. IKU` = $val2)", null, false);
            
            $query = $builder->get();

            if (!$query) {
                // Log DB Error if Query Fails
                $error = $db->error();
                throw new \RuntimeException("Query Capaian IKU Error: " . $error['message']);
            }

            $originalMaster = $query->getRowArray();

            if ($originalMaster) {
                $snapshot = [
                    'pengajuan_id'   => $newId,
                    'tahun'          => $originalMaster['Tahun'],
                    'bulan'          => $originalMaster['Bulan'],
                    'fungsi'         => $originalMaster['Fungsi'],
                    'no_iku'         => $originalMaster['No. IKU'],
                    'nama_indikator' => $originalMaster['Nama Indikator'],
                    'no_indikator'   => $originalMaster['No. Indikator'],
                    'no_bulan'       => $originalMaster['No. Bulan'],
                    'target'         => $originalMaster['Target'],
                    'realisasi'      => $originalMaster['Realisasi'],
                    'perf_bulan'     => $originalMaster['Performa % Capaian Bulan'],
                    'kat_bulan'      => $originalMaster['Kategori Capaian Bulan'],
                    'perf_tahun'     => $originalMaster['Performa % Capaian Tahun'],
                    'kat_tahun'      => $originalMaster['Kategori Capaian Tahun'],
                    'cap_norm'       => $originalMaster['Capaian Normalisasi'],
                    'cap_norm_angka' => $originalMaster['Capaian normalisasi Angka'],
                    'created_at'     => date('Y-m-d H:i:s')
                ];
                $db->table('pengajuan_perubahan_original')->insert($snapshot);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException("Transaction failed");
            }
            
            return redirect()->to('admin/pengajuan/submission')->with('message', 'Pengajuan Perubahan berhasil dikirim! Menunggu verifikasi.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }


    // 5. UPLOAD DISPOSISI (Step 1)
    public function upload_disposisi($id)
    {
        return $this->_handle_upload($id, 'file_disposisi', 'uploads/disposisi', 'disposisi', 'Bukti Disposisi berhasil diupload!');
    }

    // 6. UPLOAD SURAT ROREN (Step 2)
    public function upload_roren($id)
    {
        return $this->_handle_upload($id, 'file_surat_roren', 'uploads/surat_roren', 'proses_roren', 'Surat ke Roren berhasil diupload!');
    }

    // 7. UPLOAD E-PERFORMANCE (Step 3 - Final)
    public function upload_eperformance($id)
    {
        // 1. Perform Upload & Update Status
        $result = $this->_handle_upload($id, 'file_sc_eperformance', 'uploads/eperformance', 'selesai', 'Kasus Selesai! Data ePerformance telah diupdate.');
        
        // 2. If upload was successful (redirecting to detail with 'message'), Sync Data
        // Note: _handle_upload returns a RedirectResponse. We can't easy check boolean success without refactoring.
        // However, we can check if the file was updated in DB or simply run sync validly.
        // Better approach: Sync only if role is valid (checked in _handle_upload)
        
        if (session()->get('role') === 'perencana') {
            $db = \Config\Database::connect();
            $request = $db->table('pengajuan_perubahan')->where('id', $id)->get()->getRowArray();
            
            if ($request && $request['status'] == 'selesai') {
                $this->_sync_to_master($request);
            }
        }

        // If the upload was successful and status is 'selesai', we now fetch the original data
        // and display it for comparison.
        if (session()->get('role') === 'perencana' && $request && $request['status'] == 'selesai') {
            $db = \Config\Database::connect();
            
            // Manual query to avoid CI4 escaping issues with "No. IKU"
            $ikuRaw = str_replace('IKU ', '', $request['no_iku']);
            $val1 = $db->escape($ikuRaw);
            $val2 = $db->escape('IKU ' . $ikuRaw);

            $original = $db->table('capaian_iku')
                           ->where('Tahun', $request['tahun'])
                           ->where('Bulan', $request['bulan'])
                           ->where('Fungsi', $request['fungsi'])
                           // Fix: Remove No. Indikator and use Target IS NOT NULL
                           ->where("(`Target` IS NOT NULL AND `Target` != '-' AND `Target` != '')", null, false)
                           ->where("(`No. IKU` = $val1 OR `No. IKU` = $val2)", null, false) 
                           ->get()
                           ->getRowArray();
            
            $data = [
                'activeMenu' => 'perubahan_data',
                'title'   => 'Detail Validasi',
                'request' => $request,
                'original' => $original 
            ];

            return view('admin/pengajuan/validation_detail', $data);
        }

        return $result;
    }



    // Helper for file uploads
    private function _handle_upload($id, $fieldInput, $targetDir, $newStatus, $successMsg)
    {
        if (session()->get('role') !== 'perencana') {
            return redirect()->to('admin/pengajuan');
        }

        $file = $this->request->getFile($fieldInput);
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            if (!is_dir(FCPATH . $targetDir)) {
                mkdir(FCPATH . $targetDir, 0777, true);
            }
            $file->move(FCPATH . $targetDir, $newName);
            
            // Map timestamps based on field
            $timeField = 'tgl_upload_nota'; // fallback
            if($fieldInput == 'file_disposisi') $timeField = 'tgl_upload_disposisi';
            if($fieldInput == 'file_surat_roren') $timeField = 'tgl_upload_roren';
            if($fieldInput == 'file_sc_eperformance') $timeField = 'tgl_upload_eperformance';

            $db = \Config\Database::connect();
            $dataToUpdate = [
                $fieldInput => $targetDir . '/' . $newName,
                $timeField  => date('Y-m-d H:i:s'),
                'status'    => $newStatus,
                'updated_at'=> date('Y-m-d H:i:s')
            ];

            $db->table('pengajuan_perubahan')->where('id', $id)->update($dataToUpdate);
            
            return redirect()->to('admin/pengajuan/detail/' . $id)->with('message', $successMsg);
        }

        return redirect()->back()->with('error', 'Gagal mengupload file.');
    }


}
