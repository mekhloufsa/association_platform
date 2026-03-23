<?php
// Aura - db_import.php
require_once 'config/database.php';

try {
    $pdo = Database::getInstance();
    $sql = file_get_contents('database.sql');

    // Split SQL by semicolon, but handle cases inside quotes if needed (simplified here)
    // For database.sql, simple split should work if no complex triggers/procs
    $queries = explode(";", $sql);

    foreach ($queries as $query) {
        $query = trim($query);
        if ($query) {
            $pdo->exec($query);
        }
    }

    echo "Database imported successfully.\n";

} catch (Exception $e) {
    echo "Error during import: " . $e->getMessage() . "\n";
}
