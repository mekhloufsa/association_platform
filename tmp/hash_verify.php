<?php
// Aura - hash_verify.php
$password = 'admin123';
$hash = '$2y$10$F0kaJjbR8GDUx3ns3CUIz.4ooUDCd3O9dh00EETYhUssiILOZF6fa';

if (password_verify($password, $hash)) {
    echo "Verification SUCCESS\n";
} else {
    echo "Verification FAILED\n";
}
