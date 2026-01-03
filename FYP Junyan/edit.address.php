<?php
/**
 * edit.address.php - 编辑现有地址页面 (设计风格同步 editprofile)
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

    // 3. 拆解地址字符串 (area|postcode|line)
    $address_area = $address_postcode = $address_line = $other_area = '';
    if (!empty($addressData['address_text']) && strpos($addressData['address_text'], '|') !== false) {
        $parts = explode('|', $addressData['address_text']);
        if (count($parts) >= 3) {
            $address_area = $parts[0];
            $address_postcode = $parts[1];
            $address_line = $parts[2];
            $other_area = isset($parts[3]) ? $parts[3] : '';
        }
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// 4. 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address_area = trim($_POST['address_area'] ?? '');
    $address_postcode = trim($_POST['address_postcode'] ?? '');
    $address_line = trim($_POST['address_line'] ?? '');
    $other_area = trim($_POST['other_area'] ?? '');

    if (empty($address_area)) { $errors[] = "Please select an area."; }
    if (empty($address_postcode)) { $errors[] = "Postcode is required."; }
    if (empty($address_line)) { $errors[] = "Street address is required."; }

    if (empty($errors)) {
        try {
            // 重新拼接地址字符串
            $fullAddress = $address_area . '|' . $address_postcode . '|' . $address_line;
            if ($address_area === 'other' && !empty($other_area)) {
                $fullAddress .= '|' . $other_area;
            }

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
                            <select id="address_area" name="address_area" class="form-input" required onchange="toggleOtherArea()">
                                <option value="">-- Select Area --</option>
                                <option value="Bandar Melaka" <?php echo $address_area === 'Bandar Melaka' ? 'selected' : ''; ?>>Bandar Melaka</option>
                                <option value="Ayer Keroh" <?php echo $address_area === 'Ayer Keroh' ? 'selected' : ''; ?>>Ayer Keroh</option>
                                <option value="Bukit Beruang" <?php echo $address_area === 'Bukit Beruang' ? 'selected' : ''; ?>>Bukit Beruang</option>
                                <option value="other" <?php echo $address_area === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group-half required-field">
                            <label class="form-label">Postcode</label>
                            <input type="text" name="address_postcode" class="form-input" value="<?php echo htmlspecialchars($address_postcode); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Street Address</label>
                        <textarea name="address_line" class="form-textarea" required rows="3"><?php echo htmlspecialchars($address_line); ?></textarea>
                    </div>

                    <div class="form-group" id="other_area_group" style="display: <?php echo $address_area === 'other' ? 'block' : ''; ?>;">
                        <label class="form-label">Please Specify Area</label>
                        <input type="text" name="other_area" class="form-input" value="<?php echo htmlspecialchars($other_area); ?>">
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="saveButton">
                        <i class="fas fa-save"></i> Update Address
                    </button>
                    <a href="manageaddress.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
    function toggleOtherArea() {
        const areaSelect = document.getElementById('address_area');
        const otherGroup = document.getElementById('other_area_group');
        otherGroup.style.display = (areaSelect.value === 'other') ? 'block' : 'none';
    }
    </script>
</body>
</html>