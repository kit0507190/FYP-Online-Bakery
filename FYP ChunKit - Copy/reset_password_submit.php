<?php
// reset_password_submit.php
session_start();
date_default_timezone_set('Asia/Kuala_Lumpur');

// 数据库连接配置
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
    
    // 1. 检查两次密码是否一致
    if ($password !== $confirm_password) {
        // 重定向回重置页，保留 token 以便用户继续操作
        header("Location: resetpassword.php?token={$token}&error=" . urlencode("Passwords do not match."));
        exit;
    }

    // 2. 原地纠正逻辑：检查密码长度
    if (strlen($password) < 8) {
        // 密码太短，直接跳回重置页并显示错误
        // 这里不需要重发 code，因为 token 依然有效
        header("Location: resetpassword.php?token={$token}&error=" . urlencode("Password must be at least 8 characters long."));
        exit;
    }
    
    // 3. 验证验证码是否依然有效
    $current_time = date("Y-m-d H:i:s");
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND created_at > ?");
    $stmt->execute([$token, $current_time]);
    $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset_request) {
        $email = $reset_request['email'];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $pdo->beginTransaction();
        try {
            // 更新密码
            $update_stmt = $pdo->prepare("UPDATE user_db SET password = ? WHERE email = ?");
            $update_stmt->execute([$hashed_password, $email]);

            // 清理验证码记录
            $delete_stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
            $delete_stmt->execute([$token]);
            
            $pdo->commit();
            
            // 清理 Session 安全标记
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
        header("Location: forgotpassword.php?message=" . urlencode("Link expired."));
        exit;
    }
}