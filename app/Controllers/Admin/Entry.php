<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

// Import Entry Models
use App\Models\Entry\IkuEntryModel;
use App\Models\Entry\NkoEntryModel;
use App\Models\Entry\AnggaranEntryModel;
use App\Models\Entry\CapaianOutputEntryModel;

class Entry extends BaseController
{
    protected $ikuModel;
    protected $nkoModel;
    protected $anggaranModel;
    protected $capaianModel;

    public function __construct()
    {
        // Initialize Models
        $this->ikuModel = new IkuEntryModel();
        $this->nkoModel = new NkoEntryModel();
        $this->anggaranModel = new AnggaranEntryModel();
        $this->capaianModel = new CapaianOutputEntryModel();
    }

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

        // Security Update: Support password hashing with plain-text fallback for transition
        $isValid = false;
        if ($user) {
            if (password_verify($password, $user->password)) {
                $isValid = true;
            } elseif ($password === $user->password) {
                // Fallback for plain text (WARNING: Security risk)
                $isValid = true;
                session()->setFlashdata('security_warning', 'Anda masih menggunakan password plain-text. Harap segera perbarui password Anda!');
            }
        }

        if ($isValid) {
            session()->set([
                'isLoggedIn' => true,
                'username'   => $user->username,
                'name'       => $user->name, // Store name in session
                'role'       => $user->role
            ]);
            session()->setFlashdata('access_granted', true);

            if ($user->role === 'perencana') {
                return redirect()->to('admin/entry/selection'); 
            } else {
                return redirect()->to('admin/entry/selection');
            }
        }
        return redirect()->back()->with('error', 'Username atau Password salah!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('admin/entry/verify');
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
        
        $data = [
            'activeMenu'  => 'data_entry',
            'title'       => 'Input Realisasi Rutin',
            'list_fungsi' => [],
            'list_iku'    => [],
            'list_nama_indikator' => []
        ];

        try {
            $data['list_fungsi'] = $this->ikuModel->getListFungsi();
            $data['list_iku']    = $this->ikuModel->getListIku();
            $data['list_nama_indikator'] = $this->ikuModel->getListNamaIndikator();
        } catch (\Throwable $e) {
            log_message('error', 'Entry::rutin data fetch error: ' . $e->getMessage());
            // Optionally pass error to view to alert user
            session()->setFlashdata('error', 'Gagal memuat data referensi: ' . $e->getMessage());
        }

        return view('admin/entry/index', $data);
    }

