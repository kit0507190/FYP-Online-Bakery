<?php
/**
 * edit.address.php - 编辑现有地址页面
 */
session_start();
require_once 'config.php';

// 1. 验证登录
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$addressId = $_GET['id'] ?? null;
$errors = [];

// 2. 获取并验证该地址是否属于当前用户
if (!$addressId) {
    header("Location: manageaddress.php");
    exit();
}

try {
    $query = "SELECT * FROM user_addresses WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$addressId, $userId]);
    $addressData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$addressData) {
        header("Location: manageaddress.php");
        exit();
    }

    // 3. 拆解地址字符串 (适配旧的 | 格式和新的 , 格式)
    $address_area = $address_postcode = $address_line = $other_area = '';
    $text = $addressData['address_text'];
    
    // 兼容逻辑：先尝试用逗号拆分，如果不行再用竖线
    if (strpos($text, ', ') !== false) {
        $parts = explode(', ', $text);
        $address_line = $parts[0] ?? '';
        $address_area = $parts[1] ?? '';
        $address_postcode = $parts[2] ?? '';
        // 如果是 Other 选项，之前的拼接逻辑可能需要根据实际存入的情况微调
    } elseif (strpos($text, '|') !== false) {
        $parts = explode('|', $text);
        $address_area = $parts[0] ?? '';
        $address_postcode = $parts[1] ?? '';
        $address_line = $parts[2] ?? '';
        $other_area = $parts[3] ?? '';
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// 4. 处理表单提交更新
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address_area = trim($_POST['address_area'] ?? '');
    $address_postcode = trim($_POST['address_postcode'] ?? '');
    $address_line = trim($_POST['address_line'] ?? '');
    $other_area = trim($_POST['other_area'] ?? '');

    // 邮编对照字典
    $postcode_map = [
        "Bandar Melaka" => ["75000", "75100", "75200", "75300"],
        "Ayer Keroh"    => ["75450"],
        "Bukit Beruang" => ["75450"]
    ];

    if (empty($address_area)) { $errors[] = "Please select an area."; }
    if (!preg_match("/^[0-9]{5}$/", $address_postcode)) {
        $errors[] = "Postcode must be 5 digits.";
    } elseif ($address_area !== 'other' && isset($postcode_map[$address_area])) {
        if (!in_array($address_postcode, $postcode_map[$address_area])) {
            $errors[] = "Postcode does not match the selected area.";
        }
    }
    if (empty($address_line)) { $errors[] = "Street address is required."; }

    if (empty($errors)) {
        try {
            // 统一使用逗号拼接逻辑
            $display_area = ($address_area === 'other') ? $other_area : $address_area;
            $fullAddress = $address_line . ", " . $display_area . ", " . $address_postcode;

            $updateSql = "UPDATE user_addresses SET address_text = ?, updated_at = NOW() WHERE id = ? AND user_id = ?";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([$fullAddress, $addressId, $userId]);

            // 如果是默认地址，同步更新到 user_db
            if ($addressData['is_default'] == 1) {
                $syncSql = "UPDATE user_db SET address = ? WHERE id = ?";
                $syncStmt = $pdo->prepare($syncSql);
                $syncStmt->execute([$fullAddress, $userId]);
            }

            header("Location: manageaddress.php");
            exit();
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
    <title>Edit Address - Bakery House</title>
    <link rel="stylesheet" href="edit.address.css">
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
                <h1>Edit Address</h1>
                <p>Update your delivery location details</p>
            </div>

            <form action="edit.address.php?id=<?php echo $addressId; ?>" method="POST" class="edit-form" id="addressForm">
                <div class="info-card">
                    <h2><i class="fas fa-map-marker-alt"></i> Address Details</h2>
                    
                    <div class="form-row">
                        <div class="form-group-half required-field">
                            <label class="form-label">Area</label>
                            <select id="address_area" name="address_area" class="form-input" required>
                                <option value="">-- Select Area --</option>
                                <option value="Bandar Melaka" <?php echo $address_area === 'Bandar Melaka' ? 'selected' : ''; ?>>Bandar Melaka</option>
                                <option value="Ayer Keroh" <?php echo $address_area === 'Ayer Keroh' ? 'selected' : ''; ?>>Ayer Keroh</option>
                                <option value="Bukit Beruang" <?php echo $address_area === 'Bukit Beruang' ? 'selected' : ''; ?>>Bukit Beruang</option>
                                <option value="other" <?php echo $address_area === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group-half required-field">
                            <label class="form-label">Postcode</label>
                            <input type="text" name="address_postcode" id="address_postcode" class="form-input" 
                                   value="<?php echo htmlspecialchars($address_postcode); ?>" 
                                   required maxlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Street Address</label>
                        <textarea name="address_line" class="form-textarea" required rows="3" 
                                  placeholder="Unit no, Building, Street Name..."><?php echo htmlspecialchars($address_line); ?></textarea>
                    </div>

                    <div class="form-group" id="other_area_group" style="display: <?php echo $address_area === 'other' ? 'block' : 'none'; ?>;">
                        <label class="form-label">Please Specify Area</label>
                        <input type="text" name="other_area" class="form-input" value="<?php echo htmlspecialchars($other_area); ?>">
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="saveButton">
                        <i class="fas fa-save"></i> Update Address
                    </button>
                    <a href="manageaddress.php" class="btn btn-secondary">Cancel</a>
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

    <script src="add.address.js"></script>
</body>
</html>