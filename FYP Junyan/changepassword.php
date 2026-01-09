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
                    $updateQuery = "UPDATE user_db SET password = ?, updated_at = NOW() WHERE id = ?";
                    $updateStmt = $pdo->prepare($updateQuery);
                    if ($updateStmt->execute([$hashedPassword, $userId])) {
                        header("Location: changepassword.php?success=1");
                        exit();
                    }
                }
            } else { $errors[] = "Current password is incorrect."; }
        } catch (PDOException $e) { $errors[] = "Update failed: " . $e->getMessage(); }
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
    <style>
        /* 强制覆盖和修复样式 */
        .password-wrapper {
            position: relative !important;
            display: block !important; /* 确保占满宽度 */
        }
        .password-wrapper .form-input {
            padding-right: 45px !important;
            width: 100%;
        }
        .toggle-password {
            position: absolute !important;
            right: 15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background: none !important;
            border: none !important;
            color: #999 !important;
            cursor: pointer !important;
            z-index: 10 !important;
        }
        .toast-overlay {
            position: fixed !important;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.7) !important;
            display: flex !important;
            align-items: center; justify-content: center;
            z-index: 9999 !important;
        }
        .toast-card {
            background: white !important;
            padding: 40px !important;
            border-radius: 15px !important;
            text-align: center !important;
            max-width: 400px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important;
        }
        .toast-icon {
            background: #d4a76a; color: white;
            width: 60px; height: 60px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 30px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

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
                    <button type="submit" class="btn btn-primary" id="saveButton">Update Password</button>
                    <a href="profile.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

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
    </script>
</body>
</html>