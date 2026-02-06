<?php

namespace App\Models;

use CodeIgniter\Model;

class capaianoutputmodel extends Model
{
    protected $table = 'capaian_output';
    protected $primaryKey = 'id';

    /**
     * Helper: Apply filters to Query Builder
     */
    private function applyFilters($builder, $filter = [])
    {
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
            $builder->where('`Keterangan RO`', $db->escape($filter['keterangan_ro']), false);
        }

        return $builder;
    }

    /**
     * Get scoreboard data
     */
    public function getScoreboard($filter = [])
    {
        $db = \Config\Database::connect();
        
        $whereDetail = $this->buildWhereClause($filter);

        return $db->query("
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
    }

    /**
     * Get bar chart data (Target vs Realisasi per RO)
     */
    public function getBarData($filter = [])
    {
        $db = \Config\Database::connect();
        $where = $this->buildWhereClause($filter);

        $sql = "SELECT 
                    `No. RO` as no, 
                    MAX(`Target tahun`) as target, 
                    MAX(`Realisasi`) as realisasi
                FROM capaian_output 
                $where
                GROUP BY `No. RO`
                ORDER BY CAST(`No. RO` AS UNSIGNED) ASC";

        return $db->query($sql)->getResultArray(); // Use getResultArray for consistency with CI4 builder default
    }

    /**
     * Get kategori capaian for donut chart (clean N/A)
     */
    public function getKategoriCapaian($filter = [])
    {
        $db = \Config\Database::connect();
        $where = $this->buildWhereClause($filter);

        $sql = "SELECT 
                    `Kategori` as label, 
                    COUNT(*) as jumlah 
                FROM capaian_output 
                $where 
                AND `Kategori` NOT IN ('N/A', '#DIV/0!', '', '-')
                GROUP BY `Kategori`
                ORDER BY jumlah DESC";

        return $db->query($sql)->getResultArray();
    }

    /**
     * Get kategori belanja for donut chart (clean N/A)
     */
    public function getKategoriBelanja($filter = [])
    {
        $db = \Config\Database::connect();
        $where = $this->buildWhereClause($filter);

        $sql = "SELECT 
                    `Kategori Belanja` as label, 
                    COUNT(*) as jumlah 
                FROM capaian_output 
                $where 
                AND `Kategori Belanja` NOT IN ('N/A', '#DIV/0!', '', '-')
                GROUP BY `Kategori Belanja`
                ORDER BY jumlah DESC";

        return $db->query($sql)->getResultArray();
    }

    /**
     * Get trend data (realisasi kumulatif per bulan)
     */
    /**
     * Get trend data (realisasi kumulatif per bulan)
     */
    public function getTrendData($filter = [])
    {
        $db = \Config\Database::connect();
        $where = $this->buildWhereClause($filter);

        $sql = "SELECT 
                    `Bulan`, 
                    `No. Bulan`, 
                    SUM(`Realisasi Kumulatif`) as kumulatif
                FROM capaian_output 
                $where
                GROUP BY `Bulan`, `No. Bulan`
                ORDER BY `No. Bulan` ASC";

        return $db->query($sql)->getResultArray();
    }

    /**
     * Get ranking data (top or bottom N RO)
     * @param array $filter Filter parameters
     * @param string $order 'ASC' or 'DESC'
     * @param int $limit Number of results
     */
    public function getRankingData($filter = [], $order = 'DESC', $limit = 5)
    {
        $db = \Config\Database::connect();
        $where = $this->buildWhereClause($filter);
        
        // Sanitize order and limit just in case
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        $limit = (int)$limit;

        $sql = "SELECT 
                    `No. RO` as no, 
                    MAX(`Realisasi Kumulatif %`) as nilai
                FROM capaian_output 
                $where
                GROUP BY `No. RO`
                ORDER BY nilai $order
                LIMIT $limit";

        return $db->query($sql)->getResultArray();
    }

    /**
     * Get distinct values for filter dropdowns
     */
    /**
     * Get distinct values for filter dropdowns
     */
    public function getFilterOptions($column, $filter = [])
    {
        $db = \Config\Database::connect();
        
        // Case 1: Dropdown Bulan (Sort by No. Bulan)
        if ($column === '`No. Bulan`, `Bulan`') {
            // Perlu filter tahun/fungsi jika ada
            $where = $this->buildWhereClause($filter);
            $sql = "SELECT DISTINCT `No. Bulan`, `Bulan` 
                    FROM capaian_output 
                    $where 
                    AND `Bulan` IS NOT NULL AND `Bulan` != ''
                    ORDER BY `No. Bulan` ASC";
            return $db->query($sql)->getResultArray();
        } 
        
        // Case 2: Dropdown Tahun (Ambil semua tahun yg tersedia)
        elseif ($column === 'Tahun') {
            // Biasanya tahun tidak difilter oleh filter lain, ambil distinct global
            $sql = "SELECT DISTINCT `Tahun` 
                    FROM capaian_output 
                    WHERE `Tahun` IS NOT NULL AND `Tahun` != ''
                    ORDER BY `Tahun` DESC";
            return $db->query($sql)->getResultArray();
        } 
        
        // Case 3: Dropdown Keterangan RO
        elseif ($column === '`Keterangan RO`, `No. RO`') {
            $where = $this->buildWhereClause($filter);
            // Group by keduanya untuk safe SQL (only_full_group_by compatible)
            $sql = "SELECT DISTINCT `Keterangan RO` as keterangan, `No. RO` as RO
                    FROM capaian_output 
                    $where 
                    AND `Keterangan RO` IS NOT NULL AND `Keterangan RO` NOT IN ('N/A', '', '-')
                    GROUP BY `Keterangan RO`, `No. RO`
                    ORDER BY CAST(`No. RO` AS UNSIGNED) ASC";
            return $db->query($sql)->getResultArray();
        } 
        
        // Case 4: Default (Fungsi, dll)
        else {
            $where = $this->buildWhereClause($filter);
            $cleanCol = str_replace('`', '', $column);
            
            $sql = "SELECT DISTINCT `$cleanCol` 
                    FROM capaian_output 
                    $where 
                    AND `$cleanCol` IS NOT NULL AND `$cleanCol` != ''
                    ORDER BY `$cleanCol` ASC";
            return $db->query($sql)->getResultArray();
        }
    }

    /**
     * Helper: Build WHERE clause string for raw SQL queries
     */
    private function buildWhereClause($filter = [])
    {
        $db = \Config\Database::connect();
        
        $whereDetail = " WHERE 1=1 ";

        if (!empty($filter['tahun'])) {
            $tahunSafe = $db->escape($filter['tahun']);
            $whereDetail .= " AND Tahun = $tahunSafe ";
        }
        if (!empty($filter['bulan'])) {
            $bulanSafe = $db->escape($filter['bulan']);
            $whereDetail .= " AND Bulan = $bulanSafe";
        }
        if (!empty($filter['fungsi'])) {
            $fungsiSafe = $db->escape($filter['fungsi']);
            $whereDetail .= " AND Fungsi = $fungsiSafe";
        }
        if (!empty($filter['keterangan_ro'])) {
            $whereDetail .= " AND `Keterangan RO` = " . $db->escape($filter['keterangan_ro']);
        }

        return $whereDetail;
    }
}
