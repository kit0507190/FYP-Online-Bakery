<?php
/**
 * manageaddress.php - 地址管理列表页 (已解耦 user_db)
 */
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// --- 逻辑 1：处理删除地址 ---
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    
    // 只有非默认地址可以被删除，确保用户至少保留一个默认入口（除非你想允许全部删除）
    $deleteQuery = "DELETE FROM user_addresses WHERE id = ? AND user_id = ? AND is_default = 0";
    $pdo->prepare($deleteQuery)->execute([$deleteId, $userId]);
    
    header("Location: manageaddress.php");
    exit();
}

// --- 逻辑 2：处理设置为默认地址 ---
if (isset($_GET['set_default'])) {
    $addressId = $_GET['set_default'];
    
    // 第一步：将该用户所有地址设为非默认
    $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
    
    // 第二步：将指定的地址设为默认
    $pdo->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?")->execute([$addressId, $userId]);
    
    header("Location: manageaddress.php");
    exit();
}

// 获取所有地址列表
$stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
$stmt->execute([$userId]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * 核心修正：智能地址解析函数
 * 能够同时识别和美化处理旧格式 (|) 和新格式 (,)
 */
function formatAddress($raw) {
    if (empty($raw)) return "No address detail";

    // 如果是旧格式 Area|Postcode|Line
    if (strpos($raw, '|') !== false) {
        $parts = explode('|', $raw);
        if (count($parts) >= 3) {
            $area = ($parts[0] === 'other' && isset($parts[3])) ? $parts[3] : $parts[0];
            $postcode = $parts[1];
            $street = $parts[2];
            return htmlspecialchars($street) . ", " . htmlspecialchars($area) . ", " . htmlspecialchars($postcode);
        }
    }
    
    // 如果是新格式或其他纯文本，直接返回并处理换行
    return nl2br(htmlspecialchars($raw));
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
                <a href="profile.php" class="back-link"><i class="fas fa-chevron-left"></i> Back to Profile</a>
            </div>

            <div class="profile-header">
                <h1>My Addresses</h1>
                <p>Manage your saved delivery locations</p>
            </div>

            <div class="address-list">
                <?php if (empty($addresses)): ?>
                    <div class="info-card empty-state" style="text-align: center; padding: 40px;">
                        <i class="fas fa-map-marked-alt" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                        <p>No addresses found. Add one to start ordering!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($addresses as $addr): ?>
                        <div class="info-card address-card <?php echo $addr['is_default'] ? 'is-default' : ''; ?>" 
                             style="<?php echo $addr['is_default'] ? 'border-left: 5px solid #d4a76a;' : ''; ?>">
                            <div class="address-body" style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="address-info" style="display: flex; align-items: center; gap: 15px;">
                                    <div class="address-icon" style="color: #d4a76a; font-size: 1.2rem;"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="address-text" style="line-height: 1.5;">
                                        <?php echo formatAddress($addr['address_text']); ?>
                                    </div>
                                </div>
                                
                                <div class="address-actions" style="display: flex; align-items: center; gap: 15px;">
                                    <?php if ($addr['is_default']): ?>
                                        <span class="badge-default" style="background: #d4a76a; color: white; padding: 5px 10px; border-radius: 5px; font-size: 0.8rem;">Default</span>
                                        <a href="edit.address.php?id=<?php echo $addr['id']; ?>" class="btn-edit-icon" style="color: #666;"><i class="fas fa-edit"></i></a>
                                    <?php else: ?>
                                        <a href="manageaddress.php?set_default=<?php echo $addr['id']; ?>" class="btn-set" style="font-size: 0.9rem; color: #d4a76a; text-decoration: none; font-weight: bold;">Set Default</a>
                                        
                                        <a href="edit.address.php?id=<?php echo $addr['id']; ?>" class="btn-edit-icon" style="color: #666;"><i class="fas fa-edit"></i></a>

                                        <a href="manageaddress.php?delete_id=<?php echo $addr['id']; ?>" class="btn-delete" 
                                           style="color: #dc3545;" onclick="return confirm('Delete this address?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="add-action" style="margin-top: 30px; text-align: center;">
                <a href="add.address.php" class="btn btn-primary" style="background: #d4a76a; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;">
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
                    <a href="index.php">Home</a>
                    <a href="menu.php">Menu</a>
                    <a href="about_us.php" class="active">About</a>
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