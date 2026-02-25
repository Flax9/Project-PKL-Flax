<?php
// Initialize CI4
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

require 'public/index.php';

$db = \Config\Database::connect();
$tableInfo = $db->getFieldData('capaian_iku');
print_r($tableInfo);
