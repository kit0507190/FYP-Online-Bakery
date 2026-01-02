<?php
// changepassword.php - 修改密码页面
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
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
        header("Location: User_Login.php");
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
    if (empty($current_password)) { $errors[] = "Current password is required."; }
    if (empty($new_password)) { $errors[] = "New password is required."; }
    if (empty($confirm_password)) { $errors[] = "Please confirm your new password."; }
    
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
                    
                    if ($updateStmt->execute([$hashedPassword, $userId])) {
                        // 【核心改动】成功后直接重定向回 profile.php，并带上 success 参数
                        header("Location: profile.php?success=1");
                        exit();
                    }
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
    
    <?php include 'header.php'; ?>

    <div class="message-container">
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
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
                <div class="info-card">
                    <h2><i class="fas fa-user-circle"></i> Account Information</h2>
                    <div style="margin-bottom: 20px; line-height: 1.8;">
                        <p><strong>Name:</strong> <?php echo $userName; ?></p>
                        <p><strong>Email:</strong> <?php echo $userEmail; ?></p>
                    </div>
                </div>

                <div class="info-card">
                    <h2><i class="fas fa-key"></i> Change Password</h2>
                    
                    <div class="form-group required-field">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="current_password" name="current_password" 
                                   class="form-input" required placeholder="Enter your current password">
                            <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="new_password" name="new_password" 
                                   class="form-input" required placeholder="Min 6 characters">
                            <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   class="form-input" required placeholder="Re-enter new password">
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div id="passwordStrength" class="password-strength"></div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="saveButton">
                        <i class="fas fa-save"></i> Save New Password
                    </button>
                    <a href="profile.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

    <script>
        // 切换密码显示/隐藏
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('passwordForm');
            const saveButton = document.getElementById('saveButton');
            
            if (form && saveButton) {
                form.addEventListener('submit', function() {
                    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                    saveButton.disabled = true;
                });
            }
        });
    </script>
</body>
</html>