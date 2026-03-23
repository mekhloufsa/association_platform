<?php
require_once 'config/database.php';

try {
    $pdo = Database::getInstance();
    $stmt = $pdo->query("SHOW TABLES");
    echo "--- Tables in database ---\n";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo $row[0] . "\n";
    }
} catch (Exception $e) {
    echo "Error listing tables: " . $e->getMessage() . "\n";
}
