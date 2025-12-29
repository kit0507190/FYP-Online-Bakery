<?php
$host = 'localhost';
$db   = 'bakeryhouse';
$user = 'root';        // Change if needed (e.g., on hosting)
$pass = '';            // Change if you have a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>