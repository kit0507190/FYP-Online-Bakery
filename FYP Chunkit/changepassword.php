<?php
// changepassword.php - 修改密码页面
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: User_Login.php"); exit(); }
require_once 'config.php';

$userId = $_SESSION['user_id'];
$errors = [];
$userName = ''; $userEmail = '';

try {
    $query = "SELECT name, email FROM user_db WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $userName = htmlspecialchars($user['name']);
        $userEmail = htmlspecialchars($user['email']);
    } else {
        session_destroy(); header("Location: User_Login.php"); exit();
    }
} catch (PDOException $e) { die("Error: " . $e->getMessage()); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // --- 验证逻辑开始 ---
    if (empty($current_password)) { 
        $errors[] = "Current password is required."; 
    }

    // 【同步注册页面的验证标准】
    if (strlen($new_password) < 8 || !preg_match("/[A-Za-z]/", $new_password) || !preg_match("/[0-9]/", $new_password)) {
        $errors[] = "Password must be 8+ chars with letters & numbers.";
    }

    if ($new_password !== $confirm_password) { 
        $errors[] = "New passwords do not match."; 
    }
    // --- 验证逻辑结束 ---
    
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
                    $updateQuery = "UPDATE user_db SET password = ?, updated_at = NOW() WHERE id = ?";
                    $updateStmt = $pdo->prepare($updateQuery);
                    if ($updateStmt->execute([$hashedPassword, $userId])) {
                        header("Location: changepassword.php?success=1");
                        exit();
                    }
                }
            } else { 
                $errors[] = "Current password is incorrect."; 
            }
        } catch (PDOException $e) { 
            $errors[] = "Update failed: " . $e->getMessage(); 
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
    <link rel="stylesheet" href="footer.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="message-container" style="max-width: 800px; margin: 20px auto 0; padding: 0 20px;">
        <?php if (!empty($errors)): ?>
            <div class="error-message" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; border-left: 5px solid #dc3545;">
                <ul style="margin: 0; list-style: none; padding: 0;">
                    <?php foreach ($errors as $e): ?>
                        <li><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="toast-overlay">
        <div class="toast-card">
            <div class="toast-icon"><i class="fas fa-check"></i></div>
            <h3 style="color: #5a3921;">Password Changed!</h3>
            <p style="color: #666; margin: 15px 0 25px;">Your security has been updated successfully.</p>
            <button class="btn btn-primary" onclick="window.location.href='profile.php'" style="width: 100%;">Done</button>
        </div>
    </div>
    <?php endif; ?>

    <main class="profile-page">
        <div class="profile-container">
            <div class="profile-header">
                <h1>Change Password</h1>
                <p>Keep your Bakery House account secure</p>
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
                            <input type="password" id="new_password" name="new_password" class="form-input" required placeholder="8+ chars (letters & numbers)">
                            <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span id="password-error" style="color: #dc3545; font-size: 12px; display: none; margin-top: 5px; font-weight: bold;"></span>
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
                    <button type="submit" class="btn btn-primary" id="saveButton">Update Password</button>
                    <a href="profile.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // --- 前端异步验证逻辑 ---
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const newPwd = document.getElementById('new_password').value;
            const confirmPwd = document.getElementById('confirm_password').value;
            const errorSpan = document.getElementById('password-error');
            
            // 正则表达式：至少包含一个字母和一个数字
            const hasLetter = /[A-Za-z]/.test(newPwd);
            const hasNumber = /[0-9]/.test(newPwd);

            if (newPwd.length < 8 || !hasLetter || !hasNumber) {
                e.preventDefault(); // 拦截提交
                errorSpan.textContent = "Password must be 8+ chars with letters & numbers.";
                errorSpan.style.display = "block";
                document.getElementById('new_password').style.borderColor = "#dc3545";
                return;
            }

            if (newPwd !== confirmPwd) {
                e.preventDefault();
                errorSpan.textContent = "New passwords do not match.";
                errorSpan.style.display = "block";
                return;
            }
        });
    </script>
</body>
</html>