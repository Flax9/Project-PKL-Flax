<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Entry extends BaseController
{
    // Menampilkan halaman verifikasi (Gateway)
    public function verify()
    {
        #if (session()->get('is_admin_entry')) {
            #return redirect()->to('admin/entry');
        #}
        return view('admin/entry/verify');
    }


    // Mengecek password admin
    public function checkAuth()
    {
        $authCode = $this->request->getPost('auth_code');
        if ($authCode === 'ADMIN123') { // Ganti password sesuai keinginan
            #session()->set('is_admin_entry', true);
            session()->setFlashdata('access_granted', true);
            return redirect()->to('admin/entry');
        }
        return redirect()->back()->with('error', 'Kode Otorisasi Tidak Valid!');
    }

    // Menampilkan halaman utama Data Entry (Tabel IKU)
    public function index()
    {
        /*if (!session()->get('is_admin_entry')) {
            return redirect()->to('admin/entry/verify');
        }*/
        if (!session()->getFlashdata('access_granted')) {
            return redirect()->to('admin/entry/verify');
        }

        // Definisikan activeMenu di sini agar sidebar tidak error
    $data = [
        'activeMenu' => 'data_entry' 
    ];

        return view('admin/entry/index', $data);
    }
}