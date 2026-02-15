<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailVerificationColumnsToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'temp_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email'
            ],
            'email_otp' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
                'null' => true,
                'after' => 'temp_email'
            ],
            'otp_created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'email_otp'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['temp_email', 'email_otp', 'otp_created_at']);
    }
}
