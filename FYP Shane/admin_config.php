<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function getAdminPDOConnection() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $host = '127.0.0.1';
            $dbname = 'bakeryhouse';
            $username = 'root';        
            $password = '';           

            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("DB Connection failed: " . $e->getMessage());
            return null;
        }
    }
    return $pdo;
}

function cleanAdminInput($data) {
    return trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
}

function verifyAdminPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_role']);
}

// Redirect to login
function redirectToAdminLogin() {
    header("Location: admin_login.php");
    exit();
}

// Redirect to dashboard 
function redirectToAdminDashboard() {
    header("Location: admin_dashboard.php");
    exit();
}

?>