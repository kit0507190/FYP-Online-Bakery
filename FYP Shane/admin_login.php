<?php

require_once 'admin_config.php';


if (isAdminLoggedIn()) {
    redirectToAdminDashboard();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = cleanAdminInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        try {
            $pdo = getAdminPDOConnection();
            if (!$pdo) {
                throw new Exception("Database connection failed");
            }

            // Fetch user (by username OR email)
            $stmt = $pdo->prepare("
                SELECT id, username, email, password, role, status
                FROM admins
                WHERE (username = :val OR email = :val)
                LIMIT 1
            ");
            $stmt->execute([':val' => $username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            $loginSuccess = false;

            // Only verify password if user exists AND is active
            if ($admin && $admin['status'] === 'active') {
                if (verifyAdminPassword($password, $admin['password'])) {
                    $loginSuccess = true;
                }
            }

            if ($loginSuccess) {
               

                // Update last login time
                $pdo->prepare("
                    UPDATE admins
                    SET last_login = NOW()
                    WHERE id = :id
                ")->execute([':id' => $admin['id']]);

                
                $_SESSION['admin_id']            = $admin['id'];
                $_SESSION['admin_username']      = $admin['username'];
                $_SESSION['admin_email']         = $admin['email'];
                $_SESSION['admin_role']          = $admin['role'];
                $_SESSION['admin_last_activity'] = time();

                redirectToAdminDashboard();
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            error_log("Admin Login PDO Error: " . $e->getMessage());
            $error = "Database error. Please try again later.";
        } catch (Exception $e) {
            error_log("Admin Login General Error: " . $e->getMessage());
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #5a3921 0%, #7a5537 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-header h1 { font-size: 28px; margin-bottom: 10px; font-weight: 600; }
        .login-header p { font-size: 14px; opacity: 0.9; }
        .logo {
            width: 80px; height: 80px; margin: 0 auto 20px;
            background: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 32px; color: #5a3921; font-weight: bold;
        }
        .login-form { padding: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; margin-bottom: 8px;
            font-weight: 500; color: #333; font-size: 14px;
        }
        .form-input {
            width: 100%; padding: 12px 15px;
            border: 1px solid #ddd; border-radius: 8px;
            font-size: 16px; transition: all 0.3s;
        }
        .form-input:focus {
            outline: none; border-color: #5a3921;
            box-shadow: 0 0 0 3px rgba(90,57,33,0.1);
        }
        .error-message {
            background: #f8d7da; color: #721c24;
            padding: 12px; border-radius: 8px; margin-bottom: 20px;
            font-size: 14px; border-left: 4px solid #dc3545;
            display: flex; align-items: center; gap: 10px;
        }
        .login-btn {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, #5a3921 0%, #7a5537 100%);
            color: white; border: none; border-radius: 8px;
            font-size: 16px; font-weight: 600; cursor: pointer;
            transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(90,57,33,0.2);
        }
        .login-footer {
            text-align: center; padding: 20px 30px;
            border-top: 1px solid #eee; font-size: 14px; color: #666;
        }
        .login-footer a { color: #5a3921; text-decoration: none; font-weight: 500; }
        .login-footer a:hover { text-decoration: underline; }
        .password-wrapper { position: relative; }
        .toggle-password {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #666; cursor: pointer; padding: 5px; font-size: 16px;
        }
        .toggle-password:hover { color: #333; }
        .password-wrapper input { padding-right: 40px; }
        @media (max-width: 480px) {
            .login-container { max-width: 100%; }
            .login-header, .login-form { padding: 20px; }
        }
        .fa-spinner { animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo"><i class="fas fa-crown"></i></div>
            <h1>Admin Login</h1>
            <p>Bakery House Management System</p>
        </div>

        <?php if ($error): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <form action="" method="POST" class="login-form" id="loginForm">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" class="form-input"
                       placeholder="Enter your username or email"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       required autofocus>
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

        <div class="login-footer">
            <p style="margin-top:10px; font-size:12px; color:#999;">
                Authorized admin access only
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.querySelector('.toggle-password i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        document.getElementById('loginForm')?.addEventListener('submit', function() {
            const btn = document.getElementById('loginButton');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
        });
    </script>
</body>
</html>