<?php
// changepassword.php - 修改密码页面
session_start();

// 1. 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

require_once 'config.php';

$userId = $_SESSION['user_id'];
$errors = [];
$userName = '';
$userEmail = '';

// 2. 获取用户信息
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

// 3. 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($current_password)) { $errors[] = "Current password is required."; }
    if (strlen($new_password) < 6) { $errors[] = "New password must be at least 6 characters long."; }
    if ($new_password !== $confirm_password) { $errors[] = "New passwords do not match."; }
    
    if (empty($errors)) {
        try {
            $passwordQuery = "SELECT password FROM user_db WHERE id = ?";
            $passwordStmt = $pdo->prepare($passwordQuery);
            $passwordStmt->execute([$userId]);
            $userData = $passwordStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($userData && password_verify($current_password, $userData['password'])) {
                if (password_verify($new_password, $userData['password'])) {
                    $errors[] = "New password must be different from current password.";
                } else {
                    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                    // 符合 SCRUD 手写要求 
                    $updateQuery = "UPDATE user_db SET password = ?, updated_at = NOW() WHERE id = ?";
                    $updateStmt = $pdo->prepare($updateQuery);
                    
                    if ($updateStmt->execute([$hashedPassword, $userId])) {
                        // 成功后重定向到自己以触发弹窗
                        header("Location: changepassword.php?success=1");
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

    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
    <div class="toast-overlay" id="toastOverlay">
        <div class="toast-card">
            <div class="toast-icon"><i class="fas fa-check"></i></div>
            <h3 style="color: var(--bakery-brown); margin-bottom: 10px;">Password Changed!</h3>
            <p style="color: #666; margin-bottom: 25px;">Your account is now more secure. Please use your new password next time you log in.</p>
            <button class="btn btn-primary" onclick="window.location.href='profile.php'" style="width: 100%;">Done</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="message-container">
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <main class="profile-page">
        <div class="profile-container">
            <div class="profile-header">
                <h1>Change Password</h1>
                <p>Ensure your account stays protected</p>
            </div>

            <form action="changepassword.php" method="POST" class="edit-form" id="passwordForm">
                <div class="info-card">
                    <h2><i class="fas fa-key"></i> Security Settings</h2>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Current Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="current_password" name="current_password" class="form-input" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">New Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="new_password" name="new_password" class="form-input" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Confirm New Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="saveButton">
                        <i class="fas fa-lock"></i> Update Password
                    </button>
                    <a href="profile.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <script>
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

        document.getElementById('passwordForm').addEventListener('submit', function() {
            const btn = document.getElementById('saveButton');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Securing...';
            btn.disabled = true;
        });
    </script>
</body>
</html>