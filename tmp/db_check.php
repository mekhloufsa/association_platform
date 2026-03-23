<?php
// Aura - db_check.php
require_once 'config/database.php';

try {
    $pdo = Database::getInstance();

    echo "Checking wilayas table columns:\n";
    $stmt = $pdo->query("DESCRIBE wilayas");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
