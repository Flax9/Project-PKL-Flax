<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggaranModel extends Model
{
    protected $table = 'anggaran'; // Nama tabel sesuai phpMyAdmin

    /**
     * Fungsi pembantu untuk menerapkan filter ke Query Builder
     */
    private function applyFilters($builder, $filter = [])
    {
        if (!empty($filter['tahun'])) {
            $builder->where('Tahun', $filter['tahun']);
        }
        if (!empty($filter['bulan'])) {
            $builder->where('Bulan', $filter['bulan']);
        }
        if (!empty($filter['program'])) {
            $builder->where('`PROGRAM/KEGIATAN`', $filter['program']);
        }
        if (!empty($filter['ro'])) {
            $builder->where('`RO`', $filter['ro']);
        }
        return $builder;
    }

    public function getScoreboard($filter = [])
    {
        $builder = $this->select("
                COALESCE(SUM(`PAGU`), 0) as total_pagu, 
                COALESCE(SUM(`REALISASI`), 0) as total_realisasi,
                (COALESCE(SUM(`PAGU`), 0) - COALESCE(SUM(`REALISASI`), 0)) as sisa_anggaran,
                CASE 
                    WHEN SUM(`PAGU`) > 0 THEN (SUM(`REALISASI`) / SUM(`PAGU`) * 100) 
                    ELSE 0 
                END as persentase_serapan,
                -- TAMBAHKAN BARIS INI UNTUK TARGET TW
                ROUND(AVG(NULLIF(`CAPAIAN_TARGET_TW`, 0)), 2) as avg_target_tw
            ");
        
        $this->applyFilters($builder, $filter);
        return $builder->get()->getRow();
    }

    public function getChartProgram($filter = [])
    {
        $builder = $this->select("
                `PROGRAM/KEGIATAN` as program, 
                SUM(`PAGU`) as pagu, 
                SUM(`REALISASI`) as realisasi
            ");
        
        $this->applyFilters($builder, $filter);
        
        return $builder->groupBy('`PROGRAM/KEGIATAN`')
                       ->orderBy('pagu', 'DESC')
                       ->findAll();
    }

    public function getMonthlyTrend($tahun = null, $filter = [])
    {
        $builder = $this->select("TRIM(Bulan) as Bulan, SUM(`REALISASI`) as realisasi");
        
        // Filter tahun wajib untuk grafik tren agar urutan bulannya relevan
        if ($tahun) {
            $builder->where('Tahun', $tahun);
        }

        // Terapkan filter tambahan (kecuali bulan, agar tren tetap terlihat satu tahun penuh)
        if (!empty($filter['program'])) {
            $builder->where('`PROGRAM/KEGIATAN`', $filter['program']);
        }
        if (!empty($filter['ro'])) {
            $builder->where('`RO`', $filter['ro']);
        }

        return $builder->groupBy('Bulan')
            ->orderBy("FIELD(TRIM(Bulan), 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->findAll();
    }

    /**
     * Fungsi untuk mendapatkan daftar unik untuk isi dropdown filter
     */
    public function getFilterOptions($column)
    {
        return $this->distinct()
                    ->select($column)
                    ->where($column . ' IS NOT NULL')
                    ->where($column . " != ''")
                    ->orderBy($column, 'ASC')
                    ->findAll();
    }

    public function getFilterBulan()
    {
        return $this->distinct()
                    ->select('Bulan')
                    ->where('Bulan IS NOT NULL')
                    ->where("Bulan != ''")
                    ->orderBy("FIELD(TRIM(Bulan), 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
                    ->findAll();
    }
}