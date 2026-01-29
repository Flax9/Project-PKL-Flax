<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $table            = 'pengajuan_perubahan';
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
    public function getRequests($userId = null)
    {
        $builder = $this->builder();
        
        if ($userId) {
            $builder->where('user_id', $userId);
        }

        return $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
    }
}
