<?php
// Aura - db_reset_import.php
require_once 'config/database.php';

try {
    $pdo = Database::getInstance();
    
    // Disable foreign key checks for reset
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Explicitly drop tables to ensure schema from database.sql is applied fresh
    $tables = ['volunteers', 'campaigns', 'donations', 'help_requests', 'annonces', 'sieges', 'associations', 'users', 'wilayas', 'tasks', 'activity_logs'];
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `$table` CASCADE");
    }
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    $sql = file_get_contents('database.sql');
    
    // Use exec directly for the whole file if PDO allows multiple statements, 
    // or better, read line by line or use a more reliable split.
    // For many MySQL setups, exec() can handle multiple statements if configured.
    // Let's try to exec the whole thing first as it's a standard SQL file.
    if ($pdo->exec($sql) !== false) {
        echo "Database reset and imported successfully.\n";
    } else {
        echo "Error: exec returned false.\n";
    }

} catch (Exception $e) {
    echo "Error during import: " . $e->getMessage() . "\n";
}
