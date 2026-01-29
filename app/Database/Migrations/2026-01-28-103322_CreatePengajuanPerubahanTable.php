<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengajuanPerubahanTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            // Data Identity fields (replicating IKU structure)
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
                'constraint' => 50, // e.g., "IKU 1"
            ],
            'id_iku' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true, // Optional linking ID if needed
            ],
            'nama_indikator' => [
                'type' => 'TEXT',
            ],
            
            // Change Request Data
            'nilai_semula' => [
                'type' => 'TEXT', // Text to accommodate various formats
            ],
            'nilai_menjadi' => [
                'type' => 'TEXT',
            ],
            
            // File Uploads
            'file_nota_dinas' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'file_disposisi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'file_surat_roren' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'file_sc_eperformance' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            // Timestamps for each step
            'tgl_upload_nota' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'tgl_upload_disposisi' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'tgl_upload_roren' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'tgl_upload_eperformance' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['diajukan', 'disposisi', 'proses_roren', 'selesai', 'ditolak'],
                'default'    => 'diajukan',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('pengajuan_perubahan');
    }

    public function down()
    {
        $this->forge->dropTable('pengajuan_perubahan');
    }
}
