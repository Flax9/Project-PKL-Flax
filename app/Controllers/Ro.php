<?php

namespace App\Controllers;

class Ro extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Ambil Filter dari URL
        $tahunAktif   = $this->request->getGet('tahun') ?: '2025';
        $bulanAktif   = $this->request->getGet('bulan');
        $fungsiAktif  = $this->request->getGet('fungsi');
        $roAktif      = $this->request->getGet('keterangan_ro');

        // 2. Inisialisasi Query Builder dengan nama tabel yang benar
        $builder = $db->table('capaian_output'); 
        $builder->where('Tahun', $tahunAktif);

        if ($bulanAktif)  $builder->where('Bulan', $bulanAktif);
        if ($fungsiAktif) $builder->where('Fungsi', $fungsiAktif);
        if ($roAktif)     $builder->where('Keterangan RO', $roAktif);

        // Kloning builder untuk berbagai perhitungan scoreboard
        $b1 = clone $builder;
        $b2 = clone $builder;
        $b3 = clone $builder;
        $b4 = clone $builder;

        // 3. Kalkulasi Data Scoreboard
        $target    = $b1->selectMax('Target tahun', 'total')->get()->getRow()->total ?? 0;
        $realisasi = $b2->selectSum('Realisasi', 'total')->get()->getRow()->total ?? 0;
        $kumulatif = $b3->selectMax('Realisasi Kumulatif', 'total')->get()->getRow()->total ?? 0;
        
        $persen_kumulatif = ($target > 0) ? ($kumulatif / $target) * 100 : 0;
        $indeks_capaian   = $b4->selectAvg('Capaian', 'total')->get()->getRow()->total ?? 0;

        // 4. Susun Data untuk dikirim ke View
        $data = [
            'activeMenu' => 'ro',
            'title'      => 'Rincian Output (RO) | BBPOM Surabaya',
            'summary'    => (object) [
                'total_target'     => $target,
                'total_realisasi'  => $realisasi,
                'total_kumulatif'  => $kumulatif,
                'persen_kumulatif' => $persen_kumulatif,
                'indeks_capaian'   => $indeks_capaian
            ],
            // Data Grafik (Gunakan tabel capaian_output)
            'grafik_belanja' => $db->table('capaian_output')->select('Kategori Belanja as label, COUNT(*) as value')->where('Tahun', $tahunAktif)->groupBy('Kategori Belanja')->get()->getResultArray(),
            
            // Variabel Filter untuk Header (Gunakan tabel capaian_output)
            'filter_ro'      => $db->table('capaian_output')->distinct()->select('Keterangan RO')->where('Tahun', $tahunAktif)->get()->getResultArray(),
            'filter_fungsi'  => $db->table('capaian_output')->distinct()->select('Fungsi')->where('Tahun', $tahunAktif)->get()->getResultArray(),
            'filter_bulan'   => $db->table('capaian_output')->distinct()->select('Bulan, No. Bulan')->orderBy('No. Bulan', 'ASC')->get()->getResultArray(),
        ];

        return view('ro/index', $data);
    }
}