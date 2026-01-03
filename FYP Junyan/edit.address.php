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
$success = false;

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
        // 如果地址不存在或不属于该用户，强制跳回
        header("Location: manageaddress.php");
        exit();
    }

    // 3. 将存储的字符串拆解回表单 (假设格式为: Area | Postcode | Street)
    $parts = explode(' | ', $addressData['address_text']);
    $currentArea = $parts[0] ?? '';
    $currentPostcode = $parts[1] ?? '';
    $currentStreet = $parts[2] ?? '';

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// 4. 处理表单更新提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $area = $_POST['area'] ?? '';
    $postcode = trim($_POST['postcode'] ?? '');
    $street = trim($_POST['street'] ?? '');

    if (empty($area) || empty($postcode) || empty($street)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        try {
            $fullAddress = "$area | $postcode | $street";
            
            $updateSql = "UPDATE user_addresses SET address_text = ?, updated_at = NOW() WHERE id = ? AND user_id = ?";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([$fullAddress, $addressId, $userId]);

            // 如果该地址是默认地址，同步更新 user_db (方案A的可选增强)
            if ($addressData['is_default'] == 1) {
                $syncSql = "UPDATE user_db SET address = ? WHERE id = ?";
                $syncStmt = $pdo->prepare($syncSql);
                $syncStmt->execute([$fullAddress, $userId]);
            }

            header("Location: manageaddress.php?updated=1");
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
    <link rel="stylesheet" href="add.address.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    
    <?php include 'header.php'; ?>

    <main class="address-page">
        <div class="address-container">
            <div class="address-header">
                <h1>Edit Address</h1>
                <p>Modify your delivery location details below</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach ($errors as $error): echo "<p>$error</p>"; endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="edit.address.php?id=<?php echo $addressId; ?>" method="POST" class="address-form">
                
                <div class="form-group">
                    <label>Area / City</label>
                    <select name="area" class="form-select" required>
                        <option value="">Select Area</option>
                        <option value="Kuala Lumpur" <?php echo ($currentArea == 'Kuala Lumpur') ? 'selected' : ''; ?>>Kuala Lumpur</option>
                        <option value="Petaling Jaya" <?php echo ($currentArea == 'Petaling Jaya') ? 'selected' : ''; ?>>Petaling Jaya</option>
                        <option value="Subang Jaya" <?php echo ($currentArea == 'Subang Jaya') ? 'selected' : ''; ?>>Subang Jaya</option>
                        <option value="Shah Alam" <?php echo ($currentArea == 'Shah Alam') ? 'selected' : ''; ?>>Shah Alam</option>
                        <option value="Others" <?php echo ($currentArea == 'Others') ? 'selected' : ''; ?>>Others</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Postcode</label>
                    <input type="text" name="postcode" class="form-input" placeholder="e.g. 47500" 
                           value="<?php echo htmlspecialchars($currentPostcode); ?>" required>
                </div>

                <div class="form-group">
                    <label>Street Address</label>
                    <textarea name="street" class="form-textarea" placeholder="Unit no, Building, Street Name..." 
                              required><?php echo htmlspecialchars($currentStreet); ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">Update Address</button>
                    <a href="manageaddress.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </main>

</body>
</html>