<?php
require_once 'config/database.php';

try {
    $pdo = Database::getInstance();
    
    $sql = "CREATE TABLE IF NOT EXISTS `siege_requests` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `siege_id` int(11) NOT NULL,
      `national_id_number` varchar(50) NOT NULL,
      `description` text NOT NULL,
      `contact_info` text NOT NULL,
      `attachments` text, 
      `status` enum('pending','approved','rejected') DEFAULT 'pending',
      `president_message` text,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`siege_id`) REFERENCES `sieges`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql);
    echo "Table 'siege_requests' created successfully.\n";
    
} catch (Exception $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}
