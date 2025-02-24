<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_name = 'mysql:host=localhost;dbname=artibuip_az';
$user_name = 'artibuip_az';
$user_password = 'Artistico@123';

try {
    $conn = new PDO($db_name, $user_name, $user_password);
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
} catch (PDOException $e) {
    // Handle database errors, e.g., log the error, display a user-friendly message, etc.
    echo "Database Error: " . $e->getMessage();
}

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
