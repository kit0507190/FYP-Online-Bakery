<?php
// admin/login.php
require_once 'admin_config.php';

// If already logged in, redirect to dashboard
if (isAdminLoggedIn()) {
    redirectToAdminDashboard();
}

// Initialize error variable
$error = '';

// Check if POST request (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and clean inputs
    $username = cleanAdminInput($_POST['username'] ?? '');
    $password = cleanAdminInput($_POST['password'] ?? '');
    
    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields!';
    } else {
        // Connect to database
        $pdo = getAdminPDOConnection();
        
        // Prepare query
        $sql = "SELECT id, username, password, role, status, login_attempts 
                FROM admins 
                WHERE (username = :username OR email = :email) 
                AND status = 'active'
                LIMIT 1";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $username
        ]);
        
        $admin = $stmt->fetch();
        
        if ($admin) {
            // Check login attempts
            if ($admin['login_attempts'] >= ADMIN_MAX_LOGIN_ATTEMPTS) {
                $error = 'Too many login attempts! Please try again later.';
            }
            // Verify password
            elseif (verifyAdminPassword($password, $admin['password'])) {
                // Login successful
                // Reset login attempts
                $update_sql = "UPDATE admins SET 
                              login_attempts = 0, 
                              last_login = NOW() 
                              WHERE id = :id";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute([':id' => $admin['id']]);
                
                // Set session variables
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_role'] = $admin['role'];
                $_SESSION['admin_last_activity'] = time();
                
                // Redirect to dashboard
                redirectToAdminDashboard();
                
            } else {
                // Wrong password
                $update_sql = "UPDATE admins SET 
                              login_attempts = login_attempts + 1 
                              WHERE id = :id";
                $update_stmt = $pdo->prepare($update_sql);
                $update_stmt->execute([':id' => $admin['id']]);
                
                $error = 'Incorrect username or password!';
            }
        } else {
            $error = 'Incorrect username or password!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #667eea;
            outline: none;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
        }
        
        .error-message {
            background: #fee;
            border: 1px solid #f99;
            color: #c00;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-footer a {
            color: #667eea;
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>🔐 Admin Login</h2>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- Note: Form submits to login.php itself -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Enter username or email"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter password">
                </div>
                
                <button type="submit" class="btn-login">Login</button>
            </form>
            
            <div class="login-footer">
                <p>Back to <a href="../index.php">Main Site</a></p>
                <p style="margin-top: 10px; font-size: 12px; color: #999;">
                    Authorized admin access only
                </p>
            </div>
        </div>
    </div>
</body>
</html>