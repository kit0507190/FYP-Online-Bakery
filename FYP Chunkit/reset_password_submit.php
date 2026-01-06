<?php
// reset_password_submit.php - 新密码提交处理器

// 1. 数据库连接配置 (与之前保持一致)
// ********** 请确保这里的配置与之前的文件 (forgot_password_handler.php) 一致 **********
$host = 'localhost';
$db   = 'bakeryhouse'; // 你的数据库名称
$user = 'root'; 
$pass = ''; 
// **********************************************

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 数据库连接失败，重定向到登录页面并显示通用错误
    header("Location: ../User_Login.php?error=" . urlencode("System error during password update."));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // 如果缺少 Token，视为无效请求
    if (empty($token)) {
        header("Location: ../forgot_password.php?message=" . urlencode("Invalid request parameters or missing token."));
        exit;
    }

    // 2. 检查密码是否匹配且符合最低要求
    if ($password !== $confirm_password) {
        // 两次密码不一致，重定向回重置页面并带上 Token 和错误信息
        header("Location: ../resetpassword.php?token={$token}&error=" . urlencode("Passwords do not match."));
        exit;
    }

    // 简单的密码强度检查 (确保长度至少为 8 位)
    if (strlen($password) < 8) {
        header("Location: ../resetpassword.php?token={$token}&error=" . urlencode("Password must be at least 8 characters long."));
        exit;
    }
    
    // 3. 验证 Token 是否有效 (检查是否存在且未过期)
    // created_at 存储的是过期时间
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND created_at > NOW()");
    $stmt->execute([$token]);
    $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset_request) {
        $email = $reset_request['email'];
        
        // 4. 对新密码进行安全的哈希处理
        // 使用 PHP 推荐的 PASSWORD_BCRYPT 算法
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // 5. 更新用户的密码并清理 Token (使用事务确保操作的原子性)
        $pdo->beginTransaction();
        try {
            // 更新 user_db 表中的密码
            $update_stmt = $pdo->prepare("UPDATE user_db SET password = ? WHERE email = ?");
            $update_stmt->execute([$hashed_password, $email]);

            // 清理 Token：删除 password_resets 表中的记录 (使其无法被再次使用)
            $delete_stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
            $delete_stmt->execute([$token]);
            
            $pdo->commit();
            
            // 6. 完成并重定向到登录页面
            header("Location: ../User_Login.php?message=" . urlencode("Success! Your password has been reset. Please log in with your new password."));
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            // 数据库操作失败，返回一个通用错误
            header("Location: ../forgot_password.php?message=" . urlencode("System error during password update. Please try again."));
            exit;
        }

    } else {
        // Token 无效或已过期
        header("Location: ../forgot_password.php?message=" . urlencode("The reset link is invalid or has expired. Please request a new one."));
        exit;
    }
} else {
    // 非 POST 请求，直接跳转到忘记密码页面
    header("Location: ../forgot_password.php");
    exit;
}
?>