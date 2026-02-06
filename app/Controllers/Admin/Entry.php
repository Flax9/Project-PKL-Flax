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
            'list_fungsi' => $this->ikuModel->getListFungsi(),
            'list_iku'    => $this->ikuModel->getListIku(),
            'list_nama_indikator' => $this->ikuModel->getListNamaIndikator()
        ];

        return view('admin/entry/index', $data);
    }

    public function profile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('admin/entry/verify');
        }

        $data = [
            'activeMenu' => 'profile',
            'title'      => 'Profil Pengguna',
            'user'       => [
                'username' => session()->get('username'),
                'role'     => session()->get('role')
            ]
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
}