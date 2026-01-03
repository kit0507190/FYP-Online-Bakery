<?php
require_once 'config.php';

// 初始化变量，防止页面第一次加载时出现 "Undefined variable" 警告，并用于保留用户输入
$errors = [];
$name = ""; 
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取表单数据并过滤多余空格
    $name     = trim($_POST["name"] ?? '');
    $email    = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm  = $_POST["confirmPassword"] ?? '';
    $agree    = isset($_POST["agreeTerms"]);

    // ====== 服务器端验证逻辑 (Server-side Validation) ======

    // 1. 验证姓名长度
    if (empty($name) || strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters.";
    }

    // 2. 验证 Email 格式及真实性
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address format.";
    } else {
        // 提取域名并检查该域名的 MX 记录（邮件交换记录），确保域名真实存在
        $domain = substr(strrchr($email, "@"), 1);
        if (!checkdnsrr($domain, "MX")) {
            $errors[] = "The email domain '@$domain' does not exist. Please use a real email.";
        }
    }

    // 3. 验证密码强度 (至少8位且包含字母和数字)
    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must be 8+ chars with letters & numbers.";
    }

    // 4. 验证两次密码是否一致
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // 5. 验证是否勾选条款
    if (!$agree) {
        $errors[] = "You must agree to the terms and privacy policy.";
    }

    // 6. 检查数据库中 Email 是否已重复
    if (empty($errors)) {
        try {
            $check = $pdo->prepare("SELECT id FROM user_db WHERE email = ?");
            $check->execute([$email]);
            
            if ($check->rowCount() > 0) {
                $errors[] = "This email is already registered.";
            } else {
                // 验证全部通过，对密码进行加密并存入数据库
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO user_db (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed]);

                // 注册成功，跳转到登录页
                header("Location: User_Login.php?registered=1");
                exit();
            }
        } catch (Exception $e) {
            $errors[] = "Registration failed. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery House - Registration</title>
    <link rel="stylesheet" href="User_Register.css">
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <div class="logo-content">
                <div class="logo-image">
                    <img src="Bakery House Logo.png" alt="Bakery House Logo">
                </div>
                <h2 class="logo-text">Bakery House</h2>
                <p class="tagline">Sweet & Delicious</p>
                <div class="slogan-box">
                    <p class="slogan">The Warmth of Home, The Taste of Baking.</p>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-header">
                <h1>Create An Account</h1>
                <p>Join our Bakery House community!</p>
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

            <form action="" method="POST" id="registerForm">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" required placeholder="8+ chars (letters & numbers)">
                    <span class="password-toggle" id="togglePassword">Show</span>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" required placeholder="Confirm your password">
                    <span class="password-toggle" id="toggleConfirmPassword">Show</span>
                </div>

                <div class="terms-group">
                    <input type="checkbox" id="agreeTerms" name="agreeTerms" <?= isset($_POST['agreeTerms']) ? 'checked' : '' ?> required>
                    <label for="agreeTerms">I agree to the <a href="#" id="termsLink">Terms of Service</a> and <a href="#" id="privacyLink">Privacy Policy</a></label>
                </div>

                <button type="submit" class="btn-submit">Create Account</button>

                <div class="login-link">
                    Already have an account? <a href="User_Login.php">Sign In</a>
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

    <div id="policyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="policyTitle">Terms of Service</h2>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body" id="policyBody"></div>
            <div class="modal-footer">
                <button class="btn-close-modal" id="modalCloseBtn">Understood</button>
            </div>
        </div>
    </div>

    <script src="User_Register.js"></script>
</body>
</html>