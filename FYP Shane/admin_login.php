<?php
// admin/admin_login.php

// 使用我们独立的配置系统
require_once 'admin_config.php';

// 如果已经登录，重定向到管理面板
if (isAdminLoggedIn()) {
    redirectToAdminDashboard();
}

$error = '';  // 存储错误消息

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. 获取并清理表单数据
    $username = cleanAdminInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // 2. 基本验证
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        try {
            // 3. 获取数据库连接
            $pdo = getAdminPDOConnection();
            
            if (!$pdo) {
                throw new Exception("Database connection failed");
            }
            
            // 4. 查询 admins 表（使用我们独立的表）
            $query = "SELECT id, username, email, password, role, status, login_attempts 
                     FROM admins 
                     WHERE (username = :username OR email = :email) 
                     AND status = 'active'
                     LIMIT 1";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':username' => $username,
                ':email' => $username
            ]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // 5. 验证管理员是否存在
            if ($admin) {
                // 6. 检查登录尝试次数
                if ($admin['login_attempts'] >= ADMIN_MAX_LOGIN_ATTEMPTS) {
                    $error = "Too many login attempts. Please try again later.";
                }
                // 7. 验证密码
                elseif (verifyAdminPassword($password, $admin['password'])) {
                    // 8. 登录成功 - 重置登录尝试次数
                    $update_sql = "UPDATE admins SET 
                                  login_attempts = 0, 
                                  last_login = NOW() 
                                  WHERE id = :id";
                    $update_stmt = $pdo->prepare($update_sql);
                    $update_stmt->execute([':id' => $admin['id']]);
                    
                    // 9. 设置会话（使用我们的标准session变量名）
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['admin_role'] = $admin['role'];
                    $_SESSION['admin_last_activity'] = time();
                    
                    // 10. 重定向到管理面板
                    redirectToAdminDashboard();
                    
                } else {
                    // 密码错误 - 增加登录尝试次数
                    $update_sql = "UPDATE admins SET 
                                  login_attempts = login_attempts + 1 
                                  WHERE id = :id";
                    $update_stmt = $pdo->prepare($update_sql);
                    $update_stmt->execute([':id' => $admin['id']]);
                    
                    $error = "Invalid username or password.";
                }
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            error_log("Admin Login Error: " . $e->getMessage());
            $error = "Database error. Please try again later.";
        } catch (Exception $e) {
            error_log("Admin Login Error: " . $e->getMessage());
            $error = "System error. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bakery House</title>
    <style>
        /* 1. 基础样式 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        /* 2. 登录容器 */
        .login-container {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        /* 3. 头部样式 */
        .login-header {
            background: linear-gradient(135deg, #5a3921 0%, #7a5537 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .login-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #5a3921;
            font-weight: bold;
        }
        
        /* 4. 表单样式 */
        .login-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #5a3921;
            box-shadow: 0 0 0 3px rgba(90, 57, 33, 0.1);
        }
        
        /* 5. 错误消息 */
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #dc3545;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error-message i {
            font-size: 18px;
        }
        
        /* 6. 登录按钮 */
        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #5a3921 0%, #7a5537 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(90, 57, 33, 0.2);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        /* 7. 底部链接 */
        .login-footer {
            text-align: center;
            padding: 20px 30px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
        }
        
        .login-footer a {
            color: #5a3921;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        /* 8. 响应式设计 */
        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
            }
            
            .login-header {
                padding: 20px;
            }
            
            .login-form {
                padding: 20px;
            }
        }
        
        /* 9. 加载状态 */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* 10. 密码显示/隐藏按钮 */
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 5px;
            font-size: 16px;
        }
        
        .toggle-password:hover {
            color: #333;
        }
        
        .password-wrapper input {
            padding-right: 40px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <!-- 头部 -->
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-crown"></i>
            </div>
            <h1>Admin Login</h1>
            <p>Bakery House Management System</p>
        </div>
        
        <!-- 错误消息 -->
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>
        
        <!-- 登录表单 -->
        <form action="" method="POST" class="login-form" id="loginForm">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" class="form-input" 
                       placeholder="Enter your username or email"
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-input" 
                           placeholder="Enter your password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="login-btn" id="loginButton">
                <i class="fas fa-sign-in-alt"></i> Login to Dashboard
            </button>
        </form>
        
        <!-- 底部链接 -->
        <div class="login-footer">
            <a href="../index.php">← Back to Main Site</a>
            <p style="margin-top: 10px; font-size: 12px; color: #999;">
                Authorized admin access only
            </p>
        </div>
    </div>

    <script>
        // 页面加载完成后执行
        document.addEventListener('DOMContentLoaded', function() {
            // 1. 表单提交处理
            const form = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            
            if (form) {
                form.addEventListener('submit', function() {
                    // 2. 显示加载状态
                    loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
                    loginButton.disabled = true;
                });
            }
            
            // 3. 自动聚焦到用户名输入框
            const usernameInput = document.getElementById('username');
            if (usernameInput) {
                usernameInput.focus();
            }
        });
        
        // 4. 切换密码显示/隐藏
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.toggle-password');
            const icon = toggleButton.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.className = 'fas fa-eye-slash';
                toggleButton.title = 'Hide password';
            } else {
                passwordInput.type = 'password';
                icon.className = 'fas fa-eye';
                toggleButton.title = 'Show password';
            }
        }
        
        // 5. 按Enter键提交表单
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                const activeElement = document.activeElement;
                if (activeElement.tagName === 'INPUT') {
                    event.preventDefault();
                    document.getElementById('loginForm').submit();
                }
            }
        });
    </script>
</body>
</html>