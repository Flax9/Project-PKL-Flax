<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\IkuModel;
use App\Libraries\TelegramService;

class Pengajuan extends BaseController
{
    protected $pengajuanModel;
    protected $ikuModel;

    public function __construct()
    {
        $this->pengajuanModel = new PengajuanModel();
        $this->ikuModel = new IkuModel();
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

        // Fetch SNAPSHOT Data (Nilai Semula)
        $original = $this->pengajuanModel->getSnapshot($id);
        
        $data = [
            'activeMenu' => 'perubahan_data',
            'title'   => 'Detail Validasi',
            'request' => $request,
            'original' => $original 
        ];

        return view('admin/pengajuan/validation_detail', $data);
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
            // Use generic Model method to find candidates
            $rows = $this->pengajuanModel->findIkuCandidates($tahun, $bulan, $fungsi, $no_iku);

    
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
                return $this->response->setJSON([
                    'status' => 'not_found',
                    'debug'  => "Data tidak ditemukan di tabel capaian_iku.",
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
        $req = $this->request;

        try {
            // Use Model to check validity
            // We pass the whole GET array, the model handles mapping
            $isValid = $this->pengajuanModel->checkValidity($req->getGet());

            if ($isValid) {
                 return $this->response->setJSON(['status' => 'valid', 'message' => 'Data Valid! Kombinasi ditemukan di Database.']);
            } else {
                 return $this->response->setJSON(['status' => 'invalid', 'message' => 'Data Tidak Valid / Tidak Ditemukan Kombinasi Ini.']);
            }
        } catch (\Throwable $e) {
             return $this->response->setJSON([
                 'status' => 'error', 
                 'message' => $e->getMessage()
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

        // Using Model to create submission (Transaction & Snapshot handled in Model)
        try {
            if ($this->pengajuanModel->createSubmission($data)) {
                // --- NOTIFIKASI TELEGRAM ---
                $telegram = new TelegramService();
                $adminId = env('TELEGRAM_ADMIN_CHAT_ID');
                if ($adminId) {
                    $msg = "🔔 <b>Pengajuan Baru!</b>\n\n";
                    $msg .= "ID: #{$db->insertID()}\n";
                    $msg .= "IKU: {$data['no_iku']} - {$data['nama_indikator']}\n";
                    $msg .= "Fungsi: {$data['fungsi']}\n";
                    $msg .= "Oleh: " . (session()->get('username') ?? 'User');
                    
                    $telegram->sendMessage($adminId, $msg);
                }
                // ---------------------------

                return redirect()->to('admin/pengajuan/submission')->with('message', 'Pengajuan Perubahan berhasil dikirim! Menunggu verifikasi.');
            } else {
                throw new \Exception("Gagal menyimpan transaksi.");
            }
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
            $request = $this->pengajuanModel->find($id);
            
            if ($request && $request['status'] == 'selesai') {
                $this->pengajuanModel->syncToMaster($id);
            }
        }

        // If the upload was successful and status is 'selesai', we now fetch the original data
        // and display it for comparison.
        if (session()->get('role') === 'perencana' && $request && $request['status'] == 'selesai') {
            // Fetch SNAPSHOT Data for consistent view
            $original = $this->pengajuanModel->getSnapshot($id);
            
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

        if ($this->pengajuanModel->handleUpload($id, $fieldInput, $targetDir, $newStatus)) {
             return redirect()->to('admin/pengajuan/detail/' . $id)->with('message', $successMsg);
        }

        return redirect()->back()->with('error', 'Gagal mengupload file.');
    }


}
