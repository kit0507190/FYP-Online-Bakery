<?php
// 同样需要设置马来西亚时区，确保与数据库里的过期时间对比时没有时差
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

$host = 'localhost'; $db = 'bakeryhouse'; $user = 'root'; $pass = ''; 

// 只有当用户是通过 POST 提交，且 Session 里记录了邮箱时才处理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['reset_email'])) {
    $email = $_SESSION['reset_email'];
    $user_code = trim($_POST['code']); // 获取用户在网页上输入的 6 位码

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        
        // 获取当前马来西亚的时间，用于对比
        $current_time = date("Y-m-d H:i:s");

        // 查询数据库：
        // 1. Email 必须匹配
        // 2. Token (验证码) 必须匹配
        // 3. created_at (过期时间) 必须大于当前时间（表示还没过期）
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND token = ? AND created_at > ?");
        $stmt->execute([$email, $user_code, $current_time]);
        $reset = $stmt->fetch();

        if ($reset) {
            // --- 验证通过 ---
            // 在 Session 中打一个“通过”的标记，这样 resetpassword.php 才会允许访问
            $_SESSION['code_verified'] = true;
            // 带着验证码跳转到重置密码页
            header("Location: resetpassword.php?token=" . urlencode($user_code));
            exit;
        } else {
            // 验证失败或已过期
            header("Location: verify_code.php?error=" . urlencode("The verification code is incorrect or has expired."));
            exit;
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>