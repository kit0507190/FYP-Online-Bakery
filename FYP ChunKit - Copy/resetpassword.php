<?php
// resetpassword.php
session_start();

/**
 * 原理阶段 1：安全关卡 (Session 门票)
 * 只要用户没关浏览器，这个 Session 就会记录他已经通过了验证码校验。
 */
if (!isset($_SESSION['code_verified']) || $_SESSION['code_verified'] !== true) {
    header("Location: forgotpassword.php?message=" . urlencode("Please verify your code first."));
    exit;
}

// 数据库连接
$host = 'localhost';
$db   = 'bakeryhouse'; 
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('System error: Database connection failed.');
}

$error = '';
$valid_token = false;
$email = '';

/**
 * 原理阶段 2：状态保持
 * 当用户从 submit 页面因为密码不合格被跳回来时，URL 会带着 token。
 * 我们利用这个 token 重新维持页面状态。
 */
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // 从数据库检查这个验证码是否依然有效（没过期且存在）
    $stmt = $pdo->prepare("SELECT email, created_at FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset_request) {
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $expiry_time = strtotime($reset_request['created_at']);
        $current_time = time();
        
        if ($current_time <= $expiry_time) { 
            $valid_token = true;
            $email = $reset_request['email']; 
        } else {
            $error = 'Your session has expired. Please request a new code.';
        }
    } else {
        $error = 'Invalid request. Please try the process again.';
    }
} else {
    $error = 'Security token missing.';
}

// 接收来自 reset_password_submit.php 的原地纠错提示
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set New Password - Bakery House</title>
    <style>
        /* 保持你的 Bakery House 风格 */
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #fdf6f0 0%, #f8e8d8 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
        .container { background-color: white; border-radius: 20px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); padding: 40px; max-width: 450px; width: 100%; text-align: center; }
        h1 { color: #5a3921; margin-bottom: 25px; font-size: 28px; }
        .account-info { background: #fdf6f0; padding: 10px; border-radius: 10px; color: #5a3921; margin-bottom: 25px; font-size: 14px; border: 1px dashed #d4a76a; }
        .form-group { margin-bottom: 20px; text-align: left; position: relative; }
        .form-group label { display: block; margin-bottom: 8px; color: #5a3921; font-weight: 600; }
        .form-group input { width: 100%; padding: 12px 15px; border: 2px solid #e1e1e1; border-radius: 10px; font-size: 16px; box-sizing: border-box; transition: border-color 0.3s; }
        .form-group input:focus { border-color: #d4a76a; outline: none; }
        
        .password-toggle { position: absolute; right: 15px; top: 40px; color: #d4a76a; cursor: pointer; font-size: 13px; font-weight: bold; }

        .btn { width: 100%; padding: 14px; background: #d4a76a; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.3s; margin-top: 10px; }
        .btn:hover { background: #c2955a; }
        
        /* 错误消息美化：原地纠错时非常醒目 */
        .error-message { color: #e74c3c; background: #f8d7da; padding: 12px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-size: 14px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Set New Password</h1>
        
        <?php if ($error): ?>
            <div class="error-message">⚠️ <?= $error ?></div>
        <?php endif; ?>

        <?php if ($valid_token): ?>
            <div class="account-info">
                Resetting password for:<br><strong><?= htmlspecialchars($email) ?></strong>
            </div>
            
            <form action="reset_password_submit.php" method="POST" id="resetForm">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" id="password" name="password" placeholder="Min. 8 characters" required>
                    <span class="password-toggle" onclick="togglePass('password', this)">Show</span>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
                    <span class="password-toggle" onclick="togglePass('confirm_password', this)">Show</span>
                </div>
                
                <button type="submit" class="btn">Update Password</button>
            </form>
        <?php else: ?>
            <p style="margin-top:20px;"><a href="forgotpassword.php" style="color: #d4a76a; font-weight:bold;">Return and get a new code</a></p>
        <?php endif; ?>
    </div>

    <script>
        function togglePass(inputId, toggleText) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                toggleText.textContent = "Hide";
            } else {
                input.type = "password";
                toggleText.textContent = "Show";
            }
        }

        // 前端初步检查：在数据还没发给后端前先挡住明显的错误
        document.getElementById('resetForm').onsubmit = function(e) {
            const p = document.getElementById('password').value;
            const cp = document.getElementById('confirm_password').value;
            if (p.length < 8) {
                alert("Password must be at least 8 characters long.");
                e.preventDefault();
            } else if (p !== cp) {
                alert("Passwords do not match.");
                e.preventDefault();
            }
        };
    </script>
</body>
</html>