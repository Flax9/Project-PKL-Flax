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
                        ->select('`No. IKU` as no_iku, `No. Indikator` as no_indikator', false)
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
            'list_iku'    => $list_iku,
            'list_nama_indikator' => $list_nama_indikator
        ];

        return view('admin/entry/index', $data);
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
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $db->transBegin();

        try {
            foreach ($dataArray as $row) {
                $db->query($sql, [
                    $row['fungsi'],
                    $row['no_indikator'],
                    $row['no_iku'],
                    $row['nama_indikator'],
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
}