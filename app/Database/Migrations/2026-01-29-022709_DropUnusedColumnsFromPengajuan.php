<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropUnusedColumnsFromPengajuan extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('pengajuan_perubahan', ['id_iku', 'nilai_semula']);
    }

    public function down()
    {
        // Revert (add back if rolled back)
        $fields = [
            'id_iku' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'nilai_semula' => [
                'type' => 'TEXT', 
                'null' => true
            ],
        ];
        $this->forge->addColumn('pengajuan_perubahan', $fields);
    }
}
