<?php
require_once __DIR__ . '/../config/database.php';

try {
    $db = Database::getInstance();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h1>Database Fix Script</h1>";

    // Fix associations table
    try {
        $db->exec("ALTER TABLE associations ADD COLUMN thank_you_message TEXT DEFAULT NULL");
        echo "<p style='color: green;'>✅ Associations: thank_you_message column added.</p>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<p style='color: blue;'>ℹ️ Associations: thank_you_message column already exists.</p>";
        } else {
            echo "<p style='color: red;'>❌ Associations Error: " . $e->getMessage() . "</p>";
        }
    }

    // Fix donations table
    try {
        $db->exec("ALTER TABLE donations ADD COLUMN siege_id INT DEFAULT NULL AFTER association_id");
        echo "<p style='color: green;'>✅ Donations: siege_id column added.</p>";
        
        try {
            $db->exec("ALTER TABLE donations ADD CONSTRAINT fk_donation_siege FOREIGN KEY (siege_id) REFERENCES sieges(id) ON DELETE SET NULL");
            echo "<p style='color: green;'>✅ Donations: foreign key constraint added.</p>";
        } catch (PDOException $e) {
             echo "<p style='color: blue;'>ℹ️ Donations: Constraint might already exist or failed: " . $e->getMessage() . "</p>";
        }

    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<p style='color: blue;'>ℹ️ Donations: siege_id column already exists.</p>";
        } else {
            echo "<p style='color: red;'>❌ Donations Error: " . $e->getMessage() . "</p>";
        }
    }

    echo "<hr><p>Vous pouvez maintenant supprimer ce fichier (<b>public/fix_db.php</b>) et réessayer de sauvegarder votre message.</p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Connection Error: " . $e->getMessage() . "</p>";
}
