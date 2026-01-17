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
        header("Location: resetpassword.php?token={$token}&error=" . urlencode("Passwords do not match."));
        exit;
    }

    // 2. 【核心安全性修改】同步注册页面的验证标准
    // 检查：长度 8+、包含字母、包含数字
    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        // 验证失败，退回重置页
        header("Location: resetpassword.php?token={$token}&error=" . urlencode("Password must be 8+ chars with letters & numbers."));
        exit;
    }
    
    // 3. 验证验证码（Token）是否依然有效
    // 注意：这里的 SQL 逻辑需要匹配你数据库中的失效时间
    $current_time = date("Y-m-d H:i:s");
    // 修正：created_at 是创建时间，通常有效 Token 的 created_at 应该大于（当前时间 - 有效期）
    // 或者你的逻辑是 created_at 存储的是过期时间，这里保持你原有的逻辑判断
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND created_at > ?");
    $stmt->execute([$token, $current_time]);
    $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset_request) {
        $email = $reset_request['email'];
        // 使用 password_hash 默认算法（更安全）
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $pdo->beginTransaction();
        try {
            // 更新密码并增加更新时间记录（如果有 updated_at 字段的话）
            $update_stmt = $pdo->prepare("UPDATE user_db SET password = ?, updated_at = NOW() WHERE email = ?");
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
        header("Location: forgotpassword.php?message=" . urlencode("Link or code expired."));
        exit;
    }
}