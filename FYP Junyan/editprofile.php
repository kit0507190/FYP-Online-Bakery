<?php
/**
 * editprofile.php - 编辑个人资料页面
 */
session_start();

// 1. 验证登录：未登录则重定向到登录页
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit();
}

// 2. 引入数据库配置
require_once 'config.php';

if (!isset($pdo)) {
    die("Database connection failed.");
}

$userId = $_SESSION['user_id'];
$errors = [];
$fieldErrors = [];
$name = $email = $phone = $address_area = $address_postcode = $address_line = $other_area = '';

// 3. 获取当前用户的资料信息
try {
    $query = "SELECT name, email, phone, address FROM user_db WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $name = htmlspecialchars($user['name']);
        $email = htmlspecialchars($user['email']);
        $phone = htmlspecialchars($user['phone'] ?? '');
        
        // 地址拆解逻辑 (area|postcode|line)
        if (!empty($user['address']) && strpos($user['address'], '|') !== false) {
            $parts = explode('|', $user['address']);
            if (count($parts) >= 3) {
                $address_area = htmlspecialchars($parts[0]);
                $address_postcode = htmlspecialchars($parts[1]);
                $address_line = htmlspecialchars($parts[2]);
                $other_area = isset($parts[3]) ? htmlspecialchars($parts[3]) : '';
            }
        } else {
            $address_line = htmlspecialchars($user['address'] ?? '');
        }
        
        // 设置给 header.php 使用的变量
        $isLoggedIn = true;
        $userName = $user['name'];
    } else {
        session_destroy();
        header("Location: User_Login.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// 4. 处理表单提交更新请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address_area = trim($_POST['address_area'] ?? '');
    $address_postcode = trim($_POST['address_postcode'] ?? '');
    $address_line = trim($_POST['address_line'] ?? '');
    $other_area = trim($_POST['other_area'] ?? '');

    // --- 后端基础验证 ---
    if (empty($name)) { $errors[] = "Full name is required."; }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Valid email is required."; }
    if (empty($address_area)) { $errors[] = "Please select an area."; }
    if (empty($address_postcode)) { $errors[] = "Postcode is required."; }

    // 5. 如果没有错误，执行更新
    if (empty($errors)) {
        try {
            // 重新拼接地址字符串
            $fullAddress = $address_area . '|' . $address_postcode . '|' . $address_line;
            if ($address_area === 'other' && !empty($other_area)) {
                $fullAddress .= '|' . $other_area;
            }

            $updateQuery = "UPDATE user_db SET name = ?, email = ?, phone = ?, address = ?, updated_at = NOW() WHERE id = ?";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute([$name, $email, $phone, $fullAddress, $userId]);

            // 更新 Session 中的名字
            $_SESSION['user_name'] = $name;

            // 【关键】修改成功后跳回 profile.php 并触发弹窗
            header("Location: profile.php?success=1");
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
    <title>Edit Profile - Bakery House</title>
    <link rel="stylesheet" href="editprofile.css?v=1.1">
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
            <div class="profile-header">
                <h1>Edit Profile</h1>
                <p>Update your personal information below</p>
            </div>

            <form action="editprofile.php" method="POST" class="edit-form" id="profileForm">
                <div class="info-card">
                    <h2><i class="fas fa-user-circle"></i> Personal Information</h2>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-input" value="<?php echo $name; ?>" required>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" value="<?php echo $email; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-input" value="<?php echo $phone; ?>" placeholder="e.g., 011-2345678">
                    </div>
                </div>

                <div class="info-card">
                    <h2><i class="fas fa-map-marker-alt"></i> Delivery Address</h2>
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
                            <input type="text" name="address_postcode" class="form-input" value="<?php echo $address_postcode; ?>" required placeholder="e.g., 75000">
                        </div>
                    </div>
                    
                    <div class="form-group required-field">
                        <label class="form-label">Street Address</label>
                        <textarea name="address_line" class="form-textarea" required rows="3"><?php echo $address_line; ?></textarea>
                    </div>

                    <div class="form-group" id="other_area_group" style="display: <?php echo $address_area === 'other' ? 'block' : 'none'; ?>;">
                        <label class="form-label">Please Specify Area</label>
                        <input type="text" name="other_area" class="form-input" value="<?php echo $other_area; ?>">
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary" id="saveButton">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="profile.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
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

    <script src="editprofile.js"></script>
</body>
</html>