<?php

namespace App\Controllers;

use App\Models\AnggaranModel;

class Anggaran extends BaseController
{
    protected $anggaranModel;

    public function __construct()
    {
        // Inisialisasi Model Anggaran
        $this->anggaranModel = new AnggaranModel();
    }

    public function index()
    {
        // 1. Tangkap semua input filter dari URL (GET)
        $filter = [
            'tahun'   => $this->request->getGet('tahun'),
            'bulan'   => $this->request->getGet('bulan'),
            'program' => $this->request->getGet('program'),
            'ro'      => $this->request->getGet('ro'),
        ];


        // 2. Tentukan Tahun Aktif untuk Grafik Tren
        // Jika filter tahun kosong, gunakan tahun berjalan (2026) sebagai default
        $tahunAktif = $filter['tahun'] ?: date('Y');

        // 3. Ambil Data dari Model dengan parameter filter
        $scoreboard = $this->anggaranModel->getScoreboard($filter);
        $chartBar   = $this->anggaranModel->getChartProgram($filter);
        
        // Khusus Tren, kita kirimkan tahunAktif dan filter tambahan (program/ro)
        $trendData  = $this->anggaranModel->getMonthlyTrend($filter['tahun'], $filter);

        // 4. Siapkan Opsi Dropdown (Mengambil data unik dari Database)
        // Menggunakan backtick pada `RO` dan `PROGRAM/KEGIATAN` untuk mencegah Error #1064
        $data = [
            'activeMenu'    => 'anggaran',
            'title'         => 'Anggaran | BBPOM Surabaya',
            'tahun_label'   => $filter['tahun'] ?: 'Semua Tahun',
            
            // Mengambil list unik untuk isi dropdown di View
            'filter_tahun'   => $this->anggaranModel->getFilterOptions('Tahun'),
            // PERBAIKAN: Mengurutkan bulan secara kronologis untuk dropdown
            'filter_bulan'   => $this->anggaranModel->getFilterBulan(),
            'filter_program' => $this->anggaranModel->getFilterOptions('`PROGRAM/KEGIATAN`'),
            'filter_ro'      => $this->anggaranModel->getFilterOptions('`RO`'),
            
            'summary'    => $scoreboard, 
            'filter'     => $filter,

            
            
            // Kirim balik variabel filter agar dropdown tetap 'selected' setelah di-refresh
            'filter' => $filter,
            
            'anggaran_data' => [
                'scoreboard'  => $scoreboard,
                'chart_bar'   => json_encode($chartBar),
                'chart_trend' => json_encode($trendData)
            ]
        ];

        // 5. Load View dengan membawa data yang sudah diproses
        return view('anggaran/index', $data);
    }
}