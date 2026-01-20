<?php
// db_connect.php (PDO version)
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "bakeryhouse";

try {
    $pdo = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}