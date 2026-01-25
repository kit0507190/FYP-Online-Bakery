<?php
/**
 * User_Login.php - 用户登录页面
 */
session_start();
require_once 'config.php';

// 初始化变量
$errors = [];
$email = $_POST['email'] ?? "";

// 处理登录请求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $remember = isset($_POST["rememberMe"]);

    // 后端基础验证
    if (empty($email)) {
        $errors[] = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (empty($password)) {
        $errors[] = "Please enter your password.";
    }

    // 如果没有错误，尝试从数据库匹配
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id, name, email, password FROM user_db WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // 登录成功 - 设置 Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                // 记住我功能
                if ($remember) {
                    setcookie('user_email', $email, time() + (30 * 24 * 60 * 60), '/');
                } else {
                    setcookie('user_email', '', time() - 3600, '/');
                }

                header("Location: mainpage.php");
                exit();
            } else {
                $errors[] = "Invalid email or password.";
            }
        } catch (Exception $e) {
            $errors[] = "Login failed. Please try again later.";
        }
    }
}

$registered = isset($_GET['registered']) && $_GET['registered'] == '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery House - Sign In</title>
    <link rel="stylesheet" href="User_Login.css">
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <div class="logo-content">
                <a href="index.php" class="logo-image-link">
                    <div class="logo-image">
                        <img src="Bakery House Logo.png" alt="Bakery House Logo">
                    </div>
                </a>
                <h2 class="logo-text">Bakery House</h2>
                <p class="tagline">Sweet & Delicious</p>
                <div class="slogan-box">
                    <p class="slogan">The Warmth of Home, The Taste of Baking.</p>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-header">
                <h1>Welcome Back!</h1>
                <p>Log in to continue your sweet journey</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-container">
                    <ul class="error-list">
                        <?php foreach ($errors as $e): ?>
                            <li><span class="error-icon">⚠️</span> <?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="" method="POST" id="loginForm">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" required placeholder="Enter your password">
                    <span class="password-toggle" id="togglePassword">Show</span>
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" id="rememberMe" name="rememberMe"> Remember me
                    </label>
                    <a href="forgotpassword.php" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>

                <div class="login-link">
                    Don't have an account? <a href="User_Register.php" class="create-account-link">Create Account</a>
                </div>

                <div class="cake-decoration">
                    <div class="cake-piece"></div>
                    <div class="cake-piece"></div>
                    <div class="cake-piece"></div>
                    <div class="cake-piece"></div>
                </div>
            </form>
        </div>
    </div>

    <script>const isRegistered = <?= json_encode($registered) ?>;</script>
    <script src="User_Login.js"></script>
</body>
</html>