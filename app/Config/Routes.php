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

//routes untuk fungsi no. iku di data entry
$routes->get('admin/entry/get_all_no_iku', 'Admin\Entry::get_all_no_iku');


//untu data entry
// app/Config/Routes.php

$routes->group('admin', function($routes) {
    
    /** * ALUR VERIFIKASI (GATEWAY)
     * Mengarahkan user ke pintu masuk sebelum melihat form
     */
    $routes->get('entry/verify', 'Admin\Entry::verify');      // Tampilan input password
    $routes->post('entry/check-auth', 'Admin\Entry::checkAuth'); // Proses validasi password

    /**
     * PENAMBAHAN BARU: GATEWAY PEMILIHAN JALUR
     * Halaman transisi (Card) setelah login berhasil
     */
    $routes->get('entry/selection', 'Admin\Entry::selection');

    /**
     * PENAMBAHAN BARU: PEMISAHAN JALUR OPERASIONAL
     * Mengarahkan ke form sesuai kebutuhan (Rutin atau Modifikasi)
     */
    $routes->get('entry/rutin', 'Admin\Entry::rutin');      // Form untuk data baru
    $routes->get('entry/modifikasi', 'Admin\Entry::modifikasi'); // Form untuk revisi data

    /**
     * HALAMAN UTAMA & OPERASIONAL (DIPROTEKSI)
     * Sebaiknya nanti menggunakan Filters CI4, namun secara route tetap seperti ini:
     */
    
    // Menampilkan halaman utama Data Entry (Index)
    $routes->get('entry', 'Admin\Entry::index');
    
    // Endpoint AJAX untuk mengambil data Master berdasarkan tahun
    // Kita buat lebih general: get_master/(:segment)/(:num) 
    // agar bisa dipakai untuk master IKU, Anggaran, dll di masa depan.
    $routes->match(['get','post'], 'entry/get_master/(:any)', 'Admin\Entry::getMasterData/$1');
    
    //routes untuk auto-fill nama indikator berdasarkan no. IKU
    $routes->get('/entry/get_master_iku/(:any)', 'Admin\Entry::get_master_iku/$1');

    // Endpoint AJAX untuk mendapatkan detail IKU (Nama Indikator)
    // Param 1: Tahun, Param 2: No_IKU
    $routes->get('entry/get_detail_iku/(:num)/(:num)', 'Admin\Entry::getDetailIku/$1/$2');
    
    // Auto-Fill Nama Indikator (AJAX - GET/POST)
    // Define under the 'admin' group so URL becomes '/admin/entry/get_detail_iku'
    $routes->match(['get','post'], 'entry/get_detail_iku', 'Admin\Entry::get_detail_iku');

    // Proses Simpan Massal (Batch Insert) dari Staging Area ke DB
    $routes->post('entry/simpan_iku_batch', 'Admin\Entry::simpan_iku_batch');

    // Import Excel/CSV untuk IKU
    $routes->post('entry/import_iku', 'Admin\Entry::import_iku');

    // NKO Routes
    $routes->post('entry/import_nko', 'Admin\Entry::import_nko');
    $routes->post('entry/simpan_nko_batch', 'Admin\Entry::simpan_nko_batch');

    // Anggaran Routes
    $routes->post('entry/import_anggaran', 'Admin\Entry::import_anggaran');
    $routes->post('entry/simpan_anggaran_batch', 'Admin\Entry::simpan_anggaran_batch');
    $routes->get('entry/get_master_anggaran', 'Admin\Entry::get_master_anggaran');

    // Capaian Output Routes
    $routes->post('entry/import_capaian_output', 'Admin\Entry::import_capaian_output');
    $routes->post('entry/simpan_capaian_output_batch', 'Admin\Entry::simpan_capaian_output_batch');
    $routes->get('entry/get_master_capaian_output', 'Admin\Entry::get_master_capaian_output');

    //routes deteksi iku by select tahun
   $routes->get(
    'entry/get_iku_by_tahun/(:segment)',
    'Admin\Entry::get_iku_by_tahun/$1'
    );

    // ROUTES PENGAJUAN PERUBAHAN DATA
    $routes->get('pengajuan', 'Admin\Pengajuan::index');
    $routes->get('pengajuan/submission', 'Admin\Pengajuan::submission');
    $routes->get('pengajuan/check_data', 'Admin\Pengajuan::check_data'); // <-- ADDED THIS
    $routes->get('pengajuan/check_validity', 'Admin\Pengajuan::check_validity');
    $routes->post('pengajuan/store', 'Admin\Pengajuan::store');
    
    // Validation (Planner) Routes
    $routes->get('pengajuan/validation', 'Admin\Pengajuan::validation');
    $routes->get('pengajuan/detail/(:num)', 'Admin\Pengajuan::detail/$1');
    
    // Upload Actions (Planner)
    $routes->post('pengajuan/upload_disposisi/(:num)', 'Admin\Pengajuan::upload_disposisi/$1');
    $routes->post('pengajuan/upload_roren/(:num)', 'Admin\Pengajuan::upload_roren/$1');
    $routes->post('pengajuan/upload_eperformance/(:num)', 'Admin\Pengajuan::upload_eperformance/$1');

    // Profile Route
    $routes->get('profile', 'Admin\Entry::profile');
    $routes->post('entry/upload-photo', 'Admin\Entry::upload_photo');
    $routes->post('entry/update-profile', 'Admin\Entry::update_profile');
    
    // Email Verification Routes
    $routes->post('entry/request-verification', 'Admin\Entry::request_verification');
    $routes->post('entry/verify-otp', 'Admin\Entry::verify_otp');

    // Telegram Test Route
    $routes->get('testtelegram', 'Admin\TestTelegram::index');

    // Telegram Webhook Route (Public)
    $routes->post('telegram/webhook', 'TelegramWebhook::index');

    // fallback route untuk method lain di Entry
    $routes->get('entry/(:any)', 'Admin\Entry::$1');
});