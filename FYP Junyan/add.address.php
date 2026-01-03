<?php
/**
 * add_address.php - 添加新地址页面 (增加退回功能)
 */
session_start();
require_once 'config.php';

// 1. 验证登录
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$errors = [];

// 2. 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address_text = trim($_POST['address_text'] ?? '');

    if (empty($address_text)) {
        $errors[] = "Please enter your full address.";
    }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO user_addresses (user_id, address_text, is_default) VALUES (?, ?, 0)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $address_text]);

            header("Location: manageaddress.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Address - Bakery House</title>
    <link rel="stylesheet" href="add_address.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    
    <?php include 'header.php'; ?>

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
            
            <div class="back-navigation">
                <a href="manageaddress.php" class="back-link">
                    <i class="fas fa-chevron-left"></i> Back to Manage Addresses
                </a>
            </div>

            <div class="profile-header">
                <h1>Add New Address</h1>
                <p>Provide your delivery details below</p>
            </div>

            <form action="add_address.php" method="POST" class="edit-form">
                <div class="info-card">
                    <h2><i class="fas fa-map-marked-alt"></i> Address Details</h2>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Full Address</label>
                        <textarea name="address_text" class="form-textarea" required rows="4" placeholder="e.g., 17, Taman Bunga 4/12, Ayer Keroh, 75100 Melaka"></textarea>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Address
                    </button>
                    <a href="manageaddress.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
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
                    <a href="menu.php">Menu</a>
                    <a href="about_us.php">About</a>
                    <a href="contact_us.php">Contact</a>
                    <a href="privacypolicy.php">Privacy Policy</a>
                    <a href="termservice.php">Terms of Service</a>
                </div>
                <p>&copy; 2024 BakeryHouse. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>