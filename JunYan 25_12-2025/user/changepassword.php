<?php
// changepassword.php - 修改密码页面
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 数据库连接
require_once 'config.php';

// 检查数据库连接
if (!isset($pdo)) {
    die("Database connection failed.");
}

$userId = $_SESSION['user_id'];
$errors = [];
$success = '';
$current_password = $new_password = $confirm_password = '';

// 从数据库获取用户基本信息用于显示
try {
    $query = "SELECT name, email FROM user_db WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $userName = htmlspecialchars($user['name']);
        $userEmail = htmlspecialchars($user['email']);
    } else {
        session_destroy();
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error fetching user data: " . $e->getMessage());
}

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // 验证必填字段
    if (empty($current_password)) {
        $errors[] = "Current password is required.";
    }
    if (empty($new_password)) {
        $errors[] = "New password is required.";
    }
    if (empty($confirm_password)) {
        $errors[] = "Please confirm your new password.";
    }
    
    // 验证密码长度
    if (strlen($new_password) < 6) {
        $errors[] = "New password must be at least 6 characters long.";
    }
    
    // 验证密码是否匹配
    if ($new_password !== $confirm_password) {
        $errors[] = "New passwords do not match.";
    }
    
    // 验证当前密码是否正确
    if (empty($errors)) {
        try {
            $passwordQuery = "SELECT password FROM user_db WHERE id = ?";
            $passwordStmt = $pdo->prepare($passwordQuery);
            $passwordStmt->execute([$userId]);
            $userData = $passwordStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($userData && password_verify($current_password, $userData['password'])) {
                // 验证新密码不能与旧密码相同
                if (password_verify($new_password, $userData['password'])) {
                    $errors[] = "New password must be different from current password.";
                } else {
                    // 更新密码
                    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE user_db SET password = ?, updated_at = NOW() WHERE id = ?";
                    $updateStmt = $pdo->prepare($updateQuery);
                    $updateStmt->execute([$hashedPassword, $userId]);
                    
                    $success = "Password changed successfully!";
                    
                    // 清空表单字段
                    $current_password = $new_password = $confirm_password = '';
                }
            } else {
                $errors[] = "Current password is incorrect.";
            }
        } catch (PDOException $e) {
            $errors[] = "Error updating password: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Bakery House</title>
    <link rel="stylesheet" href="editprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="mainpage.php" class="logo">
                    <img src="Bakery House Logo.png" alt="BakeryHouse">
                </a>
                <ul class="nav-links">
                    <li><a href="mainpage.php">Home</a></li>
                    <li><a href="menu.html">Menu</a></li>
                    <li><a href="about_us.html">About</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li class="cart-icon">
                        <span>🛒 Cart</span>
                        <span class="cart-count">0</span>
                    </li>
                    
                    <li class="user-menu">
                        <div class="user-icon" onclick="toggleDropdown()">
                            <?php echo strtoupper(substr($userName, 0, 1)); ?>
                        </div>
                        <div class="dropdown-menu" id="dropdownMenu">
                            <a href="profile.php">Profile</a>
                            <a href="logout.php">Log Out</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="message-container">
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (!empty($success)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
    </div>

    <main class="profile-page">
        <div class="profile-container">
            <div class="profile-header">
                <h1>Change Password</h1>
                <p>Update your account password securely</p>
            </div>

            <form action="changepassword.php" method="POST" class="edit-form" id="passwordForm">
                <!-- 用户信息显示 -->
                <div class="info-card">
                    <h2><i class="fas fa-user-circle"></i> Account Information</h2>
                    <div style="margin-bottom: 20px;">
                        <p><strong>Name:</strong> <?php echo $userName; ?></p>
                        <p><strong>Email:</strong> <?php echo $userEmail; ?></p>
                    </div>
                </div>

                <!-- 密码修改表单 -->
                <div class="info-card">
                    <h2><i class="fas fa-key"></i> Change Password</h2>
                    
                    <!-- 当前密码 -->
                    <div class="form-group required-field">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="current_password" name="current_password" 
                                   class="form-input" value="<?php echo htmlspecialchars($current_password); ?>"
                                   required placeholder="Enter your current password">
                            <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-hint">Enter your current account password</div>
                    </div>
                    
                    <!-- 新密码 -->
                    <div class="form-group required-field">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="new_password" name="new_password" 
                                   class="form-input" value="<?php echo htmlspecialchars($new_password); ?>"
                                   required placeholder="Enter new password (min 6 characters)">
                            <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-hint">Minimum 6 characters required</div>
                    </div>
                    
                    <!-- 确认新密码 -->
                    <div class="form-group required-field">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   class="form-input" value="<?php echo htmlspecialchars($confirm_password); ?>"
                                   required placeholder="Confirm your new password">
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-hint">Re-enter your new password</div>
                    </div>
                    
                    <!-- 密码强度指示器 -->
                    <div id="passwordStrength" class="password-strength"></div>
                </div>

                <!-- 操作按钮 -->
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="saveButton">
                        <i class="fas fa-save"></i> Change Password
                    </button>
                    
                    <a href="profile.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
                
                <div class="form-note" style="text-align: center; margin-top: 20px; color: #666;">
                    <i class="fas fa-info-circle"></i> All fields are required
                </div>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="Bakery House Logo.png" alt="BakeryHouse">
                </div>
                <p>Sweet & Delicious</p>
                <div class="footer-links">
                    <a href="mainpage.php">Home</a>
                    <a href="menu.html">Menu</a>
                    <a href="about_us.html">About</a>
                    <a href="contact.html">Contact</a>
                    <a href="privacypolicy.html">Privacy Policy</a>
                    <a href="termservice.html">Terms of Service</a>
                </div>
                <p>&copy; 2024 BakeryHouse. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initUserMenu();
            
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            
            if (newPasswordInput) {
                newPasswordInput.addEventListener('input', checkPasswordStrength);
                confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            }
            
            const form = document.getElementById('passwordForm');
            if (form) {
                form.addEventListener('submit', validatePasswordForm);
            }
            
            const saveButton = document.getElementById('saveButton');
            if (saveButton) {
                form.addEventListener('submit', function() {
                    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                    saveButton.disabled = true;
                });
            }
        });
        
        function initUserMenu() {
            const userIcon = document.querySelector('.user-icon');
            const dropdownMenu = document.getElementById('dropdownMenu');
            
            if (userIcon && dropdownMenu) {
                userIcon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleDropdown(dropdownMenu);
                });
                
                document.addEventListener('click', function(e) {
                    if (!userIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.style.display = 'none';
                        dropdownMenu.classList.remove('active');
                    }
                });
            }
        }
        
        function toggleDropdown(dropdown) {
            if (!dropdown) return;
            
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
                dropdown.classList.remove('active');
            } else {
                dropdown.style.display = 'block';
                dropdown.classList.add('active');
            }
        }
        
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
                button.title = 'Hide password';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
                button.title = 'Show password';
            }
        }
        
        function checkPasswordStrength() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (!password) {
                strengthDiv.innerHTML = '';
                return;
            }
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            
            let message = '';
            let color = '';
            
            if (strength <= 2) {
                message = 'Weak';
                color = '#dc3545';
            } else if (strength <= 4) {
                message = 'Fair';
                color = '#fd7e14';
            } else {
                message = 'Strong';
                color = '#28a745';
            }
            
            strengthDiv.innerHTML = `Password strength: <strong style="color: ${color}">${message}</strong>`;
            strengthDiv.style.color = color;
        }
        
        function checkPasswordMatch() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (!confirmPassword) return;
            
            if (newPassword !== confirmPassword) {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#28a745';
            }
        }
        
        function validatePasswordForm(event) {
            const currentPassword = document.getElementById('current_password').value.trim();
            const newPassword = document.getElementById('new_password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();
            
            let isValid = true;
            let errorMessage = '';
            
            if (!currentPassword) {
                errorMessage += '• Current password is required.\n';
                isValid = false;
            }
            
            if (!newPassword) {
                errorMessage += '• New password is required.\n';
                isValid = false;
            } else if (newPassword.length < 6) {
                errorMessage += '• New password must be at least 6 characters long.\n';
                isValid = false;
            }
            
            if (!confirmPassword) {
                errorMessage += '• Please confirm your new password.\n';
                isValid = false;
            } else if (newPassword !== confirmPassword) {
                errorMessage += '• New passwords do not match.\n';
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
                alert('Please fix the following errors:\n\n' + errorMessage);
            }
            
            return isValid;
        }
    </script>
</body>
</html>