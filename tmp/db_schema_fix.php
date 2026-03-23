<?php
$host = '127.0.0.1';
$db   = 'association_platform';
$user = 'root';
$pass = 'Root2025!,';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Connection success!\n";

     // Fix associations table
     try {
         $pdo->exec("ALTER TABLE associations ADD COLUMN thank_you_message TEXT DEFAULT NULL");
         echo "Associations: thank_you_message added.\n";
     } catch (PDOException $e) {
         if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
             echo "Associations: thank_you_message already exists.\n";
         } else {
             echo "Associations Error: " . $e->getMessage() . "\n";
         }
     }

     // Fix donations table
     try {
         $pdo->exec("ALTER TABLE donations ADD COLUMN siege_id INT DEFAULT NULL AFTER association_id");
         $pdo->exec("ALTER TABLE donations ADD CONSTRAINT fk_donation_siege FOREIGN KEY (siege_id) REFERENCES sieges(id) ON DELETE SET NULL");
         echo "Donations: siege_id and constraint added.\n";
     } catch (PDOException $e) {
         if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
             echo "Donations: siege_id already exists.\n";
         } else if (strpos($e->getMessage(), 'Duplicate key name') !== false || strpos($e->getMessage(), 'Duplicate entry') !== false) {
             echo "Donations: constraint already exists.\n";
         } else {
             echo "Donations Error: " . $e->getMessage() . "\n";
         }
     }

} catch (\PDOException $e) {
     echo "Connection Error: " . $e->getMessage() . "\n";
}
