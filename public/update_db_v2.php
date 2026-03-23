<?php
require_once __DIR__ . '/../config/database.php';

try {
    $db = Database::getInstance();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h1>Database Update v2</h1>";

    // 1. Add columns to help_requests
    try {
        $db->exec("ALTER TABLE help_requests ADD COLUMN appointment_details TEXT DEFAULT NULL, ADD COLUMN refusal_message TEXT DEFAULT NULL");
        echo "<p style='color: green;'>✅ help_requests: appointment_details and refusal_message columns added.</p>";
    } catch (PDOException $e) {
        echo "<p style='color: blue;'>ℹ️ help_requests update: " . $e->getMessage() . "</p>";
    }

    echo "<hr><p>Mise à jour terminée. Vous pouvez supprimer ce fichier (<b>public/update_db_v2.php</b>).</p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Connection Error: " . $e->getMessage() . "</p>";
}