    public function profile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('admin/entry/verify');
        }

        // Fetch fresh user data from DB to ensure updates (photo, name, email) are visible
        $db = \Config\Database::connect();
        $username = session()->get('username');
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        // Inject 'name' into session if it's missing (Auto-fix for sidebar)
        if (!session()->has('name') && !empty($user['name'])) {
            session()->set('name', $user['name']);
        }

        $data = [
            'activeMenu' => 'profile',
            'title'      => 'Profil Pengguna',
            'user'       => $user
        ];

        return view('admin/profile', $data);
    }

    // --- DETEKSI IKU ---
    public function get_iku_by_tahun($tahun = null)
    {
        if (!$this->request->isAJAX() || empty($tahun)) {
            return $this->response->setStatusCode(404);
        }

        $result = $this->ikuModel->getIkuByTahun($tahun);
        
        if (isset($result['error'])) {
            return $this->response->setJSON($result)->setStatusCode(200);
        }
        return $this->response->setJSON($result);
    }

    // --- IKU OPERATIONS ---
    public function import_iku()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $file = $this->request->getFile('file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid']);
        }

        $result = $this->ikuModel->importData($file);
        return $this->response->setJSON($result);
    }

    public function simpan_iku_batch()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $json = $this->request->getPost('bulk_data');
        $data = json_decode($json, true);

        if (empty($data)) return $this->response->setJSON(['status' => 'error', 'message' => 'Data kosong']);

        $result = $this->ikuModel->saveBatchData($data);
        return $this->response->setJSON($result);
    }

    // --- NKO OPERATIONS ---
    public function import_nko()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $file = $this->request->getFile('file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File invalid']);
        }

        $result = $this->nkoModel->importData($file);
        return $this->response->setJSON($result);
    }

    public function simpan_nko_batch()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $json = $this->request->getPost('bulk_data');
        $data = json_decode($json, true);
        
        if (empty($data)) return $this->response->setJSON(['status' => 'error', 'message' => 'Data kosong']);

        $result = $this->nkoModel->saveBatchData($data);
        return $this->response->setJSON($result);
    }

    // --- ANGGARAN OPERATIONS ---
    public function import_anggaran()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $file = $this->request->getFile('file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File invalid']);
        }

        $result = $this->anggaranModel->importData($file);
        return $this->response->setJSON($result);
    }

    public function simpan_anggaran_batch()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $json = $this->request->getPost('bulk_data');
        $data = json_decode($json, true);
        
        if (empty($data)) return $this->response->setJSON(['status' => 'error', 'message' => 'Data kosong']);

        $result = $this->anggaranModel->saveBatchData($data);
        return $this->response->setJSON($result);
    }

    public function get_master_anggaran()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $data = $this->anggaranModel->getMasterAnggaran();
        return $this->response->setJSON($data);
    }

    // --- CAPAIAN OUTPUT OPERATIONS ---
    public function get_master_capaian_output()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $data = $this->capaianModel->getMasterCapaianOutput();
        return $this->response->setJSON($data);
    }

    public function import_capaian_output()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $file = $this->request->getFile('file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File tidak valid']);
        }

        $result = $this->capaianModel->importData($file);
        return $this->response->setJSON($result);
    }

    public function simpan_capaian_output_batch()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);
        $json = $this->request->getPost('bulk_data');
        $data = json_decode($json, true);

        if (empty($data)) return $this->response->setJSON(['status' => 'error', 'message' => 'Data kosong']);

        $result = $this->capaianModel->saveBatchData($data);
        return $this->response->setJSON($result);
    }

    // --- 3. PROFILE OPERATIONS ---

    public function upload_photo()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $validationRule = [
            'photo' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[photo]',
                    'is_image[photo]',
                    'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                    'max_size[photo,2048]',
                ],
            ],
        ];

        if (! $this->validate($validationRule)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => $this->validator->getErrors()['photo'] ?? 'Invalid file'
            ]);
        }

        $img = $this->request->getFile('photo');

        if (! $img->hasMoved()) {
            $username = session()->get('username');
            $newName = $username . '_' . time() . '.' . $img->getExtension();
            
            // Ensure directory exists
            if (!is_dir(FCPATH . 'uploads/profile')) {
                mkdir(FCPATH . 'uploads/profile', 0777, true);
            }

            $img->move(FCPATH . 'uploads/profile', $newName);

            // Update DB
            $db = \Config\Database::connect();
            $db->table('users')->where('username', $username)->update(['photo' => $newName]);

            return $this->response->setJSON([
                'status' => 'success',
                'photo' => base_url('uploads/profile/' . $newName)
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memindahkan file']);
    }

    public function update_profile()
    {
        $db = \Config\Database::connect();
        $username = session()->get('username');
        
        $name = $this->request->getPost('name');
        // Email update is now handled via OTP verification
        $password = $this->request->getPost('password');

        $data = [
            'name' => $name,
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $db->table('users')->where('username', $username)->update($data);

        // Update session so sidebar reflects changes immediately
        session()->set('name', $name);

        session()->setFlashdata('success', 'Profil berhasil diperbarui!');
        return redirect()->to('admin/profile');
    }

    // --- EMAIL VERIFICATION (OTP) ---

    public function request_verification()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $newEmail = $this->request->getPost('email');
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Format email tidak valid']);
        }

        // Check if email already used by another user
        $db = \Config\Database::connect();
        $exists = $db->table('users')->where('email', $newEmail)->countAllResults();
        if ($exists > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email sudah digunakan oleh pengguna lain']);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $username = session()->get('username');

        // Save to DB
        $db->table('users')->where('username', $username)->update([
            'temp_email' => $newEmail,
            'email_otp'  => $otp,
            'otp_created_at' => date('Y-m-d H:i:s')
        ]);

        // SIMULATION MODE: Return OTP in response because SMTP is not configured
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Kode OTP dikirim (Mode Simulasi)',
            'debug_otp' => $otp // Exposed for testing
        ]);
    }

    public function verify_otp()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(404);

        $otp = $this->request->getPost('otp');
        $username = session()->get('username');
        $db = \Config\Database::connect();
        
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        if (!$user || $user['email_otp'] !== $otp) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kode OTP salah']);
        }

        // Update Email & Clear OTP
        $db->table('users')->where('username', $username)->update([
            'email'      => $user['temp_email'],
            'temp_email' => null,
            'email_otp'  => null,
            'otp_created_at' => null
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Email berhasil diperbarui!']);
    }
}