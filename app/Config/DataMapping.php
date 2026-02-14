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
     * Definisi Header dan Tipe Data untuk validasi Import Excel.
     * Format: 'Nama Kolom Excel' => 'tipe_data'
     * Tipe yang didukung: 'string', 'decimal', 'integer', 'date'
     */
    public $headers = [
        // Header ketat untuk IkuEntryModel
        'iku_import' => [
            'Fungsi'                    => 'string',
            'No. Indikator'             => 'string', 
            'No. IKU'                   => 'string',
            'Nama Indikator'            => 'string',
            'No. Bulan'                 => 'integer',
            'Bulan'                     => 'string',
            'Target'                    => 'decimal',
            'Realisasi'                 => 'decimal',
            'Performa %Capaian Bulan'   => 'decimal',
            'Kategori Capaian Bulan'    => 'string',
            'Performa %Capaian Tahun'   => 'decimal',
            'Kategori Capaian Tahun'    => 'string',
            'Capaian Normalisasi'       => 'decimal',
            'Capaian normalisasi Angka' => 'decimal',
            'Tahun'                     => 'integer'
        ],
        
        // Header minimal untuk NkoEntryModel check
        'nko_required' => [
            'bulan'         => 'string',
            'total capaian' => 'decimal',
            'total iku'     => 'integer',
            'nko'           => 'decimal',
            'tahun'         => 'integer'
        ],

        // Header untuk AnggaranEntryModel
        'anggaran_required' => [
            'No. RO'                     => 'string', // Kadang ada huruf
            'RO'                         => 'string',
            'PROGRAM/KEGIATAN'           => 'string',
            'PAGU'                       => 'decimal',
            'REALISASI'                  => 'decimal',
            'Capaian Realisasi'          => 'decimal',
            '% Target TW'                => 'decimal',
            'CAPAIAN TERHADAP TARGET TW' => 'decimal',
            'Kategori TW'                => 'string',
            'Bulan'                      => 'string'
        ],

        // Header untuk CapaianOutputEntryModel
        'capaian_output_required' => [
            'Tahun'               => 'integer',
            'Bulan'               => 'string',
            'No. Bulan'           => 'integer',
            'Rincian Output'      => 'string',
            'No. RO'              => 'string', 
            'Keterangan RO'       => 'string',
            'Fungsi'              => 'string',
            'Target %Bulan'       => 'decimal',
            'Realisasi'           => 'decimal', 
            '%Realisasi'          => 'decimal',
            'Realisasi Kumulatif' => 'decimal',
            'Capaian'             => 'decimal',
            'Kategori'            => 'string'
        ]
    ];

    /**
     * Allowed Fields untuk Model
     * Kunci array sesuai dengan kunci di $tables
     */
    public $allowedFields = [
        'iku' => [
            'Fungsi', 'No. Indikator', 'No. IKU', 'Nama Indikator', 'No. Bulan', 'Bulan', 
            'Target', 'Realisasi', 'Performa %Capaian Bulan', 'Kategori Capaian Bulan', 
            'Performa %Capaian Tahun', 'Kategori Capaian Tahun', 'Capaian Normalisasi', 
            'Capaian normalisasi Angka', 'Tahun'
        ],
        'nko' => [
            'Tahun', 'Bulan', 'Total Capaian', 'Total IKU', 'NKO'
        ],
        'anggaran' => [
            'Tahun', 'Bulan', 'No. RO', 'RO', 'PROGRAM/KEGIATAN', 'PAGU', 'REALISASI', 
            'Capaian Realisasi', 'Target TW', 'Capaian Target TW', 'Kategori TW'
        ],
        'capaian_output' => [
            'Tahun', 'Bulan', 'No. Bulan', 'Rincian Output', 'No. RO', 'Kode RO', 'Keterangan RO',
            'Fungsi', 'Target %Bulan', 'Realisasi', '%Realisasi', 'Realisasi Kumulatif', 
            'Salah %Realisasi Kumulatif', 'Capaian', 'Kategori', 'Target Tahun', 'Kategori Belanja', 
            'Realisasi Kumulatif %'
        ]
    ];
}
