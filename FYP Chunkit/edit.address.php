<?php
/**
 * edit.address.php - 完整版：包含邮编验证、智能解析与自定义存储顺序
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

    // --- 3. 拆解地址字符串 (适配存储逻辑：街道|地区|邮编|其他) ---
    $address_area = $address_postcode = $address_line = $other_area = '';
    $text = $addressData['address_text'];

    if (strpos($text, '|') !== false) {
        $parts = explode('|', $text);
        
        $address_line     = $parts[0] ?? ''; 
        $address_area     = $parts[1] ?? ''; 
        $address_postcode = $parts[2] ?? ''; 
        $other_area       = $parts[3] ?? ''; 
        
        $known_areas = ["Bandar Melaka", "Ayer Keroh", "Bukit Beruang"];
        if (!empty($address_area) && !in_array($address_area, $known_areas)) {
            $other_area = $address_area;
            $address_area = 'other';
        }
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// --- 4. 处理表单提交更新 ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address_area     = trim($_POST['address_area'] ?? '');
    $address_postcode = trim($_POST['address_postcode'] ?? '');
    $address_line     = trim($_POST['address_line'] ?? '');
    $other_area       = trim($_POST['other_area'] ?? '');

    $postcode_map = [
        "Bandar Melaka" => ["75000", "75100", "75200", "75300"],
        "Ayer Keroh"    => ["75450"],
        "Bukit Beruang" => ["75450"]
    ];

    if (empty($address_area)) { $errors[] = "Please select an area."; }
    
    if (!preg_match("/^[0-9]{5}$/", $address_postcode)) {
        $errors[] = "Postcode must be exactly 5 digits.";
    } 
    elseif ($address_area !== 'other' && isset($postcode_map[$address_area])) {
        if (!in_array($address_postcode, $postcode_map[$address_area])) {
            $errors[] = "The postcode $address_postcode does not match the selected area ($address_area).";
        }
    }

    if (empty($address_line)) { $errors[] = "Street address is required."; }
    if ($address_area === 'other' && empty($other_area)) { $errors[] = "Please specify your area name."; }

    if (empty($errors)) {
        try {
            $display_area = ($address_area === 'other') ? $other_area : $address_area;
            $fullAddress = $address_line . "|" . $display_area . "|" . $address_postcode . "|" . $other_area;

            $updateSql = "UPDATE user_addresses SET address_text = ?, updated_at = NOW() WHERE id = ? AND user_id = ?";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([$fullAddress, $addressId, $userId]);

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
    <link rel="stylesheet" href="footer.css">
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
                        
                        <div class="form-group-half required-field postcode-wrapper">
                            <label class="form-label">Postcode</label>
                            
                            <div id="postcode-hint">
                                <i class="fas fa-info-circle"></i> Valid: <span id="hint-text"></span>
                            </div>

                            <input type="text" name="address_postcode" id="address_postcode" class="form-input" 
                                   value="<?php echo htmlspecialchars($address_postcode); ?>" 
                                   required maxlength="5">
                            <span id="postcode-error"></span>
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Street Address</label>
                        <textarea name="address_line" class="form-textarea" required rows="3" 
                                  placeholder="No 1, Jalan Bakery..."><?php echo htmlspecialchars($address_line); ?></textarea>
                    </div>

                    <div class="form-group" id="other_area_group" style="display: <?php echo $address_area === 'other' ? 'block' : 'none'; ?>;">
                        <label class="form-label">Please Specify Area</label>
                        <input type="text" name="other_area" class="form-input" value="<?php echo htmlspecialchars($other_area); ?>">
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Address
                    </button>
                    <a href="manageaddress.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="add.address.js"></script>

</body>
</html>