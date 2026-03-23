<?php
require_once 'config/database.php';

$db = Database::getInstance();
$dbname = 'association_platform';

$sql = "SELECT 
            TABLE_NAME, 
            COLUMN_NAME, 
            CONSTRAINT_NAME, 
            REFERENCED_TABLE_NAME, 
            REFERENCED_COLUMN_NAME 
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE REFERENCED_TABLE_NAME = 'sieges' 
          AND TABLE_SCHEMA = '$dbname'";

$stmt = $db->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Foreign Keys pointing to 'sieges':\n";
foreach ($results as $row) {
    echo "{$row['TABLE_NAME']}.{$row['COLUMN_NAME']} -> {$row['REFERENCED_TABLE_NAME']}.{$row['REFERENCED_COLUMN_NAME']} ({$row['CONSTRAINT_NAME']})\n";
}

// Also check the specific ON DELETE actions
$sql = "SELECT 
            TABLE_NAME, 
            CONSTRAINT_NAME, 
            DELETE_RULE 
        FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS 
        WHERE REFERENCED_TABLE_NAME = 'sieges' 
          AND CONSTRAINT_SCHEMA = '$dbname'";

$stmt = $db->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "\nReferential Constraints Actions:\n";
foreach ($results as $row) {
    echo "{$row['TABLE_NAME']} ({$row['CONSTRAINT_NAME']}): ON DELETE {$row['DELETE_RULE']}\n";
}
