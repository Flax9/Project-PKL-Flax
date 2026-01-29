<?php
// Direct PDO connection to bypass CI4 bootstrap issues
$dsn = 'mysql:host=localhost;dbname=db_monitoring_bpom;charset=utf8mb4';
$user = 'root';
$pass = ''; // Default XAMPP/Laragon

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("DESCRIBE capaian_iku");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "\nCOLUMNS OF capaian_iku:\n";
    print_r($columns);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


