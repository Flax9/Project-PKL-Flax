<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\capaianoutputmodel;

class CapaianOutput extends BaseController
{
    protected $outputModel;

    public function __construct()
    {
        $this->outputModel = new capaianoutputmodel();
    }

    public function index()
    {
        // =================================================================
        // 1. LOGIKA FILTER (Konsisten dengan Dashboard)
        // =================================================================
        $filter = [
            'tahun'         => $this->request->getGet('tahun'),
            'bulan'         => $this->request->getGet('bulan'),
            'fungsi'        => $this->request->getGet('fungsi'),
            'keterangan_ro' => $this->request->getGet('keterangan_ro'),
        ];

        // =================================================================
        // 2. QUERY UTAMA VIA MODEL (Data Capaian Output)
        // =================================================================

        // A. QUERY SCOREBOARD (5 Kotak Utama)
        $scoreboard = $this->outputModel->getScoreboard($filter);

        // B. QUERY GRAFIK BAR (Rasio Target vs Realisasi)
        $barData = $this->outputModel->getBarData($filter);

        // C. QUERY DONUT 1: Kategori Capaian (Membersihkan N/A dan #DIV/0!)
        $katCapaian = $this->outputModel->getKategoriCapaian($filter);

        // D. QUERY DONUT 2: Kategori Jenis Belanja (Membersihkan N/A dan #DIV/0!)
        $katBelanja = $this->outputModel->getKategoriBelanja($filter);

        // E. QUERY TREND (Trend Realisasi Program/Kegiatan)
        $trendData = $this->outputModel->getTrendData($filter);

        // F. QUERY RANKING (Peringkat Terendah & Tertinggi)
        $rankRendah = $this->outputModel->getRankingData($filter, 'ASC', 5);
        $rankTinggi = $this->outputModel->getRankingData($filter, 'DESC', 5);

        // =================================================================
        // 3. QUERY UNTUK DROPDOWN FILTER
        // =================================================================
        $filterBase = ['tahun' => $filter['tahun']];

        $filterBulan  = $this->outputModel->getFilterOptions('`No. Bulan`, `Bulan`', $filterBase);
        $filterTahun  = $this->outputModel->getFilterOptions('Tahun');
        $filterFungsi = $this->outputModel->getFilterOptions('Fungsi', $filterBase);
        $filterKetRo  = $this->outputModel->getFilterOptions('`Keterangan RO`, `No. RO`', $filterBase);

        // =================================================================
        // 4. PACKING DATA
        // =================================================================
        $data = [
            'activeMenu'       => 'capaian_output',
            'title'            => 'Capaian Output | BBPOM Surabaya',
            'filter_bulan'     => $filterBulan,
            'filter_tahun'     => $filterTahun,
            'filter_fungsi'    => $filterFungsi,
            'filter_indikator' => [],
            'filter_keterangan_ro' => $filterKetRo,
            'output_data'      => [
                'scoreboard'  => $scoreboard,
                'chart_bar'   => json_encode($barData),
                'chart_kat'   => json_encode($katCapaian),
                'chart_bel'   => json_encode($katBelanja),
                'chart_trend' => json_encode($trendData),
                'rank_low'    => json_encode($rankRendah),
                'rank_high'   => json_encode($rankTinggi),
            ]
        ];

        return view('capaian_output/index', $data);
    }
}