<?php
namespace App\Models;
use CodeIgniter\Model;

// Nama class harus sama persis dengan nama file (Case Sensitive)
class IkuModel extends Model {
    protected $table = 'capaian_iku';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Fungsi', 'No. Indikator', 'No. IKU', 'Nama Indikator', 'No. Bulan', 'Bulan', 'Target', 'Realisasi', 'Tahun'];

    /**
     * Helper: Apply filters to Query Builder
     * @param object $builder Query Builder instance
     * @param array $filter Associative array with filter keys
     * @return object Modified builder
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
        if (!empty($filter['indikator'])) {
            $builder->where('`Nama Indikator`', $db->escape($filter['indikator']), false);
        }
        if (!empty($filter['fungsi'])) {
            $builder->where('Fungsi', $filter['fungsi']);
        }

        return $builder;
    }

    /**
     * Get summary statistics (avg bulan, avg tahun)
     */
    public function getSummary($filter = [])
    {
        $builder = $this->select('
            ROUND(AVG(`Performa % Capaian Bulan`), 2) as avg_bulan, 
            ROUND(AVG(`Performa % Capaian Tahun`), 2) as avg_tahun
        ');
        
        $this->applyFilters($builder, $filter);
        return $builder->get()->getRow();
    }

    /**
     * Get NKO average
     * @param array $filter Filter parameters
     */
    public function getNkoAverage($filter = [])
    {
        $builder = $this->db->table('nko');
        $builder->select('ROUND(AVG(`NKO`), 2) as total_nko');
        
        if (!empty($filter['tahun'])) {
            $builder->where('Tahun', $filter['tahun']);
        }
        if (!empty($filter['bulan'])) {
            $builder->where('Bulan', $filter['bulan']);
        }

        return $builder->get()->getRow();
    }

    /**
     * Get total unique IKU up to given year
     */
    public function getTotalIku($tahun)
    {
        return $this->distinct()
                    ->select('COUNT(DISTINCT `No. IKU`) as total')
                    ->where('Tahun <=', $tahun)
                    ->get()
                    ->getRow()->total;
    }

    /**
     * Get trend data (line chart) - avg per month
     */
    public function getTrendData($filter = [])
    {
        // Gunakan false agar CI tidak meng-escape manual
        $builder = $this->select('
            `Bulan`, 
            `No. Bulan`,
            ROUND(AVG(`Performa % Capaian Bulan`), 2) as rata_bulan, 
            ROUND(AVG(`Performa % Capaian Tahun`), 2) as rata_tahun
        ', false);

        if (!empty($filter['tahun'])) {
            $builder->where('Tahun', $filter['tahun']);
        }

        // Gunakan string dengan escape=false untuk groupBy dan orderBy
        return $builder->groupBy('`Bulan`, `No. Bulan`', false)
                       ->orderBy('`No. Bulan`', 'ASC', false)
                       ->findAll();
    }

    /**
     * Get bar chart data (Target vs Realisasi per IKU)
     */
    public function getBarData($filter = [])
    {
        $builder = $this->select('
            `No. IKU` as no, 
            AVG(Target) as target, 
            AVG(Realisasi) as realisasi
        ', false);

        $this->applyFilters($builder, $filter);

        return $builder->groupBy('`No. IKU`', false)
                       ->orderBy('CAST(`No. IKU` AS UNSIGNED)', 'ASC', false)
                       ->findAll();
    }

    /**
     * Get kategori data for donut charts
     * @param array $filter Filter parameters
     * @param string $type 'bulan' or 'tahun'
     */
    public function getKategoriData($filter = [], $type = 'bulan')
    {
        $column = ($type === 'bulan') ? 'Kategori Capaian Bulan' : 'Kategori Capaian Tahun';
        
        $builder = $this->select("`$column` as label, COUNT(*) as jumlah", false);
        $this->applyFilters($builder, $filter);

        return $builder->groupBy("`$column`", false)->findAll();
    }

    /**
     * Get ranking data (top or bottom N)
     * @param array $filter Filter parameters
     * @param string $order 'ASC' or 'DESC'
     * @param int $limit Number of results
     */
    public function getRankingData($filter = [], $order = 'DESC', $limit = 5)
    {
        $builder = $this->select('
            `No. IKU` as no, 
            AVG(`Performa % Capaian Tahun`) as nilai
        ', false);

        $this->applyFilters($builder, $filter);

        return $builder->groupBy('`No. IKU`', false)
                       ->orderBy('nilai', $order)
                       ->limit($limit)
                       ->findAll();
    }

    /**
     * Get top 5 list for sidebar table
     */
    public function getTopFiveList($filter = [])
    {
        $builder = $this->select('
            `Nama Indikator` as nama, 
            `Performa % Capaian Tahun` as nilai
        ', false);

        $this->applyFilters($builder, $filter);

        return $builder->orderBy('nilai', 'DESC')
                       ->limit(5)
                       ->findAll();
    }

    /**
     * Get distinct values for filter dropdowns
     * @param string $column Column name to get distinct values
     * @param array $filter Base filter to apply before getting distinct values
     */
    public function getFilterOptions($column, $filter = [])
    {
        // Builder awal (akan di-override untuk kolom tertentu)
        $builder = $this->distinct();

        // Apply base filter (usually just tahun)
        if (!empty($filter['tahun'])) {
            $builder->where('Tahun', $filter['tahun']);
        }

        // Special ordering and selection for specific columns
        if ($column === '`No. Bulan`, `Bulan`') {
            return $builder->select($column, false)
                           ->where('Bulan IS NOT NULL')
                           ->where("Bulan != ''")
                           ->orderBy('`No. Bulan`', 'ASC', false)
                           ->findAll();
        } elseif ($column === 'Tahun') {
            return $builder->select($column, false)
                           ->where('Tahun IS NOT NULL')
                           ->where("Tahun != ''")
                           ->orderBy('Tahun', 'DESC')
                           ->findAll();
        } elseif ($column === '`Nama Indikator`') {
            // FIX: View mengharapkan key 'nama'
            return $builder->select('`Nama Indikator` as nama', false)
                           ->groupBy('`Nama Indikator`', false)
                           ->orderBy('`No. Indikator`', 'ASC', false)
                           ->findAll();
        } else {
            return $builder->select($column, false)
                           ->where($column . ' IS NOT NULL')
                           ->where($column . " != ''")
                           ->orderBy($column, 'ASC', false)
                           ->findAll();
        }
    }
}