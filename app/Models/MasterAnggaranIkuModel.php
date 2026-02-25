<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterAnggaranIkuModel extends Model
{
    protected $table = 'master_anggaran_iku';
    protected $primaryKey = 'id';
    
    // Sesuaikan allowed fields jika ada penambahan/pengurangan
    protected $allowedFields = [
        'no_indikator_kinerja',
        'nama_indikator_kinerja',
        'sasaran_kegiatan'
    ];

    /**
     * Mengambil data master digabungkan (LEFT JOIN) dengan data transaksinya.
     * Menggunakan LEFT JOIN agar master IKU tetap muncul meskipun belum ada transaksi.
     */
    public function getCombinedData($filter = [])
    {
        $builder = $this->db->table($this->table . ' m');
        
        // Memilih kolom spesifik yang akan ditampilkan di view
        $builder->select('
            t.id as id_transaksi,
            m.no_indikator_kinerja as no_indikator,
            m.nama_indikator_kinerja,
            m.sasaran_kegiatan,
            t.bulan,
            t.tahun,
            t.target,
            t.realisasi as realisasi_kinerja,
            t.persen_kinerja,
            t.pagu_anggaran,
            t.realisasi_anggaran,
            t.persen_anggaran
        ');

        // Lakukan JOIN berdasarkan nomor indikator
        $builder->join('transaksi_anggaran_iku t', 'm.no_indikator_kinerja = t.no_indikator_kinerja', 'left');

        // Terapkan Filter
        if (!empty($filter['tahun'])) {
            $builder->where('(t.tahun IS NULL OR t.tahun = ' . $this->db->escape($filter['tahun']) . ')');
        }
        
        if (!empty($filter['bulan'])) {
            $builder->where('t.bulan', $filter['bulan']);
        }
        
        // Filter spesifik ke nama indikator jika dipilih dari dropdown ("Nama Indikator" pada form IKU)
        if (!empty($filter['indikator'])) {
            // Dropdown mengirim data dengan pola "1. Teks Indikator", "2. Teks Indikator", kita perlu membersihkan prefix angka di awal string
            $cleanIndikator = preg_replace('/^\d+\.\s*/', '', $filter['indikator']);
            $builder->where('m.nama_indikator_kinerja', $cleanIndikator);
        }

        return $builder->orderBy('t.id', 'ASC')->orderBy('m.id', 'ASC')->get()->getResultArray();
    }
}
