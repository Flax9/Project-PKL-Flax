<?php

namespace App\Models;

use CodeIgniter\Model;

class CapaianOutputModel extends Model
{
    protected $config;

    public function __construct()
    {
        parent::__construct();
        $this->config = config('DataMapping');
        $this->table = $this->config->tables['capaian_output'];
    }

    protected $primaryKey = 'id';

    /**
     * Helper: Apply filters to Query Builder
     */
    private function applyFilters($builder, $filter = [])
    {
        // $builder is properly typed as \CodeIgniter\Database\BaseBuilder
        $db = \Config\Database::connect();

        if (!empty($filter['tahun'])) {
            $builder->where('Tahun', $filter['tahun']);
        }
        if (!empty($filter['bulan'])) {
            $builder->where('Bulan', $filter['bulan']);
        }
        if (!empty($filter['fungsi'])) {
            $builder->where('Fungsi', $filter['fungsi']);
        }
        if (!empty($filter['keterangan_ro'])) {
            // Manual escape is still needed if using where string for column name with spaces?
            // CI4 handles `where('Col Name', $val)` correctly with backticks automatically?
            // To be safe and consistent with existing logic:
            $builder->where("`Keterangan RO` = " . $db->escape($filter['keterangan_ro']), null, false);
        }

        return $builder;
    }

    /**
     * Get scoreboard data
     */
    public function getScoreboard($filter = [])
    {
        // Subquery for Indeks Capaian
        // Complex logic: Average of Max Capaian per RO
        $subBuilder = $this->db->table($this->config->tables['capaian_output'])
                           ->select('MAX(Capaian) as max_capaian', false)
                           ->where('Capaian >', 0)
                           ->groupBy('`No. RO`', false);
        
        $this->applyFilters($subBuilder, $filter);
        $subQuery = $subBuilder->getCompiledSelect();

        // Main Query
        $builder = $this->builder();
        $builder->select('
            MAX(`Target tahun`) as total_target, 
            SUM(`Realisasi`) as total_realisasi,
            MAX(`Realisasi Kumulatif`) as realisasi_kumulatif,
            MAX(`Realisasi Kumulatif %`) as persen_realisasi_kumulatif
        ', false);
        
        // Add Subquery Select
        $builder->select("(SELECT ROUND(AVG(sub.max_capaian), 2) FROM ($subQuery) as sub) as indeks_capaian");

        $this->applyFilters($builder, $filter);

        return $builder->get()->getRow();
    }

    /**
     * Get bar chart data (Target vs Realisasi per RO)
     */
    public function getBarData($filter = [])
    {
        $builder = $this->builder();
        $builder->select('
            `No. RO` as no, 
            MAX(`Target tahun`) as target, 
            MAX(`Realisasi`) as realisasi
        ', false);
        
        $this->applyFilters($builder, $filter);
        
        $builder->groupBy('`No. RO`', false)
                ->orderBy('CAST(`No. RO` AS UNSIGNED)', 'ASC', false);

        return $builder->get()->getResultArray();
    }

    /**
     * Get kategori capaian for donut chart
     */
    public function getKategoriCapaian($filter = [])
    {
        $builder = $this->builder();
        $builder->select('`Kategori` as label, COUNT(*) as jumlah', false);
        
        $this->applyFilters($builder, $filter);
        
        $builder->where("`Kategori` NOT IN ('N/A', '#DIV/0!', '', '-')", null, false)
                ->groupBy('`Kategori`', false)
                ->orderBy('jumlah', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get kategori belanja for donut chart
     */
    public function getKategoriBelanja($filter = [])
    {
        $builder = $this->builder();
        $builder->select('`Kategori Belanja` as label, COUNT(*) as jumlah', false);
        
        $this->applyFilters($builder, $filter);
        
        $builder->where("`Kategori Belanja` NOT IN ('N/A', '#DIV/0!', '', '-')", null, false)
                ->groupBy('`Kategori Belanja`', false)
                ->orderBy('jumlah', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get trend data (realisasi kumulatif per bulan)
     */
    public function getTrendData($filter = [])
    {
        $builder = $this->builder();
        $builder->select('`Bulan`, `No. Bulan`, SUM(`Realisasi Kumulatif`) as kumulatif', false);
        
        $this->applyFilters($builder, $filter);
        
        $builder->groupBy('`Bulan`, `No. Bulan`', false)
                ->orderBy('`No. Bulan`', 'ASC', false);

        return $builder->get()->getResultArray();
    }

    /**
     * Get ranking data (top or bottom N RO)
     */
    public function getRankingData($filter = [], $order = 'DESC', $limit = 5)
    {
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        $limit = (int)$limit;

        $builder = $this->builder();
        $builder->select('`No. RO` as no, MAX(`Realisasi Kumulatif %`) as nilai', false);
        
        $this->applyFilters($builder, $filter);
        
        $builder->groupBy('`No. RO`', false)
                ->orderBy('nilai', $order)
                ->limit($limit);

        return $builder->get()->getResultArray();
    }

    /**
     * Get distinct values for filter dropdowns
     */
    public function getFilterOptions($column, $filter = [])
    {
        $builder = $this->builder();
        
        // Case 1: Dropdown Bulan (Sort by No. Bulan)
        if ($column === '`No. Bulan`, `Bulan`') {
            $builder->select($column, false)
                    ->distinct();
            $this->applyFilters($builder, $filter); // Need base filters? usually not for month list but code had it
            $builder->where("Bulan != '' AND Bulan IS NOT NULL")
                    ->orderBy('`No. Bulan`', 'ASC', false);
            return $builder->get()->getResultArray();
        } 
        
        // Case 2: Dropdown Tahun (Global)
        elseif ($column === 'Tahun') {
            return $this->select('Tahun')
                        ->distinct()
                        ->where("Tahun != '' AND Tahun IS NOT NULL")
                        ->orderBy('Tahun', 'DESC')
                        ->get()
                        ->getResultArray();
        } 
        
        // Case 3: Dropdown Keterangan RO
        elseif ($column === '`Keterangan RO`, `No. RO`') {
            $builder->select('`Keterangan RO` as keterangan, `No. RO` as RO', false)
                    ->distinct();
            $this->applyFilters($builder, $filter);
            $builder->where("`Keterangan RO` NOT IN ('N/A', '', '-') AND `Keterangan RO` IS NOT NULL", null, false)
                    ->orderBy('CAST(`No. RO` AS UNSIGNED)', 'ASC', false); // Assuming GroupBy not strictly needed if distinct matches
            return $builder->get()->getResultArray();
        } 
        
        // Case 4: Default (Fungsi, etc)
        else {
            $cleanCol = str_replace('`', '', $column);
            $builder->select($cleanCol)->distinct();
            $this->applyFilters($builder, $filter);
            $builder->where("$cleanCol != '' AND $cleanCol IS NOT NULL")
                    ->orderBy($cleanCol, 'ASC');
            return $builder->get()->getResultArray();
        }
    }
}
