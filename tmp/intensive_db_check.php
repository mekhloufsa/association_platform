<?php
require_once 'config/database.php';
$db = Database::getInstance();

echo "Listing all tables:\n";
$stmt = $db->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
print_r($tables);

foreach ($tables as $table) {
    echo "\nStructure of table '$table':\n";
    $stmt = $db->query("SHOW CREATE TABLE $table");
    $res = $stmt->fetch();
    echo $create_stmt = $res['Create Table'] . "\n";
}

// Try to delete a siege and catch error
$idToDelete = null;
$stmt = $db->query("SELECT id FROM sieges LIMIT 1");
$siege = $stmt->fetch();
if ($siege) {
    $idToDelete = $siege['id'];
    echo "Attempting to delete siege ID: $idToDelete\n";
    try {
        $stmt = $db->prepare("DELETE FROM sieges WHERE id = ?");
        $res = $stmt->execute([$idToDelete]);
        if ($res) {
            echo "Successfully deleted siege $idToDelete.\n";
        } else {
            echo "Failed to delete siege $idToDelete.\n";
            print_r($db->errorInfo());
        }
    } catch (PDOException $e) {
        echo "PDOException: " . $e->getMessage() . "\n";
    }
} else {
    echo "No sieges found to delete.\n";
}
