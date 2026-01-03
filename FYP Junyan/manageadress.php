<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// --- 逻辑：处理设置为默认地址的请求 ---
if (isset($_GET['set_default'])) {
    $addressId = $_GET['set_default'];
    
    // 1. 先把该用户所有地址设为非默认 (0)
    $resetQuery = "UPDATE user_addresses SET is_default = 0 WHERE user_id = ?";
    $pdo->prepare($resetQuery)->execute([$userId]);
    
    // 2. 把选中的地址设为默认 (1)
    $setQuery = "UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?";
    $pdo->prepare($setQuery)->execute([$addressId, $userId]);
    
    header("Location: manage_addresses.php"); // 刷新页面
    exit();
}

// --- 读取该用户的所有地址 ---
$query = "SELECT * FROM user_addresses WHERE user_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Addresses - Bakery House</title>
    <link rel="stylesheet" href="manage_addresses.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="manage-address-page">
        <div class="container">
            <div class="page-header">
                <a href="profile.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Profile</a>
                <h1>My Addresses</h1>
            </div>

            <div class="address-grid">
                <?php if (empty($addresses)): ?>
                    <p>No addresses found. Please add one!</p>
                <?php else: ?>
                    <?php foreach ($addresses as $addr): ?>
                        <div class="address-card <?php echo $addr['is_default'] ? 'default-border' : ''; ?>">
                            <div class="card-content">
                                <p><?php echo htmlspecialchars($addr['address_text']); ?></p>
                                
                                <?php if ($addr['is_default']): ?>
                                    <span class="badge-default">Current Default</span>
                                <?php else: ?>
                                    <a href="manage_addresses.php?set_default=<?php echo $addr['id']; ?>" class="btn-set-default">Set as Default</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="add-new-container">
                <a href="add_address.php" class="btn-add-new"><i class="fas fa-plus"></i> Add New Address</a>
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