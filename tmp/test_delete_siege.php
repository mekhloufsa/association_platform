<?php
require_once 'config/database.php';
require_once 'core/Model.php';
require_once 'models/Siege.php';

$id = 1; // Change to a valid id if known, or I'll find one
$siegeModel = new Siege();

try {
    $result = $siegeModel->delete($id);
    if ($result) {
        echo "Success deleting siege $id\n";
    }
    else {
        echo "Failed deleting siege $id\n";
        print_r($siegeModel->getDb()->errorInfo());
    }
}
catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
