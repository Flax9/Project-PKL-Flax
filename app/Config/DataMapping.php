<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class DataMapping extends BaseConfig
{
    /**
     * Mapping nama tabel database.
     * Menggunakan config ini memudahkan perubahan nama tabel di masa depan
     * tanpa harus mengubah kode di banyak model.
     */
    public $tables = [
        'anggaran'               => 'anggaran',
        'capaian_iku'            => 'capaian_iku',
        'capaian_output'         => 'capaian_output',
        'nko'                    => 'nko',
        'pengajuan_perubahan'    => 'pengajuan_perubahan',
        'pengajuan_log'          => 'pengajuan_perubahan_original',
        'master_anggaran'        => 'master_anggaran',
        'database_ro'            => 'database_ro',
        'iku_year_prefix'        => 'database_iku_', // e.g. database_iku_2025
    ];

    /**
     * Mapping Nama Sheet Excel untuk Import.
     */
    public $sheets = [
        'anggaran'       => 'Anggaran',
        'iku'            => 'Capaian IKU',
        'capaian_output' => 'Capaian Output',
        'nko'            => 'NKO',
    ];

    /**
     * Definisi Header untuk validasi Import Excel.
     */
    public $headers = [
        // Header ketat untuk IkuEntryModel
        'iku_import' => [
            'Fungsi', 'No. Indikator', 'No. IKU', 'Nama Indikator', 'No. Bulan',
            'Bulan', 'Target', 'Realisasi', 'Performa %Capaian Bulan',
            'Kategori Capaian Bulan', 'Performa %Capaian Tahun',
            'Kategori Capaian Tahun', 'Capaian Normalisasi',
            'Capaian normalisasi Angka', 'Tahun'
        ],
        
        // Header minimal untuk NkoEntryModel check
        'nko_required' => [
            'bulan', 'total capaian', 'total iku', 'nko'
        ],

        // Header untuk AnggaranEntryModel
        'anggaran_required' => [
            'No. RO', 'RO', 'PROGRAM/KEGIATAN', 'PAGU', 'REALISASI',
            'Capaian Realisasi', '% Target TW', 'CAPAIAN TERHADAP TARGET TW', 
            'Kategori TW', 'Bulan'
        ],

        // Header untuk CapaianOutputEntryModel
        'capaian_output_required' => [
            'Tahun', 'Bulan', 'No. Bulan', 'Rincian Output', 'No. RO', 
            'Keterangan RO', 'Fungsi', 'Target %Bulan', 'Realisasi', 
            '%Realisasi', 'Realisasi Kumulatif', 'Capaian', 'Kategori'
        ]
    ];
}
