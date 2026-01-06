<?php
// User_Register.php —— 完全匹配你的数据库：bakery_house + 表名 user_db

require_once 'db_connect.php';

// 检查数据库连接
if ($conn->connect_error) {
    die("数据库连接失败：" . $conn->connect_error);
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirmPassword"];
    $agree    = isset($_POST["agreeTerms"]);

    // ====== 表单验证 ======
    if (empty($name) || strlen($name) < 2)          
        $errors[] = "Please fill in your full name";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        $errors[] = "Please enter a valid email address.";
    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) 
        $errors[] = "Password must be at least 8 characters and contain both letters and numbers.";
    if ($password !== $confirm)                     
        $errors[] = "The two passwords do not match.";
    if (!$agree)                                     
        $errors[] = "You must agree to the Terms of Service and Privacy Policy.";

    // ====== 全部正确才进入注册流程 ======
    if (empty($errors)) {
        
        // 检查邮箱是否已被注册
        $check = $conn->prepare("SELECT id FROM user_db WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "This email address has already been registered.";
        } else {
            // 加密密码 + 存入数据库
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO user_db (name, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $name, $email, $hashed);

            if ($insert->execute()) {
                // 注册成功 → 直接跳转登录页
                header("Location: User_Login.php");
                exit;
            } else {
                $errors[] = "Registration failed, please try again later.";
            }
            $insert->close();
        }
        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
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
                <div class="logo-text">Bakery House</div>
                <div class="tagline">Sweet & Delicious</div>
                <div class="slogan">The Warmth of Home, The Taste of Baking.</div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-header">
                <h1>Create An Account</h1>
                <p>Join our Bakery House community !!!</p>
            </div>

            <!-- 错误提示 -->
            <?php if (!empty($errors)): ?>
                <div style="background:#f8d7da;color:#721c24;padding:15px;border-radius:12px;margin:20px 0;">
                    <ul style="margin:10px 0;padding-left:22px;">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- 注册表单 -->
            <form action="" method="POST" id="registerForm">
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" class="form-control" placeholder="Enter your full name">
                    <span class="error-message">Please enter a valid name</span>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" class="form-control" placeholder="Enter your email address">
                    <span class="error-message">Please enter a valid email address</span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Create a password">
                    <span class="password-toggle" id="togglePassword">Show</span>
                    <span class="error-message">Password must be at least 8 characters with letters and numbers</span>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Confirm your password">
                    <span class="error-message">Passwords do not match</span>
                </div>

                <div class="terms">
                    <input type="checkbox" id="agreeTerms" name="agreeTerms" <?= $agree ?? false ? 'checked' : '' ?>>
                    <label for="agreeTerms">I agree to the 
                        <a href="#" class="terms-link" data-type="terms">Terms of Service</a> and 
                        <a href="#" class="terms-link" data-type="privacy">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn">Create Account</button>

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

    <!-- 条款弹窗 -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Terms of Service</h3>
                <span class="modal-close">×</span>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer">
                <button class="modal-btn" id="closeModal">I Understand ♡</button>
            </div>
            <div class="cake-decoration" style="justify-content:center;margin-top:25px;">
                <div class="cake-piece"></div>
                <div class="cake-piece"></div>
                <div class="cake-piece"></div>
                <div class="cake-piece"></div>
            </div>
        </div>
    </div>

    <script src="User_Register.js"></script>
</body>
</html>