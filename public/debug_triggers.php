<?php
// Direct PDO connection
$dsn = 'mysql:host=localhost;dbname=db_monitoring_bpom;charset=utf8mb4';
$user = 'root';
$pass = ''; 

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SHOW TRIGGERS LIKE 'capaian_iku'");
    $triggers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nTRIGGERS:\n";
    print_r($triggers);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
