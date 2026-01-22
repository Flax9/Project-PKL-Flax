<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // =================================================================
        // 1. LOGIKA FILTER (SINKRONISASI VIEW & DATA)
        // =================================================================
        $filterTahunURL = $this->request->getGet('tahun');
        $bulanAktif     = $this->request->getGet('bulan');
        $indikatorAktif = $this->request->getGet('nama_indikator');
        $fungsiAktif    = $this->request->getGet('fungsi');

        // Logic: Jika URL Tahun Kosong (Pilih "Tahun"), Ambil SEMUA data (WHERE 1=1)
        if (empty($filterTahunURL)) {
            $whereBase = " WHERE 1=1 "; 
            $tahunQueryNko = date('Y'); 
        } else {
            $whereBase = " WHERE Tahun = '$filterTahunURL' ";
            $tahunQueryNko = $filterTahunURL;
        }

        // Susun Filter Dasar untuk NKO (Tahun & Bulan)
        $whereNko = " WHERE 1=1 ";
        if (!empty($filterTahunURL)) {
            $whereNko .= " AND Tahun = '$filterTahunURL' ";
        }
        if (!empty($bulanAktif)) {
            $whereNko .= " AND Bulan = '$bulanAktif' ";
        }

        // Susun Filter Detail untuk IKU (Tahun, Bulan, Indikator, Fungsi)
        $whereDetail = $whereBase;
        
        if ($bulanAktif) {
            $whereDetail .= " AND Bulan = '$bulanAktif'";
        }
        
        if ($indikatorAktif) {
            $indikatorSafe = $db->escapeString($indikatorAktif);
            $whereDetail .= " AND `Nama Indikator` = '$indikatorSafe'";
        }

        if ($fungsiAktif) {
            $fungsiSafe = $db->escapeString($fungsiAktif);
            $whereDetail .= " AND Fungsi = '$fungsiSafe'";
        }

        // =================================================================
        // 2. QUERY DATABASE REAL (MENGGANTIKAN MOCK DATA)
        // =================================================================

        // A. SUMMARY CARDS
        $summary = $db->query("
            SELECT 
                ROUND(AVG(`Performa % Capaian Bulan`), 2) as avg_bulan, 
                ROUND(AVG(`Performa % Capaian Tahun`), 2) as avg_tahun 
            FROM capaian_iku $whereDetail
        ")->getRow();

        // Query NKO: Menggunakan variabel $whereNko yang sudah mendukung filter bulan
        $nkoQuery = $db->query("
            SELECT ROUND(AVG(`NKO`), 2) as total_nko 
            FROM `nko` 
            $whereNko
        ")->getRow();

        // B. GRAFIK TREND (Line Chart)
        $trendData = $db->query("
            SELECT 
                Bulan, 
                ROUND(AVG(`Performa % Capaian Bulan`), 2) as rata_bulan, 
                ROUND(AVG(`Performa % Capaian Tahun`), 2) as rata_tahun 
            FROM capaian_iku 
            $whereBase 
            GROUP BY Bulan, `No. Bulan` 
            ORDER BY `No. Bulan` ASC
        ")->getResultArray();

        // C. GRAFIK BAR (Target vs Realisasi)
        $barData = $db->query("
            SELECT 
                `No. IKU` as no, 
                AVG(Target) as target, 
                AVG(Realisasi) as realisasi 
            FROM capaian_iku 
            $whereDetail 
            GROUP BY `No. IKU` 
            ORDER BY CAST(`No. IKU` AS UNSIGNED) ASC
        ")->getResultArray();

        // D. GRAFIK DONUT (Kategori)
        $kategoriBulan = $db->query("
            SELECT `Kategori Capaian Bulan` as label, COUNT(*) as jumlah 
            FROM capaian_iku $whereDetail 
            GROUP BY `Kategori Capaian Bulan`
        ")->getResultArray();

        $kategoriTahun = $db->query("
            SELECT `Kategori Capaian Tahun` as label, COUNT(*) as jumlah 
            FROM capaian_iku $whereDetail 
            GROUP BY `Kategori Capaian Tahun`
        ")->getResultArray();

        // E. GRAFIK RANKING (Top & Bottom 5)
        $rankRendah = $db->query("
            SELECT `No. IKU` as no, AVG(`Performa % Capaian Tahun`) as nilai 
            FROM capaian_iku $whereDetail 
            GROUP BY `No. IKU` 
            ORDER BY nilai ASC 
            LIMIT 5
        ")->getResultArray();

        $rankTinggi = $db->query("
            SELECT `No. IKU` as no, AVG(`Performa % Capaian Tahun`) as nilai 
            FROM capaian_iku $whereDetail 
            GROUP BY `No. IKU` 
            ORDER BY nilai DESC 
            LIMIT 5
        ")->getResultArray();

        // F. LIST TOP 5 (Tabel samping kanan)
        $topFiveList = $db->query("
            SELECT `Nama Indikator` as nama, `Performa % Capaian Tahun` as nilai 
            FROM capaian_iku $whereDetail 
            ORDER BY nilai DESC 
            LIMIT 5
        ")->getResultArray();

        // =================================================================
        // 3. QUERY DROPDOWN (MENGHILANGKAN OPSI BLANK)
        // =================================================================
        
        $filterIndikator = $db->query("SELECT DISTINCT `Nama Indikator` as nama FROM capaian_iku $whereBase ORDER BY 'No. Indikator' ASC")->getResultArray();
        $filterBulan = $db->query("SELECT DISTINCT Bulan, `No. Bulan` FROM capaian_iku $whereBase ORDER BY `No. Bulan` ASC")->getResultArray();
        $filterTahun = $db->query("SELECT DISTINCT Tahun FROM capaian_iku WHERE Tahun IS NOT NULL AND Tahun != '' ORDER BY Tahun DESC")->getResultArray();
        $filterFungsi = $db->query("SELECT DISTINCT Fungsi FROM capaian_iku $whereBase ORDER BY Fungsi ASC")->getResultArray();

        // =================================================================
        // 4. PACKING DATA KE VIEW
        // =================================================================
        $data = [
            'activeMenu' => 'dashboard', 
            'title'      => 'Indikator Kinerja Utama | BBPOM Surabaya',
            
            // Flag pengecekan filter bulan untuk placeholder NKO
            'bulanDipilih' => !empty($bulanAktif),
            
            'summary' => (object)[
                'avg_bulan' => $summary->avg_bulan ?? 0, 
                'avg_tahun' => $summary->avg_tahun ?? 0,
                'nko'       => $nkoQuery->total_nko ?? 0 
            ],
            
            'total_iku' => $db->query("
                SELECT COUNT(DISTINCT `No. IKU`) as total 
                FROM capaian_iku 
                WHERE Tahun <= '$tahunQueryNko'
            ")->getRow()->total,

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