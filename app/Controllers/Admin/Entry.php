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
            'list_fungsi' => $list_fungsi,
            'list_iku'    => $list_iku,
            'list_nama_indikator' => $list_nama_indikator
        ];

        return view('admin/entry/index', $data);
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