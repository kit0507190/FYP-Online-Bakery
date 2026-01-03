<?php
/**
 * manageaddress.php - 地址管理列表页
 */
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// --- 逻辑：处理设置为默认地址 ---
if (isset($_GET['set_default'])) {
    $addressId = $_GET['set_default'];
    
    // 1. 先重置所有地址为非默认
    $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
    
    // 2. 设置选中的地址为默认
    $pdo->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?")->execute([$addressId, $userId]);
    
    header("Location: manageaddress.php");
    exit();
}

// --- 获取所有地址 ---
$stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
$stmt->execute([$userId]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * 格式化地址显示 (兼容 area|postcode|line 格式)
 */
function formatAddress($raw) {
    if (strpos($raw, '|') !== false) {
        $parts = explode('|', $raw);
        if (count($parts) >= 3) {
            $area = ($parts[0] === 'other' && isset($parts[3])) ? $parts[3] : $parts[0];
            return htmlspecialchars($parts[2]) . "<br>" . htmlspecialchars($area) . ", " . htmlspecialchars($parts[1]) . " Melaka";
        }
    }
    return htmlspecialchars($raw);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Addresses - Bakery House</title>
    <link rel="stylesheet" href="manageaddress.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="profile-page">
        <div class="profile-container">
            
            <div class="back-navigation">
                <a href="profile.php" class="back-link">
                    <i class="fas fa-chevron-left"></i> Back to Profile
                </a>
            </div>

            <div class="profile-header">
                <h1>My Addresses</h1>
                <p>Manage your saved delivery locations</p>
            </div>

            <div class="address-list">
                <?php if (empty($addresses)): ?>
                    <div class="info-card empty-state">
                        <p>No addresses found. Start by adding a new one!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($addresses as $addr): ?>
                        <div class="info-card address-card <?php echo $addr['is_default'] ? 'is-default' : ''; ?>">
                            <div class="address-body">
                                <div class="address-info">
                                    <div class="address-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="address-text">
                                        <?php echo formatAddress($addr['address_text']); ?>
                                    </div>
                                </div>
                                
                                <div class="address-actions">
                                    <?php if ($addr['is_default']): ?>
                                        <span class="badge-default">Default Address</span>
                                    <?php else: ?>
                                        <a href="manageaddress.php?set_default=<?php echo $addr['id']; ?>" class="btn-action btn-set">
                                            Set as Default
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="add-action">
                <a href="add.address.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Address
                </a>
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