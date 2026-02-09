<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SchemaDump extends Migration
{
    public function up()
    {
        // 1. Table: anggaran
        $this->forge->addField([
            'No. RO' => ['type' => 'SMALLINT', 'constraint' => 6, 'null' => true],
            'RO' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'PROGRAM/KEGIATAN' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'PAGU' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'REALISASI' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'Capaian Realisasi' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'Target TW' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'CAPAIAN_TARGET_TW' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'Kategori TW' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Bulan' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Tahun' => ['type' => 'SMALLINT', 'constraint' => 6, 'null' => true],
        ]);
        $this->forge->createTable('anggaran', true);

        // 2. Table: capaian_iku
        $this->forge->addField([
            'Fungsi' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'No. Indikator' => ['type' => 'TINYINT', 'constraint' => 20, 'null' => true],
            'No. IKU' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Nama Indikator' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'No. Bulan' => ['type' => 'TINYINT', 'constraint' => 20, 'null' => true],
            'Bulan' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Target' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Realisasi' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Performa % Capaian Bulan' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Kategori Capaian Bulan' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Performa % Capaian Tahun' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Kategori Capaian Tahun' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Capaian Normalisasi' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Capaian normalisasi Angka' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Tahun' => ['type' => 'SMALLINT', 'constraint' => 6, 'null' => true],
        ]);
        $this->forge->createTable('capaian_iku', true);

        // 3. Table: capaian_output
        $this->forge->addField([
            'Rincian Output' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'No. RO' => ['type' => 'TINYINT', 'constraint' => 20, 'null' => true],
            'Keterangan RO' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Fungsi' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'No. Bulan' => ['type' => 'TINYINT', 'constraint' => 20, 'null' => true],
            'Bulan' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Target % Bulan' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Realisasi' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            '% Realisasi' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Realisasi Kumulatif' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'salah % Realisasi Kumulatif' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Capaian' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Kategori' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Target tahun' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Kategori Belanja' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Realisasi Kumulatif %' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
            'Tahun' => ['type' => 'SMALLINT', 'constraint' => 20, 'null' => true],
        ]);
        $this->forge->createTable('capaian_output', true);

        // 4. Table: database_iku_2025
        $this->forge->addField([
            'No.IKU' => ['type' => 'SMALLINT', 'constraint' => 6, 'null' => true],
            'Nama Indikator' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Kertas Kerja iku' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Manual IKU' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Fungsi' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
        ]);
        $this->forge->createTable('database_iku_2025', true);

        // 5. Table: database_iku_2026
        $this->forge->addField([
            'No.IKU' => ['type' => 'SMALLINT', 'constraint' => 6, 'null' => true],
            'Nama Indikator' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Kertas Kerja iku' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Manual IKU' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Fungsi' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
        ]);
        $this->forge->createTable('database_iku_2026', true);

        // 6. Table: database_ro
        $this->forge->addField([
            'No. RO' => ['type' => 'SMALLINT', 'constraint' => 6, 'null' => true],
            'RO' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Rincian Output' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Manual RO' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
            'Kertas Kerja ro' => ['type' => 'TEXT', 'null' => true, 'collation' => 'utf8mb4_general_ci'],
        ]);
        $this->forge->createTable('database_ro', true);

        // 7. Table: master_anggaran
        $this->forge->addField([
            'no_ro' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'ro' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false, 'collation' => 'utf8mb4_general_ci'],
            'program_kegiatan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'collation' => 'utf8mb4_general_ci'],
        ]);
        $this->forge->createTable('master_anggaran', true);
        
        // 8. Table: master_database
        $this->forge->addField([
             'IKU' => ['type' => 'TEXT', 'null' => true],
             'No.IKU' => ['type' => 'BIGINT', 'constraint' => 20, 'null' => true],
             'Nama Indikator' => ['type' => 'TEXT', 'null' => true],
             'Kertas Kerja iku' => ['type' => 'TEXT', 'null' => true],
             'Manual IKU' => ['type' => 'TEXT', 'null' => true],
             'RO' => ['type' => 'TEXT', 'null' => true],
             'No. RO' => ['type' => 'DOUBLE', 'null' => true],
             'Rincian Output' => ['type' => 'TEXT', 'null' => true],
             'Manual RO' => ['type' => 'TEXT', 'null' => true], 
             'Kertas Kerja ro' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->createTable('master_database', true);
        
        // 9. Table: nko
        $this->forge->addField([
            'nama' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'realisasi' => ['type' => 'FLOAT', 'null' => true],
            'pembilang' => ['type' => 'FLOAT', 'null' => true],
            'penyebut' => ['type' => 'FLOAT', 'null' => true],
            'capaian' => ['type' => 'FLOAT', 'null' => true],
            'bulan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tahun' => ['type' => 'INT', 'constraint' => 100, 'null' => true],
            'satuan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'target' => ['type' => 'FLOAT', 'null' => true],
            'bobot' => ['type' => 'FLOAT', 'null' => true],
        ]);
        $this->forge->createTable('nko', true);

        // 10. Table: password_resets
         $this->forge->addField([
            'email' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'token' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('email', false); // Index
        $this->forge->createTable('password_resets', true);

         // 11. Table: pengajuan_perubahan
        $this->forge->addField([
             'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
             'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
             'tahun' => ['type' => 'SMALLINT', 'constraint' => 6],
             'bulan' => ['type' => 'VARCHAR', 'constraint' => 20],
             'fungsi' => ['type' => 'VARCHAR', 'constraint' => 50],
             'no_iku' => ['type' => 'VARCHAR', 'constraint' => 50],
             'nama_indikator' => ['type' => 'TEXT', 'null' => true],
             'no_indikator' => ['type' => 'TINYINT', 'constraint' => 4, 'null' => true],
             'no_bulan' => ['type' => 'TINYINT', 'constraint' => 4, 'null' => true],
             
             'target' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'realisasi' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'perf_bulan' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'kat_bulan' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
             'perf_tahun' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'kat_tahun' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
             'cap_norm' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'cap_norm_angka' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],

             'jenis_revisi' => ['type' => 'ENUM', 'constraint' => ['Revisi Target','Revisi Realisasi','Revisi Keduanya'], 'default' => 'Revisi Realisasi'],
             'keterangan' => ['type' => 'TEXT', 'null' => true],
             'file_nota_dinas' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
             'file_disposisi' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
             'file_surat_roren' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
             'file_sc_eperformance' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
             
             'tgl_upload_nota' => ['type' => 'DATETIME', 'null' => true],
             'tgl_upload_disposisi' => ['type' => 'DATETIME', 'null' => true],
             'tgl_upload_roren' => ['type' => 'DATETIME', 'null' => true],
             'tgl_upload_eperformance' => ['type' => 'DATETIME', 'null' => true],
             
             'status' => ['type' => 'ENUM', 'constraint' => ['diajukan','disposisi','proses_roren','selesai','ditolak'], 'default' => 'diajukan'],
             'created_at' => ['type' => 'DATETIME', 'null' => true],
             'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pengajuan_perubahan', true);

         // 12. Table: pengajuan_perubahan_original
         $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pengajuan_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
             'tahun' => ['type' => 'SMALLINT', 'constraint' => 6, 'null' => true],
             'bulan' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
             'fungsi' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
             'no_iku' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
             'nama_indikator' => ['type' => 'TEXT', 'null' => true],
             'no_indikator' => ['type' => 'TINYINT', 'constraint' => 4, 'null' => true],
             'no_bulan' => ['type' => 'TINYINT', 'constraint' => 4, 'null' => true],
             'target' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'realisasi' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'perf_bulan' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'kat_bulan' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
             'perf_tahun' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'kat_tahun' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
             'cap_norm' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'cap_norm_angka' => ['type' => 'DECIMAL', 'constraint' => '10,0', 'null' => true],
             'created_at' => ['type' => 'DATETIME', 'null' => true],
         ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pengajuan_perubahan_original', true);

        // 13. Table: users
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'email_verified_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'role' => ['type' => 'ENUM', 'constraint' => ['admin','user','perencana'], 'default' => 'user'],
            'remember_token' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users', true);

    }

    public function down()
    {
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('pengajuan_perubahan_original', true);
        $this->forge->dropTable('pengajuan_perubahan', true);
        $this->forge->dropTable('password_resets', true);
        $this->forge->dropTable('nko', true);
        $this->forge->dropTable('master_database', true);
        $this->forge->dropTable('master_anggaran', true);
        $this->forge->dropTable('database_ro', true);
        $this->forge->dropTable('database_iku_2026', true);
        $this->forge->dropTable('database_iku_2025', true);
        $this->forge->dropTable('capaian_output', true);
        $this->forge->dropTable('capaian_iku', true);
        $this->forge->dropTable('anggaran', true);
    }
}
