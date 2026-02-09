<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\IkuModel;

class Dashboard extends BaseController
{
    protected $ikuModel;

    public function __construct()
    {
        $this->ikuModel = new IkuModel();
    }

    public function index()
    {
        // =================================================================
        // 1. LOGIKA FILTER (SINKRONISASI VIEW & DATA)
        // =================================================================
        $filter = [
            'tahun'      => $this->request->getGet('tahun'),
            'bulan'      => $this->request->getGet('bulan'),
            'indikator'  => $this->request->getGet('nama_indikator'),
            'fungsi'     => $this->request->getGet('fungsi'),
        ];

        // Tentukan tahun untuk query NKO (default: tahun berjalan)
        $tahunQueryNko = $filter['tahun'] ?: date('Y');

        // =================================================================
        // 2. QUERY DATABASE VIA MODEL
        // =================================================================

        // A. SUMMARY CARDS
        $summary = $this->ikuModel->getSummary($filter);
        $nkoQuery = $this->ikuModel->getNkoAverage([
            'tahun' => $filter['tahun'],
            'bulan' => $filter['bulan']
        ]);

        // B. GRAFIK TREND (Line Chart)
        $trendData = $this->ikuModel->getTrendData($filter);

        // C. GRAFIK BAR (Target vs Realisasi)
        $barData = $this->ikuModel->getBarData($filter);

        // D. GRAFIK DONUT (Kategori)
        $kategoriBulan = $this->ikuModel->getKategoriData($filter, 'bulan');
        $kategoriTahun = $this->ikuModel->getKategoriData($filter, 'tahun');

        // E. GRAFIK RANKING (Top & Bottom 5)
        $rankRendah = $this->ikuModel->getRankingData($filter, 'ASC', 5);
        $rankTinggi = $this->ikuModel->getRankingData($filter, 'DESC', 5);

        // F. LIST TOP 5 (Tabel samping kanan)
        $topFiveList = $this->ikuModel->getTopFiveList($filter);

        // =================================================================
        // 3. QUERY DROPDOWN (MENGHILANGKAN OPSI BLANK)
        // =================================================================
        $filterBase = ['tahun' => $filter['tahun']]; // Base filter untuk dropdown

        $filterIndikator = $this->ikuModel->getFilterOptions('`Nama Indikator`', $filterBase);
        $filterBulan     = $this->ikuModel->getFilterOptions('`No. Bulan`, `Bulan`', $filterBase);
        $filterTahun     = $this->ikuModel->getFilterOptions('Tahun');
        $filterFungsi    = $this->ikuModel->getFilterOptions('Fungsi', $filterBase);

        // =================================================================
        // 4. PACKING DATA KE VIEW
        // =================================================================
        $data = [
            'activeMenu' => 'dashboard', 
            'title'      => 'Indikator Kinerja Utama | BBPOM Surabaya',
            
            // Flag pengecekan filter bulan untuk placeholder NKO
            'bulanDipilih' => !empty($filter['bulan']),
            
            'summary' => (object)[
                'avg_bulan' => $summary->avg_bulan ?? 0, 
                'avg_tahun' => $summary->avg_tahun ?? 0,
                'nko'       => $nkoQuery->total_nko ?? 0 
            ],
            
            'total_iku' => $this->ikuModel->getTotalIku($tahunQueryNko),

            'rank_high'             => json_encode($topFiveList),
            'grafik_bar'            => json_encode($barData),
            'grafik_kat_bulan'      => json_encode($kategoriBulan),
            'grafik_kat_tahun'      => json_encode($kategoriTahun),
            'grafik_trend_gabungan' => json_encode($trendData),
            'grafik_rendah'         => json_encode($rankRendah),
            'grafik_tinggi'         => json_encode($rankTinggi),
            'filterIndikator'       => $filterIndikator,
            'filter_bulan'          => $filterBulan,
            'filter_tahun'          => $filterTahun,
            'filter_fungsi'         => $filterFungsi,
        ];

        return view('dashboard/index', $data);
    }
}