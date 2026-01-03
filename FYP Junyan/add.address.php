<?php
/**
 * add_address.php - 添加新地址页面
 */
session_start();
require_once 'config.php';

// 1. 登录检查
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

// 2. 处理表单提交逻辑
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $addressText = trim($_POST['address_text']);

    if (!empty($addressText)) {
        try {
            // 插入新地址，is_default 默认为 0
            $sql = "INSERT INTO user_addresses (user_id, address_text, is_default) VALUES (?, ?, 0)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $addressText]);

            // 成功后跳回管理页面 (请确认你的文件名是 manageaddress.php)
            header("Location: manageaddress.php");
            exit();
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    } else {
        $error = "Please enter a valid address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Address - Bakery House</title>
    <link rel="stylesheet" href="manage_addresses.css">
    <link rel="stylesheet" href="add.address.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="add-address-page">
        <div class="container">
            <div class="page-header">
                <a href="manageaddress.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Addresses
                </a>
                <h1>Add New Address</h1>
            </div>

            <div class="form-card">
                <?php if (isset($error)): ?>
                    <div class="error-box"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="add_address.php" method="POST">
                    <div class="form-group">
                        <label for="address_text">Full Address Details</label>
                        <textarea 
                            name="address_text" 
                            id="address_text" 
                            placeholder="e.g. 17, Taman Bunga 4/12, Ayer Keroh, 75100 Melaka" 
                            required
                        ></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> Save Address
                        </button>
                    </div>
                </form>
            </div>
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