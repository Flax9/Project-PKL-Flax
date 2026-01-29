<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameNilaiMenjadiToJenisRevisi extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('pengajuan_perubahan', [
            'nilai_menjadi' => [
                'name' => 'jenis_revisi',
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('pengajuan_perubahan', [
            'jenis_revisi' => [
                'name' => 'nilai_menjadi',
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }
}
