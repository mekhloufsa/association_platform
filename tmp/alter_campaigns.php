<?php
require_once __DIR__ . '/../config/database.php';

try {
    $db = Database::getInstance();
    
    // Check if image_path exists, if not add it
    $result = $db->query("SHOW COLUMNS FROM campaigns LIKE 'image_path'")->fetch();
    if (!$result) {
        $db->query("ALTER TABLE campaigns ADD COLUMN image_path VARCHAR(255) NULL");
        echo "Column image_path added.\n";
    }
    
    // Check if approval_status exists, if not add it
    $result = $db->query("SHOW COLUMNS FROM campaigns LIKE 'approval_status'")->fetch();
    if (!$result) {
        $db->query("ALTER TABLE campaigns ADD COLUMN approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved'");
        echo "Column approval_status added.\n";
    }
    
    echo "Database migration complete.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
