<?php
// Aura - user_check.php
require_once 'config/database.php';

try {
    $pdo = Database::getInstance();

    echo "Listing users table content:\n";
    $stmt = $pdo->query("SELECT id, email, password_hash, role, status FROM users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
