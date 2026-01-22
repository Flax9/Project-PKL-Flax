<?php
namespace App\Models;
use CodeIgniter\Model;

// Nama class harus sama persis dengan nama file (Case Sensitive)
class modelIKU extends Model {
    protected $table = 'capaian_iku';
    protected $primaryKey = 'id';
   protected $allowedFields = ['Fungsi', 'No. Indikator', 'No. IKU', 'Nama Indikator', 'No. Bulan', 'Bulan', 'Target', 'Realisasi', 'Tahun'];
}