<?php
$db = new mysqli('localhost', 'root', '', 'db_monitoring_bpom');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
$result = $db->query("DESCRIBE pengajuan_perubahan");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
?>
