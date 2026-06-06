<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsernameAndPhotoToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'unique'     => true,
            ],
            'photo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'default'    => 'default.jpg',
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'username');
        $this->forge->dropColumn('users', 'photo');
    }
}
