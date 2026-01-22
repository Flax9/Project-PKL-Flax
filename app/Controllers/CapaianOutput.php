<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CapaianOutput extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // =================================================================
        // 1. LOGIKA FILTER (Konsisten dengan Dashboard)
        // =================================================================
        $filterTahunURL = $this->request->getGet('tahun');
        $bulanAktif     = $this->request->getGet('bulan');
        $fungsiAktif    = $this->request->getGet('fungsi');
        $ketRoAktif     = $this->request->getGet('keterangan_ro');

        if (empty($filterTahunURL)) {
            $whereBase = " WHERE 1=1 ";
        } else {
            $tahunSafe = $db->escapeString($filterTahunURL);
            $whereBase = " WHERE Tahun = '$tahunSafe' ";
        }

        $whereDetail = $whereBase;

        if ($bulanAktif) {
            $bulanSafe = $db->escapeString($bulanAktif);
            $whereDetail .= " AND Bulan = '$bulanSafe'";
        }

        if ($fungsiAktif) {
            $fungsiSafe = $db->escapeString($fungsiAktif);
            $whereDetail .= " AND Fungsi = '$fungsiSafe'";
        }

        // INI KUNCI PERBAIKANNYA: Tambahkan filter Keterangan RO ke $whereDetail
        if ($ketRoAktif) {
            $whereDetail .= " AND `Keterangan RO` = '" . $db->escapeString($ketRoAktif) . "'";
        }

        // =================================================================
        // 2. QUERY UTAMA (Data Capaian Output)
        // =================================================================

        // A. QUERY SCOREBOARD (5 Kotak Utama)
        $scoreboard = $db->query("
            SELECT 
                MAX(`Target tahun`) as total_target, 
                SUM(`Realisasi`) as total_realisasi,
                MAX(`Realisasi Kumulatif`) as realisasi_kumulatif,
                MAX(`Realisasi Kumulatif %`) as persen_realisasi_kumulatif,
                (SELECT ROUND(AVG(sub.max_capaian), 2) 
                 FROM (
                    SELECT MAX(Capaian) as max_capaian 
                    FROM capaian_output 
                    $whereDetail AND Capaian > 0 
                    GROUP BY `No. RO`
                 ) as sub
                ) as indeks_capaian
            FROM capaian_output $whereDetail
        ")->getRow();

        // B. QUERY GRAFIK BAR (Rasio Target vs Realisasi)
        $barData = $db->query("
            SELECT 
                `No. RO` as no,
                MAX(`Target tahun`) as target, 
                MAX(`Realisasi`) as realisasi 
            FROM capaian_output $whereDetail 
            GROUP BY `No. RO`
            ORDER BY CAST(`No. RO` AS UNSIGNED) ASC
        ")->getResultArray();

        // C. QUERY DONUT 1: Kategori Capaian (Membersihkan N/A dan #DIV/0!)
        $katCapaian = $db->query("
            SELECT `Kategori` as label, COUNT(*) as jumlah 
            FROM capaian_output 
            $whereDetail 
            AND `Kategori` NOT IN ('N/A', '#DIV/0!', '', '-') 
            AND `Kategori` IS NOT NULL
            GROUP BY `Kategori`
            ORDER BY jumlah DESC
        ")->getResultArray();

        // D. QUERY DONUT 2: Kategori Jenis Belanja (Membersihkan N/A dan #DIV/0!)
        $katBelanja = $db->query("
            SELECT `Kategori Belanja` as label, COUNT(*) as jumlah 
            FROM capaian_output 
            $whereDetail 
            AND `Kategori Belanja` NOT IN ('N/A', '#DIV/0!', '', '-') 
            AND `Kategori Belanja` IS NOT NULL
            GROUP BY `Kategori Belanja`
            ORDER BY jumlah DESC
        ")->getResultArray();

        // E. QUERY TREND (Trend Realisasi Program/Kegiatan)
        $trendData = $db->query("
            SELECT 
                Bulan, `No. Bulan`,
                SUM(`Realisasi Kumulatif`) as kumulatif 
            FROM capaian_output $whereDetail 
            GROUP BY Bulan, `No. Bulan` 
            ORDER BY `No. Bulan` ASC
        ")->getResultArray();

        // F. QUERY RANKING (Peringkat Terendah & Tertinggi)
        $rankRendah = $db->query("
            SELECT `No. RO` as no, MAX(`Realisasi Kumulatif %`) as nilai 
            FROM capaian_output $whereDetail 
            GROUP BY `No. RO`
            ORDER BY nilai ASC LIMIT 5
        ")->getResultArray();

        $rankTinggi = $db->query("
            SELECT `No. RO` as no, MAX(`Realisasi Kumulatif %`) as nilai 
            FROM capaian_output $whereDetail 
            GROUP BY `No. RO`
            ORDER BY nilai DESC LIMIT 5
        ")->getResultArray();

        // =================================================================
        // 3. QUERY UNTUK DROPDOWN FILTER
        // =================================================================
        $tableSource = 'capaian_output';

        $filterBulan  = $db->query("SELECT DISTINCT Bulan, `No. Bulan` FROM $tableSource $whereBase ORDER BY `No. Bulan` ASC")->getResultArray();
        $filterTahun  = $db->query("SELECT DISTINCT Tahun FROM $tableSource WHERE Tahun IS NOT NULL AND Tahun != '' ORDER BY Tahun DESC")->getResultArray();
        $filterFungsi = $db->query("SELECT DISTINCT Fungsi FROM $tableSource $whereBase ORDER BY Fungsi ASC")->getResultArray();

        // Gunakan HANYA yang ini agar urutan sesuai No. RO (Numerik)
        $filterKetRo = $db->query("
            SELECT DISTINCT `Keterangan RO` as keterangan, `No. RO` 
            FROM $tableSource 
            $whereBase 
            AND `Keterangan RO` NOT IN ('N/A', '', '-')
            ORDER BY CAST(`No. RO` AS UNSIGNED) ASC
        ")->getResultArray();

        // --- BARIS DI BAWAH INI HARUS DIHAPUS AGAR TIDAK MENIMPA DATA DI ATAS ---
        // $filterKetRo = $db->query("SELECT DISTINCT `Keterangan RO` as keterangan FROM $tableSource $whereBase ORDER BY keterangan ASC")->getResultArray();
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