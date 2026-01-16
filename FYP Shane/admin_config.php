<?php
// admin/admin_config.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection for admin panel
function getAdminPDOConnection() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            // CHANGE THESE TO YOUR ACTUAL DB CREDENTIALS
            $host = '127.0.0.1';
            $dbname = 'bakeryhouse';
            $username = 'root';        // change if needed
            $password = '';            // change if needed

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

// Clean input
function cleanAdminInput($data) {
    return trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
}

// Verify password (assuming you used password_hash)
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

// Redirect to dashboard - THIS WAS MISSING OR BROKEN
function redirectToAdminDashboard() {
    header("Location: admin_dashboard.php");
    exit();
}

// Optional: Define max login attempts
if (!defined('ADMIN_MAX_LOGIN_ATTEMPTS')) {
    define('ADMIN_MAX_LOGIN_ATTEMPTS', 5);
}
?>