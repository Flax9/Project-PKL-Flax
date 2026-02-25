<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AddTimestamp extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'db:add_timestamp';
    protected $description = 'Add updated_at column to tables';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $tables = ['capaian_iku', 'master_anggaran_iku', 'transaksi_anggaran_iku'];
        
        foreach($tables as $table) {
            if (!$db->fieldExists('updated_at', $table)) {
                $db->query("ALTER TABLE `{$table}` ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
                CLI::write("Added updated_at to {$table}", 'green');
            } else {
                CLI::write("updated_at already exists in {$table}", 'yellow');
            }
        }
    }
}
