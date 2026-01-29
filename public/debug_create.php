<?php
// Direct PDO connection
$dsn = 'mysql:host=localhost;dbname=db_monitoring_bpom;charset=utf8mb4';
$user = 'root';
$pass = ''; 

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SHOW CREATE TABLE capaian_iku");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\nCREATE TABLE:\n";
    print_r($result);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
