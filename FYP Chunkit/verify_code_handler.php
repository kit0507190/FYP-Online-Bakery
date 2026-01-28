<?php
// Set local timezone for database comparison
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

$host = 'localhost'; $db = 'bakeryhouse'; $user = 'root'; $pass = ''; 

// Process code verification on POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['reset_email'])) {
    $email = $_SESSION['reset_email'];
    $user_code = trim($_POST['code']); 

    try {
        // Initialize database connection
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        
        // Get current time for expiry check
        $current_time = date("Y-m-d H:i:s");

        // Validate email, code, and expiration status
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND token = ? AND created_at > ?");
        $stmt->execute([$email, $user_code, $current_time]);
        $reset = $stmt->fetch();

        if ($reset) {
            // Success: Mark session as verified and redirect to password reset
            $_SESSION['code_verified'] = true;
            header("Location: resetpassword.php?token=" . urlencode($user_code));
            exit;
        } else {
            // Failure: Redirect back with error message
            header("Location: verify_code.php?error=" . urlencode("The verification code is incorrect or has expired."));
            exit;
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>