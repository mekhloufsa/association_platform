<?php
require_once 'config/database.php';

function checkTable($tableName) {
    try {
        $pdo = Database::getInstance();
        echo "\n--- Columns in $tableName ---\n";
        $stmt = $pdo->query("DESCRIBE $tableName");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "{$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Default']}\n";
        }
    } catch (Exception $e) {
        echo "Error checking $tableName: " . $e->getMessage() . "\n";
    }
}

checkTable('help_requests');
checkTable('siege_requests');
checkTable('sieges');
checkTable('users');
