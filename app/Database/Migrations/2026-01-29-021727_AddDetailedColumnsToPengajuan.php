<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailedColumnsToPengajuan extends Migration
{
    public function up()
    {
        $fields = [
            'no_indikator' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'nama_indikator'
            ],
            'no_bulan' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'after'      => 'no_indikator'
            ],
            'target' => [
                'type' => 'TEXT', 
                'null' => true,
                'after' => 'no_bulan'
            ],
            'realisasi' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'target'
            ],
            'perf_bulan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'realisasi'
            ],
            'kat_bulan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'perf_bulan'
            ],
            'perf_tahun' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'kat_bulan'
            ],
            'kat_tahun' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'perf_tahun'
            ],
            'cap_norm' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'kat_tahun'
            ],
            'cap_norm_angka' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'cap_norm'
            ],
        ];

        $this->forge->addColumn('pengajuan_perubahan', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('pengajuan_perubahan', [
            'no_indikator', 'no_bulan', 'target', 'realisasi', 
            'perf_bulan', 'kat_bulan', 'perf_tahun', 'kat_tahun', 
            'cap_norm', 'cap_norm_angka'
        ]);
    }
}
