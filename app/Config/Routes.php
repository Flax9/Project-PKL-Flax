<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Halaman utama (root) mau diarahkan ke mana? 
// Jika ingin localhost:8081 langsung buka dashboard:
$routes->get('/', 'Dashboard::index'); 

// 2. Route untuk Dashboard
$routes->get('/dashboard', 'Dashboard::index');
#$routes->get('/dashboard/index', 'Dashboard::index'); // Ubah dari ::iku ke ::index

// 3. Route lainnya (Pastikan method ini ada di Controller Dashboard.php)
#$routes->get('/dashboard/ro', 'Dashboard::ro');
$routes->get('/dashboard/database', 'Dashboard::database');
#$routes->get('/ro', 'Ro::index'); // Baris baru untuk Rincian Output
$routes->get('capaianoutput', 'CapaianOutput::index');
$routes->get('anggaran', 'Anggaran::index');

//untu data entry
// app/Config/Routes.php

$routes->group('admin', function($routes) {
    
    /** * ALUR VERIFIKASI (GATEWAY)
     * Mengarahkan user ke pintu masuk sebelum melihat form
     */
    $routes->get('entry/verify', 'Admin\Entry::verify');      // Tampilan input password
    $routes->post('entry/check-auth', 'Admin\Entry::checkAuth'); // Proses validasi password

    /**
     * HALAMAN UTAMA & OPERASIONAL (DIPROTEKSI)
     * Sebaiknya nanti menggunakan Filters CI4, namun secara route tetap seperti ini:
     */
    
    // Menampilkan halaman utama Data Entry (Index)
    $routes->get('entry', 'Admin\Entry::index');
    
    // Endpoint AJAX untuk mengambil data Master berdasarkan tahun
    // Kita buat lebih general: get_master/(:segment)/(:num) 
    // agar bisa dipakai untuk master IKU, Anggaran, dll di masa depan.
    $routes->get('entry/get_master/(:num)', 'Admin\Entry::getMasterData/$1');
    
    // Endpoint AJAX untuk mendapatkan detail IKU (Nama Indikator)
    // Param 1: Tahun, Param 2: No_IKU
    $routes->get('entry/get_detail_iku/(:num)/(:num)', 'Admin\Entry::getDetailIku/$1/$2');
    
    // Proses Simpan Massal (Batch Insert) dari Staging Area ke DB
    $routes->post('entry/save_iku', 'Admin\Entry::saveIku');
});