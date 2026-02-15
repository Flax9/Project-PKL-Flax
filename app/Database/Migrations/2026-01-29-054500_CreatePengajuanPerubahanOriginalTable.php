<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengajuanPerubahanOriginalTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pengajuan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            // Snapshot of Identity fields
            'tahun' => [
                'type'       => 'YEAR',
                'null'       => true,
            ],
            'bulan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'fungsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'no_iku' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_indikator' => [
                'type' => 'TEXT',
            ],
            'no_indikator' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'no_bulan' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            
            // Snapshot of Original Values
            'target' => [
                'type' => 'TEXT', 
                'null' => true,
            ],
            'realisasi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'perf_bulan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kat_bulan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'perf_tahun' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kat_tahun' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cap_norm' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cap_norm_angka' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pengajuan_id', 'pengajuan_perubahan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengajuan_perubahan_original', true);
    }

    public function down()
    {
        $this->forge->dropTable('pengajuan_perubahan_original');
    }
}
