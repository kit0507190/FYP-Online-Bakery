<?php
/**
 * add.address.php - 自动化同步逻辑版本 (已移除勾选框)
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
    $address_area = trim($_POST['address_area'] ?? '');
    $address_postcode = trim($_POST['address_postcode'] ?? '');
    $address_line = trim($_POST['address_line'] ?? '');
    $other_area = trim($_POST['other_area'] ?? '');

    // 后端基础验证
    if (empty($address_area)) { $errors[] = "Please select an area."; }
    if (empty($address_postcode)) { $errors[] = "Postcode is required."; }
    if (empty($address_line)) { $errors[] = "Street address is required."; }

    if (empty($errors)) {
        try {
            // 拼接地址字符串
            $fullAddress = $address_area . '|' . $address_postcode . '|' . $address_line;
            if ($address_area === 'other' && !empty($other_area)) {
                $fullAddress .= '|' . $other_area;
            }

            // --- 自动化逻辑：判断是否为首个地址 ---
            
            // A. 检查该用户目前已有的地址数量 
            $countQuery = "SELECT COUNT(*) FROM user_addresses WHERE user_id = ?";
            $countStmt = $pdo->prepare($countQuery);
            $countStmt->execute([$userId]);
            $addressCount = $countStmt->fetchColumn();

            // B. 逻辑判定：如果是第1个地址，is_default 设为 1，否则设为 0 
            $final_is_default = ($addressCount == 0) ? 1 : 0;

            // C. 插入新地址记录 (手写 SQL 符合 FYP 规范) [cite: 2, 28]
            $sql = "INSERT INTO user_addresses (user_id, address_text, is_default, updated_at) VALUES (?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $fullAddress, $final_is_default]);

            // D. 如果是第一个地址，立即同步更新 user_db 表 
            if ($final_is_default == 1) {
                $updateUserSql = "UPDATE user_db SET address = ? WHERE id = ?";
                $updateUserStmt = $pdo->prepare($updateUserSql);
                $updateUserStmt->execute([$fullAddress, $userId]);
            }

            // 成功后跳转 
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
    <link rel="stylesheet" href="editprofile.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

            <form action="add.address.php" method="POST" class="edit-form" id="addressForm">
                <div class="info-card">
                    <h2><i class="fas fa-map-marker-alt"></i> Address Details</h2>
                    
                    <div class="form-row">
                        <div class="form-group-half required-field">
                            <label class="form-label">Area</label>
                            <select id="address_area" name="address_area" class="form-input" required onchange="toggleOtherArea()">
                                <option value="">-- Select Area --</option>
                                <option value="Bandar Melaka">Bandar Melaka</option>
                                <option value="Ayer Keroh">Ayer Keroh</option>
                                <option value="Bukit Beruang">Bukit Beruang</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group-half required-field">
                            <label class="form-label">Postcode</label>
                            <input type="text" name="address_postcode" class="form-input" required placeholder="e.g., 75000">
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Street Address</label>
                        <textarea name="address_line" class="form-textarea" required rows="3" placeholder="Unit no, Building, Street Name..."></textarea>
                    </div>

                    <div class="form-group" id="other_area_group" style="display: none;">
                        <label class="form-label">Please Specify Area</label>
                        <input type="text" name="other_area" class="form-input" placeholder="Enter your specific area">
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="saveButton">
                        <i class="fas fa-save"></i> Save Address
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

    <script>
    function toggleOtherArea() {
        const areaSelect = document.getElementById('address_area');
        const otherGroup = document.getElementById('other_area_group');
        otherGroup.style.display = (areaSelect.value === 'other') ? 'block' : 'none';
    }

    document.getElementById('addressForm').addEventListener('submit', function() {
        const btn = document.getElementById('saveButton');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        btn.disabled = true;
    });
    </script>
</body>
</html>