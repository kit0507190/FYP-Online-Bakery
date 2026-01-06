<?php
require_once 'config.php';

$errors = [];
$name = $email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["name"] ?? '');
    $email    = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm  = $_POST["confirmPassword"] ?? '';
    $agree    = isset($_POST["agreeTerms"]);

    // ====== Validation ======
    if (empty($name) || strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must be 8+ chars with letters & numbers.";
    }
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }
    if (!$agree) {
        $errors[] = "You must agree to the terms.";
    }

    // ====== If no errors → register ======
    if (empty($errors)) {
        try {
            // Check if email exists
            $check = $pdo->prepare("SELECT id FROM user_db WHERE email = ?");
            $check->execute([$email]);
            
            if ($check->rowCount() > 0) {
                $errors[] = "This email is already registered.";
            } else {
                // Insert new user
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO user_db (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed]);

                // SUCCESS!
                header("Location: User_Login.php?registered=1");
                exit();
            }
        } catch (Exception $e) {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery House - Registration</title>
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
        
        .terms {
            display: flex;
            align-items: flex-start;
            margin: 25px 0;
            gap: 10px;
        }
        
        .terms input[type="checkbox"] {
            margin-top: 3px;
        }
        
        .terms label {
            color: #666;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .terms-link {
            color: #d4a76a;
            text-decoration: none;
            font-weight: 500;
        }
        
        .terms-link:hover {
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
        }
        
        .btn:hover {
            background: #c2955a;
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
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
        
        .validation-hint {
            display: block;
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .modal-header h2 {
            color: #5a3921;
            margin: 0;
        }
        
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: #5a3921;
        }
        
        .modal-body {
            line-height: 1.6;
            color: #333;
        }
        
        .modal-body h3 {
            color: #5a3921;
            margin: 20px 0 10px 0;
        }
        
        .modal-body p {
            margin-bottom: 15px;
        }
        
        .modal-body ul {
            margin-left: 20px;
            margin-bottom: 15px;
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
                <h1>Create An Account</h1>
                <p>Join our Bakery House community!</p>
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

            <form action="" method="POST" id="registerForm">
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" name="name" id="fullName" value="<?= htmlspecialchars($name) ?>" required placeholder="Enter your full name">
                    <span class="validation-hint">Please fill in your full name</span>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required placeholder="Enter your email">
                    <span class="validation-hint">Please enter a valid email address</span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Create a password">
                    <span class="password-toggle" id="togglePassword">Show</span>
                    <span class="validation-hint">Password must be at least 8 characters with letters and numbers</span>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" required placeholder="Confirm your password">
                    <span class="validation-hint">Please confirm your password</span>
                </div>

                <div class="terms">
                    <input type="checkbox" id="agreeTerms" name="agreeTerms" required>
                    <label for="agreeTerms">I agree to the <a href="#" class="terms-link" id="termsLink">Terms of Service</a> and <a href="#" class="terms-link" id="privacyLink">Privacy Policy</a></label>
                </div>

                <button type="submit" class="btn">Create Account</button>

                <div class="login-link">
                    Already have an account? <a href="User_Login.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Terms of Service Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Terms of Service</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <h3>1. Acceptance of Terms</h3>
                <p>By accessing and using BakeryHouse services, you accept and agree to be bound by these Terms of Service.</p>
                
                <h3>2. Account Registration</h3>
                <p>To access certain features of our services, you may be required to register for an account. You agree to:</p>
                <ul>
                    <li>Provide accurate and complete information during registration</li>
                    <li>Maintain the security of your account credentials</li>
                    <li>Notify us immediately of any unauthorized use of your account</li>
                    <li>Accept responsibility for all activities that occur under your account</li>
                </ul>
                
                <h3>3. Ordering and Payment</h3>
                <p>When placing orders through our website:</p>
                <ul>
                    <li>All orders are subject to product availability</li>
                    <li>Prices are subject to change without notice</li>
                    <li>Payment must be completed at the time of ordering</li>
                </ul>
                
                <h3>4. User Conduct</h3>
                <p>You agree not to use our services for any illegal purpose or harass our staff or other customers.</p>
                
                <h3>5. Changes to Terms</h3>
                <p>We reserve the right to modify these Terms of Service at any time. Continued use of our services constitutes acceptance of the modified terms.</p>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Privacy Policy</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <h3>Information We Collect</h3>
                <p>We may collect the following types of information:</p>
                <ul>
                    <li><strong>Personal Information:</strong> Name, email address, phone number, and delivery address</li>
                    <li><strong>Payment Information:</strong> Credit card details and billing information</li>
                    <li><strong>Technical Information:</strong> IP address, browser type, device information, and cookies</li>
                </ul>
                
                <h3>How We Use Your Information</h3>
                <p>We use the information we collect for the following purposes:</p>
                <ul>
                    <li>To process and fulfill your orders</li>
                    <li>To communicate with you about your orders and account</li>
                    <li>To improve our products, services, and website</li>
                    <li>To send you promotional offers and updates (with your consent)</li>
                </ul>
                
                <h3>Data Security</h3>
                <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>
                
                <h3>Your Rights</h3>
                <p>You have the right to access, correct, or delete your personal information. To exercise these rights, please contact us.</p>
                
                <h3>Contact Information</h3>
                <p>If you have any questions about this Privacy Policy, please contact us at bakeryhouse@gmail.com</p>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });

        // Modal functionality
        const termsModal = document.getElementById('termsModal');
        const privacyModal = document.getElementById('privacyModal');
        const termsLink = document.getElementById('termsLink');
        const privacyLink = document.getElementById('privacyLink');
        const closeButtons = document.querySelectorAll('.close');

        termsLink.addEventListener('click', function(e) {
            e.preventDefault();
            termsModal.style.display = 'block';
        });

        privacyLink.addEventListener('click', function(e) {
            e.preventDefault();
            privacyModal.style.display = 'block';
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                termsModal.style.display = 'none';
                privacyModal.style.display = 'none';
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === termsModal) {
                termsModal.style.display = 'none';
            }
            if (e.target === privacyModal) {
                privacyModal.style.display = 'none';
            }
        });

        // Form validation with real-time feedback
        const form = document.getElementById('registerForm');
        const inputs = form.querySelectorAll('input[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                // Remove error styling when user starts typing
                this.classList.remove('error');
            });
        });

        function validateField(field) {
            const value = field.value.trim();
            
            if (!value) {
                field.classList.add('error');
                return false;
            }
            
            // Email validation
            if (field.type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    field.classList.add('error');
                    return false;
                }
            }
            
            // Password validation
            if (field.id === 'password') {
                if (value.length < 8 || !/[A-Za-z]/.test(value) || !/[0-9]/.test(value)) {
                    field.classList.add('error');
                    return false;
                }
            }
            
            // Confirm password validation
            if (field.id === 'confirmPassword') {
                const password = document.getElementById('password').value;
                if (value !== password) {
                    field.classList.add('error');
                    return false;
                }
            }
            
            field.classList.remove('error');
            return true;
        }

        // Form submission validation
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });
            
            const agreeTerms = document.getElementById('agreeTerms').checked;
            if (!agreeTerms) {
                isValid = false;
                alert('You must agree to the Terms of Service and Privacy Policy');
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly');
            }
        });
    </script>
</body>
</html>