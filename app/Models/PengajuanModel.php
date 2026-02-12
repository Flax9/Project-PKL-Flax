<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $config;

    public function __construct()
    {
        parent::__construct();
        $this->config = config('DataMapping');
        $this->table = $this->config->tables['pengajuan_perubahan'];
    }

    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id',
        'tahun',
        'bulan',
        'fungsi',
        'no_iku',
        'nama_indikator',
        'no_indikator',
        'no_bulan',
        // Detailed Revision Columns
        'target',
        'realisasi',
        'perf_bulan',
        'kat_bulan',
        'perf_tahun',
        'kat_tahun',
        'cap_norm',
        'cap_norm_angka',
        // Meta
        'jenis_revisi',
        'file_nota_dinas',
        'file_disposisi',
        'file_surat_roren',
        'file_sc_eperformance',
        'tgl_upload_nota',
        'tgl_upload_disposisi',
        'tgl_upload_roren',
        'tgl_upload_eperformance',
        'status',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Helper to get all requests for a user or all if admin/planner
    public function getRequests($userId = null, $status = null)
    {
        $builder = $this->builder();
        
        if ($userId) {
            $builder->where('user_id', $userId);
        }

        if ($status && $status !== 'all') {
            $builder->where('status', $status);
        }

        return $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
    }

    /**
     * Cari kandidat data IKU di tabel capaian_iku untuk form pengajuan
     */
    public function findIkuCandidates($tahun, $bulan, $fungsi, $no_iku)
    {
        $db = \Config\Database::connect();
        $table = $this->config->tables['capaian_iku'];
        $rows = [];

        // 1. Try EXACT match first
        $builder = $db->table($table);
        $builder->where('Tahun', $tahun)
                ->where('Bulan', $bulan)
                ->where('Fungsi', $fungsi)
                ->where('`No. IKU`', $db->escape($no_iku), false);
        
        try {
            $rows = $builder->get()->getResultArray();
        } catch (\Throwable $e) { $rows = []; }

        // 2. If not found, try stripped "IKU " prefix (e.g. "1")
        if (empty($rows)) {
            $checkIku = str_replace('IKU ', '', $no_iku);
            
            $builder = $db->table($table);
            $builder->where('Tahun', $tahun)
                    ->where('Bulan', $bulan)
                    ->where('Fungsi', $fungsi)
                    ->where('`No. IKU`', $db->escape($checkIku), false);

            try {
                $rows = $builder->get()->getResultArray();
            } catch (\Throwable $e) { $rows = []; }
        }

        // 3. If still not found, try adding "IKU " prefix (e.g. "IKU 1")
        if (empty($rows)) {
            $checkIku = 'IKU ' . str_replace('IKU ', '', $no_iku);
            
            $builder = $db->table($table);
            $builder->where('Tahun', $tahun)
                    ->where('Bulan', $bulan)
                    ->where('Fungsi', $fungsi)
                    ->where('`No. IKU`', $db->escape($checkIku), false);

            try {
                $rows = $builder->get()->getResultArray();
            } catch (\Throwable $e) { $rows = []; }
        }

        return $rows;
    }

    /**
     * Cek validitas kombinasi data lengkap di database
     */
    public function checkValidity($params)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->config->tables['capaian_iku']);
        
        // Helper map params to DB columns
        $map = [
            'db_tahun' => 'Tahun',
            'db_bulan' => 'Bulan',
            'db_fungsi' => 'Fungsi',
            'db_no_iku' => 'No. IKU',
            'db_nama_indikator' => 'Nama Indikator',
            'db_no_indikator' => 'No. Indikator',
            'db_no_bulan' => 'No. Bulan',
            'db_target' => 'Target',
            'db_realisasi' => 'Realisasi',
            'db_perf_bulan' => 'Performa % Capaian Bulan',
            'db_kat_bulan' => 'Kategori Capaian Bulan',
            'db_perf_tahun' => 'Performa % Capaian Tahun',
            'db_kat_tahun' => 'Kategori Capaian Tahun',
            'db_cap_norm' => 'Capaian Normalisasi',
            'db_cap_norm_angka' => 'Capaian normalisasi Angka'
        ];

        foreach ($map as $paramKey => $dbCol) {
            $val = $params[$paramKey] ?? null;
            // Manual escaping for safe identifiers
            $builder->where("`$dbCol`", $db->escape($val), false);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Simpan pengajuan baru dan snapshot data original
     */
    public function createSubmission($data)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Insert Proposed Changes
            $this->insert($data);
            $newId = $this->getInsertID();

            // 2. Capture Snapshot of Original Data
            // Logic: Find the row in capaian_iku that matches
            $ikuRaw = str_replace('IKU ', '', $data['no_iku']);
            $val1 = $db->escape($ikuRaw);
            $val2 = $db->escape('IKU ' . $ikuRaw);
            
            $builder = $db->table($this->config->tables['capaian_iku']);
            $builder->where('Tahun', $data['tahun'])
                    ->where('Bulan', $data['bulan'])
                    ->where('Fungsi', $data['fungsi'])
                    // Filter by Non-NULL Target instead of fragile No. Indikator
                    ->where("(`Target` IS NOT NULL AND `Target` != '-' AND `Target` != '')", null, false)
                    ->where("(`No. IKU` = $val1 OR `No. IKU` = $val2)", null, false);
            
            $originalMaster = $builder->get()->getRowArray();

            if ($originalMaster) {
                // Map DB columns to snapshot columns
                $snapshot = [
                    'pengajuan_id'   => $newId,
                    'tahun'          => $originalMaster['Tahun'],
                    'bulan'          => $originalMaster['Bulan'],
                    'fungsi'         => $originalMaster['Fungsi'],
                    'no_iku'         => $originalMaster['No. IKU'],
                    'nama_indikator' => $originalMaster['Nama Indikator'],
                    'no_indikator'   => $originalMaster['No. Indikator'],
                    'no_bulan'       => $originalMaster['No. Bulan'],
                    'target'         => $originalMaster['Target'],
                    'realisasi'      => $originalMaster['Realisasi'],
                    'perf_bulan'     => $originalMaster['Performa % Capaian Bulan'],
                    'kat_bulan'      => $originalMaster['Kategori Capaian Bulan'],
                    'perf_tahun'     => $originalMaster['Performa % Capaian Tahun'],
                    'kat_tahun'      => $originalMaster['Kategori Capaian Tahun'],
                    'cap_norm'       => $originalMaster['Capaian Normalisasi'],
                    'cap_norm_angka' => $originalMaster['Capaian normalisasi Angka'],
                    'created_at'     => date('Y-m-d H:i:s')
                ];
                $db->table($this->config->tables['pengajuan_log'])->insert($snapshot);
            }

            $db->transComplete();
            return $db->transStatus();

        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    /**
     * Handle File Upload and Status Update
     */
    public function handleUpload($id, $fieldInput, $targetDir, $newStatus)
    {
        $request = \Config\Services::request();
        $file = $request->getFile($fieldInput);
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            if (!is_dir(FCPATH . $targetDir)) {
                mkdir(FCPATH . $targetDir, 0777, true);
            }
            $file->move(FCPATH . $targetDir, $newName);
            
            // Map timestamps based on field
            $timeField = 'tgl_upload_nota'; 
            if($fieldInput == 'file_disposisi') $timeField = 'tgl_upload_disposisi';
            if($fieldInput == 'file_surat_roren') $timeField = 'tgl_upload_roren';
            if($fieldInput == 'file_sc_eperformance') $timeField = 'tgl_upload_eperformance';

            $dataToUpdate = [
                $fieldInput => $targetDir . '/' . $newName,
                $timeField  => date('Y-m-d H:i:s'),
                'status'    => $newStatus,
                'updated_at'=> date('Y-m-d H:i:s')
            ];

            return $this->update($id, $dataToUpdate);
        }
        return false;
    }

    /**
     * Sync approved data back to Master Table (capaian_iku)
     */
    public function syncToMaster($requestId)
    {
        $db = \Config\Database::connect();
        $request = $this->find($requestId);
        
        if (!$request) return false;

        // 1. Fetch the SNAPSHOT to get the exact identity keys
        $snapshot = $db->table($this->config->tables['pengajuan_log'])
                       ->where('pengajuan_id', $requestId)
                       ->get()
                       ->getRowArray();

        if (!$snapshot) {
             $snapshot = $request; // Fallback? Slightly dangerous but logic from legacy
        }

        // 2. Update Query
        // 2. Update Query (Refactored to Query Builder)
        $ikuRaw = str_replace('IKU ', '', $snapshot['no_iku']);
        $ikuWithPrefix = 'IKU ' . $ikuRaw;

        $builder = $db->table($this->config->tables['capaian_iku']);
        
        // Set Values
        $builder->set('Target', $request['target'])
                ->set('Realisasi', $request['realisasi'])
                ->set('Performa % Capaian Bulan', $request['perf_bulan'])
                ->set('Kategori Capaian Bulan', $request['kat_bulan'])
                ->set('Performa % Capaian Tahun', $request['perf_tahun'])
                ->set('Kategori Capaian Tahun', $request['kat_tahun'])
                ->set('Capaian Normalisasi', $request['cap_norm'])
                ->set('Capaian normalisasi Angka', $request['cap_norm_angka']);
        
        // Clauses
        $builder->where('Tahun', $snapshot['tahun'])
                ->where('Bulan', $snapshot['bulan'])
                ->where('Fungsi', $snapshot['fungsi'])
                ->where("(`Target` IS NOT NULL AND `Target` != '-' AND `Target` != '')", null, false)
                ->groupStart()
                    ->where('`No. IKU`', $ikuRaw)
                    ->orWhere('`No. IKU`', $ikuWithPrefix)
                ->groupEnd();

        return $builder->update();
    }

    /**
     * Get Snapshot Data
     */
    public function getSnapshot($id)
    {
        $db = \Config\Database::connect();
        return $db->table($this->config->tables['pengajuan_log'])
                  ->where('pengajuan_id', $id)
                  ->get()
                  ->getRowArray();
    }
}
