<?php
$db = new mysqli('localhost', 'root', '', 'db_monitoring_bpom');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
$result = $db->query("SELECT id, username, role FROM users");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
?>
