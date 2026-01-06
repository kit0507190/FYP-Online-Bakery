<?php
// reset_password_page.php - 重置密码页面

// 1. 数据库连接配置
// ********** 请确保这里的配置与之前的一致 **********
$host = 'localhost';
$db   = 'bakeryhouse'; // 你的数据库名称
$user = 'root'; 
$pass = ''; 
// **********************************************

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 数据库连接失败
    $error = 'System error: Database connection failed.';
}


$error = '';
$valid_token = false;

// 检查 URL 中是否包含 token 参数
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 2. 验证 Token
    $stmt = $pdo->prepare("SELECT email, created_at FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset_request) {
        $expiry_time = strtotime($reset_request['created_at']);
        $current_time = time();
        
        // Token 有效期检查（created_at 字段存储的是 Token 过期的时间点）
        if ($current_time <= $expiry_time) { 
            // Token 有效
            $valid_token = true;
            $email = $reset_request['email']; // 获取用户的邮箱
        } else {
            // Token 过期
            $error = 'The link has expired. Please request a new password reset link.';
        }
    } else {
        // Token 不存在或已被使用
        $error = 'Invalid link or the link has already been used.';
    }
} else {
    $error = 'Missing reset credential (Token).';
}

// 检查是否有提交错误信息重定向回来
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Bakery House</title>
    <link rel="stylesheet" href="styles.css"> <style>
        /* 保持与 User_Login.php 相似的样式结构 */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #fdf6f0 0%, #f8e8d8 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
        .container { background-color: white; border-radius: 20px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); padding: 40px; max-width: 500px; width: 100%; text-align: center; }
        h1 { color: #5a3921; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; color: #5a3921; font-weight: 500; }
        .form-group input { width: 100%; padding: 12px 15px; border: 2px solid #e1e1e1; border-radius: 10px; font-size: 16px; }
        .btn { width: 100%; padding: 14px; background: #d4a76a; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.3s ease; }
        .btn:hover { background: #c2955a; }
        .error-message { color: #e74c3c; background: #f8d7da; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; text-align: left; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Set New Password</h1>
        
        <?php if ($error): ?>
            <div class="error-message">
                <?= $error ?>
            </div>
            <p><a href="../forgot_password.php">Click here to request a new link</a></p>

        <?php elseif ($valid_token): ?>
            <p style="margin-bottom: 20px; color: #5a3921;">Account: <strong><?= htmlspecialchars($email) ?></strong></p>
            
            <form action="reset_password_submit.php" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div class="form-group">
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn">Change Password</button>
            </form>
        
        <?php endif; ?>

    </div>
</body>
</html>