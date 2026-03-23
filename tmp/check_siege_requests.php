<?php
require_once 'config/database.php';
$db = Database::getInstance();
$stmt = $db->query("SHOW TABLES LIKE 'siege_requests'");
$res = $stmt->fetch();
if ($res) {
    echo "Table 'siege_requests' exists.\n";
    $stmt = $db->query("SHOW CREATE TABLE siege_requests");
    $create = $stmt->fetch();
    echo $create['Create Table'] . "\n";
} else {
    echo "Table 'siege_requests' MISSING.\n";
}
