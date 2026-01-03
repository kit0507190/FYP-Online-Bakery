<?php
// config.php - Recommended & Production-Ready Version
$host    = 'localhost';
$dbname  = 'bakeryhouse';
$username = 'root';
$password = '';                    // Put your DB password here if you have one

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Critical errors will throw exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // Always return associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                     // Use real prepared statements (more secure)
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // In production, never show raw error to users
    die("Database connection failed. Please try again later.");
    // For development only, you can temporarily uncomment the line below:
    // die("Connection failed: " . $e->getMessage());
}
?>