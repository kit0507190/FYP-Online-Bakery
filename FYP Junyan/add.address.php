<?php
/**
 * add.address.php - 添加新地址并自动同步默认地址逻辑
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
    $is_default_input = isset($_POST['is_default']) ? 1 : 0; // 获取是否勾选设为默认

    // 基础验证
    if (empty($address_area)) { $errors[] = "Please select an area."; }
    if (empty($address_postcode)) { $errors[] = "Postcode is required."; }
    if (empty($address_line)) { $errors[] = "Street address is required."; }

    if (empty($errors)) {
        try {
            // 拼接格式：area|postcode|line[|other_area]
            $fullAddress = $address_area . '|' . $address_postcode . '|' . $address_line;
            if ($address_area === 'other' && !empty($other_area)) {
                $fullAddress .= '|' . $other_area;
            }

            // --- 方案一核心逻辑开始 ---

            // A. 检查用户当前已有的地址数量
            $countQuery = "SELECT COUNT(*) FROM user_addresses WHERE user_id = ?";
            $countStmt = $pdo->prepare($countQuery);
            $countStmt->execute([$userId]);
            $addressCount = $countStmt->fetchColumn();

            // B. 如果是第一个地址，强制设为默认
            $final_is_default = ($addressCount == 0) ? 1 : $is_default_input;

            // C. 如果新地址要设为默认，先把该用户旧的默认地址取消
            if ($final_is_default == 1) {
                $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?")
                    ->execute([$userId]);
            }

            // D. 插入新地址记录 (符合手写 SCRUD 需求 )
            $sql = "INSERT INTO user_addresses (user_id, address_text, is_default, updated_at) VALUES (?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $fullAddress, $final_is_default]);

            // E. 同步到 user_db (解决你提到的第一个地址存不进去的问题)
            if ($final_is_default == 1) {
                $updateUserSql = "UPDATE user_db SET address = ? WHERE id = ?";
                $updateUserStmt = $pdo->prepare($updateUserSql);
                $updateUserStmt->execute([$fullAddress, $userId]);
            }

            // --- 核心逻辑结束 ---

            header("Location: manageaddress.php?success=1");
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
                    <h2><i class="fas fa-map-marker-alt"></i> Delivery Address</h2>
                    
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
                        <textarea name="address_line" class="form-textarea" required rows="3" placeholder="House No, Street Name, etc."></textarea>
                    </div>

                    <div class="form-group" id="other_area_group" style="display: none;">
                        <label class="form-label">Please Specify Area</label>
                        <input type="text" name="other_area" class="form-input" placeholder="Enter your specific area">
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: var(--bakery-brown); font-weight: 600;">
                            <input type="checkbox" name="is_default" value="1" style="width: 18px; height: 18px;"> 
                            Set as default delivery address
                        </label>
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

    <script>
    function toggleOtherArea() {
        const areaSelect = document.getElementById('address_area');
        const otherGroup = document.getElementById('other_area_group');
        otherGroup.style.display = (areaSelect.value === 'other') ? 'block' : 'none';
    }

    // 提交状态控制
    document.getElementById('addressForm').addEventListener('submit', function() {
        const btn = document.getElementById('saveButton');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        btn.disabled = true;
    });
    </script>
</body>
</html>