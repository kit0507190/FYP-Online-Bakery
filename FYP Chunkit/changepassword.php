<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: User_Login.php"); 
    exit(); 
}
require_once 'config.php';

$userId = $_SESSION['user_id'];
$errors = [];
$userName = ''; 
$userEmail = '';

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
    die("Error: " . $e->getMessage()); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($current_password)) { 
        $errors[] = "Current password is required."; 
    }

    if (strlen($new_password) < 8 || !preg_match("/[A-Za-z]/", $new_password) || !preg_match("/[0-9]/", $new_password)) {
        $errors[] = "Password must be 8+ chars with letters & numbers.";
    }

    if ($new_password !== $confirm_password) { 
        $errors[] = "New passwords do not match."; 
    }
    
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

<?php if (isset($_GET['success'])): ?>
<div class="toast-overlay" id="toastOverlay">
    <div class="toast-card">
        <div class="toast-icon"><i class="fas fa-check"></i></div>
        <h3 style="color: #000; font-size: 24px; font-weight: bold; margin-bottom: 10px;">Password Changed!</h3>
        <p style="color: #333; font-size: 16px; margin-bottom: 20px;">Your security has been updated successfully.</p>
        <button class="close-toast" onclick="window.location.href='profile.php'">Done</button>
    </div>
</div>
<?php endif; ?>

<main class="profile-page">
    <div class="profile-container">
        <div class="profile-header">
            <h1>Change Password</h1>
            <p>Keep your Bakery House account secure</p>
        </div>

        <div id="js-error-container" class="message-container" style="margin-bottom: 25px;">
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <ul style="margin: 0; list-style: none; padding: 0;">
                        <?php foreach ($errors as $e): ?>
                            <li><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <form action="changepassword.php" method="POST" class="edit-form" id="passwordForm" novalidate>
            <div class="info-card">
                <h2><i class="fas fa-key"></i> Security Settings</h2>
                
                <div class="form-group required-field">
                    <label class="form-label">Current Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="current_password" name="current_password" class="form-input" required>
                        <button type="button" class="toggle-password" onclick="togglePasswordDisplay('current_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div style="text-align: right; margin-top: 5px;">
                        <a href="forgotpassword.php" style="font-size: 12px; color: var(--bakery-gold); text-decoration: none; font-weight: bold;">
                            Forgot current password?
                        </a>
                    </div>
                </div>

                <div class="form-group required-field">
                    <label class="form-label">New Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="new_password" name="new_password" class="form-input" required placeholder="8+ chars (letters & numbers)">
                        <button type="button" class="toggle-password" onclick="togglePasswordDisplay('new_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group required-field">
                    <label class="form-label">Confirm New Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                        <button type="button" class="toggle-password" onclick="togglePasswordDisplay('confirm_password')">
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
<script src="changepassword.js"></script>
</body>
</html>