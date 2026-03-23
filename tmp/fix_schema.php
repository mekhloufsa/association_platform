<?php
try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=association_platform;charset=utf8mb4', 'root', 'Root2025!');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_TIMEOUT, 5);

    // Check if column exists
    $stmt = $db->query("SHOW COLUMNS FROM associations LIKE 'thank_you_message'");
    if (!$stmt->fetch()) {
        echo "Adding column...\n";
        $db->exec("ALTER TABLE associations ADD COLUMN thank_you_message TEXT DEFAULT NULL");
        echo "SUCCESS: Column added.\n";
    } else {
        echo "ALREADY_EXISTS: Column already there.\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
