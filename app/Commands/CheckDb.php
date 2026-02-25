<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckDb extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'db:check';
    protected $description = 'Check db schema';

    public function run(array $params)
    {
        // Load config explicitly to get DataMapping
        $mappedConfig = config('DataMapping');
        $table = $mappedConfig->tables['anggaran'] ?? 'anggaran';
        
        $db = \Config\Database::connect();
        CLI::write("Fields for {$table}:");
        $fields = $db->getFieldData($table);
        foreach($fields as $f) {
            CLI::write($f->name . ' - ' . $f->type);
        }
        
        $row = $db->table($table)->get()->getFirstRow('array');
        CLI::write("\nSample Row:");
        print_r($row);
    }
}
