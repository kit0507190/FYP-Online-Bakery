<?php

session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');


$host = 'localhost'; $db = 'bakeryhouse'; $user = 'root'; $pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header("Location: User_Login.php?error=" . urlencode("System error."));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // 1. Check if the two passwords match
    if ($password !== $confirm_password) {
        header("Location: resetpassword.php?token={$token}&error=" . urlencode("Passwords do not match."));
        exit;
    }

    // 2. [Core Security Modification] Verification Standards for Synchronized Registration Page
    // Checks: Length 8+, Contains letters, Contains numbers
    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        
        header("Location: resetpassword.php?token={$token}&error=" . urlencode("Password must be 8+ chars with letters & numbers."));
        exit;
    }
    
    // 3. Verify if the verification code (Token) is still valid.
    $current_time = date("Y-m-d H:i:s");
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND created_at > ?");
    $stmt->execute([$token, $current_time]);
    $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset_request) {
        $email = $reset_request['email'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $pdo->beginTransaction();
        try {
            // Update password and add update time record
            $update_stmt = $pdo->prepare("UPDATE user_db SET password = ?, updated_at = NOW() WHERE email = ?");
            $update_stmt->execute([$hashed_password, $email]);

            // Clear verification records
            $delete_stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
            $delete_stmt->execute([$token]);
            
            $pdo->commit();
            
            
            unset($_SESSION['reset_email']);
            unset($_SESSION['code_verified']);

            header("Location: User_Login.php?message=" . urlencode("Success! Password reset."));
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            header("Location: resetpassword.php?token={$token}&error=" . urlencode("Update failed."));
            exit;
        }
    } else {
        header("Location: forgotpassword.php?message=" . urlencode("Link or code expired."));
        exit;
    }
}