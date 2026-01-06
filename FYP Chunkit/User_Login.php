<?php
session_start();
require_once 'config.php';

$errors = [];
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $remember = isset($_POST["rememberMe"]);

    // Validation
    if (empty($email)) {
        $errors[] = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (empty($password)) {
        $errors[] = "Please enter your password.";
    }

    // If no errors, proceed with login
    if (empty($errors)) {
        try {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT id, name, email, password FROM user_db WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Login successful - set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                // Debug logging
                error_log("LOGIN SUCCESS - User ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}");

                // Set cookie if remember me is checked
                if ($remember) {
                    setcookie('user_email', $email, time() + (30 * 24 * 60 * 60), '/');
                }

                // Redirect to main page
                header("Location: mainpage.php");
                exit();
            } else {
                $errors[] = "Invalid email or password.";
            }
        } catch (Exception $e) {
            $errors[] = "Login failed. Please try again.";
            error_log("LOGIN ERROR: " . $e->getMessage());
        }
    }
}

// Check if redirected from registration
$registered = isset($_GET['registered']) && $_GET['registered'] == '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery House - Sign In</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fdf6f0 0%, #f8e8d8 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            min-height: 550px;
        }

        .logo-section {
            flex: 1;
            background: linear-gradient(to bottom right, #d4a574, #b8864e);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .logo-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }

        .logo-content {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 100%;
        }

        .logo-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: white;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 5px solid rgba(255, 255, 255, 0.3);
        }

        .logo-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-text {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }

        .tagline {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .slogan {
            font-size: 20px;
            font-weight: 600;
            margin-top: 40px;
            font-style: italic;
            text-align: center;
            line-height: 1.4;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-header h1 {
            color: #5a3921;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .form-header p {
            color: #666;
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #5a3921;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #d4a76a;
            box-shadow: 0 0 0 3px rgba(212, 167, 106, 0.2);
        }
        
        .form-group input.error {
            border-color: #e74c3c;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 40px;
            color: #d4a76a;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        
        .error-message {
            display: none;
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            color: #5a3921;
            font-size: 14px;
            cursor: pointer;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .forgot-password {
            color: #d4a76a;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: #d4a76a;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-bottom: 20px;
        }
        
        .btn:hover {
            background: #c2955a;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .login-link a {
            color: #d4a76a;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .error-container {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        .error-container ul {
            margin: 8px 0;
            padding-left: 20px;
        }
        
        .error-container li {
            margin-bottom: 5px;
        }

        .cake-decoration {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
        }

        .cake-piece {
            width: 20px;
            height: 10px;
            background: #d4a574;
            border-radius: 50% 50% 0 0;
        }

        .cake-piece:nth-child(2) {
            background: #b8864e;
        }

        .cake-piece:nth-child(3) {
            background: #d4a574;
        }

        .cake-piece:nth-child(4) {
            background: #b8864e;
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .logo-section {
                padding: 30px 20px;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .remember-forgot {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
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
                <h1>Welcome Back!</h1>
                <p>Log in to continue your sweet journey</p>
            </div>

            <!-- Show errors -->
            <?php if (!empty($errors)): ?>
                <div class="error-container">
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required placeholder="Enter your email">
                    <span class="error-message">Please enter a valid email address</span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Enter your password">
                    <span class="password-toggle" id="togglePassword">Show</span>
                    <span class="error-message">Password cannot be empty</span>
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" id="rememberMe" name="rememberMe">
                        Remember me
                    </label>
                    <a href="forgotpassword.php" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="btn">Sign In</button>

                <div class="login-link">
                    Don't have an account? <a href="User_register.php">Create Account</a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');

            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.textContent = type === 'password' ? 'Show' : 'Hide';
            });

            // Real-time validation
            function validateField(field, validationFn) {
                const formGroup = field.parentElement;
                const isValid = validationFn(field.value);

                if (isValid) {
                    formGroup.classList.remove('error');
                } else {
                    formGroup.classList.add('error');
                }
                return isValid;
            }

            const validateEmail = email => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            const validatePassword = pass => pass.trim() !== '';

            emailInput.addEventListener('blur', () => validateField(emailInput, validateEmail));
            passwordInput.addEventListener('blur', () => validateField(passwordInput, validatePassword));

            // Form submission validation
            form.addEventListener('submit', function(e) {
                const isEmailValid = validateField(emailInput, validateEmail);
                const isPasswordValid = validateField(passwordInput, validatePassword);

                if (!isEmailValid || !isPasswordValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields correctly');
                }
            });

            // Clear errors when user starts typing
            emailInput.addEventListener('input', function() {
                this.classList.remove('error');
            });

            passwordInput.addEventListener('input', function() {
                this.classList.remove('error');
            });

            <?php if ($registered): ?>
            alert('Registration successful! Please log in with your credentials.');
            <?php endif; ?>
        });
    </script>
</body>
</html>