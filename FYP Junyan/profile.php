<?php
/**
 * profile.php - 用户资料展示页
 */
session_start();

// --- 1. 登录检查 ---
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php"); 
    exit();
}

// --- 2. 引入数据库连接 ---
require_once 'config.php';

$userId = $_SESSION['user_id'];

// --- 3. 从数据库读取最新资料 ---
try {
    $query = "SELECT name, email, phone, address, created_at FROM user_db WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // 将数据库信息存入变量，并转义防止安全问题
        $name = htmlspecialchars($user['name']);
        $email = htmlspecialchars($user['email']);
        $phone = htmlspecialchars($user['phone'] ?? 'Not provided');
        $memberSince = date("F j, Y", strtotime($user['created_at']));
        
        // 解析地址 (处理 | 分隔符)
        $address_display = "No default address set";
        if (!empty($user['address'])) {
            if (strpos($user['address'], '|') !== false) {
                $parts = explode('|', $user['address']);
                if (count($parts) >= 3) {
                    $addrLine = htmlspecialchars($parts[2]);
                    $addrArea = ($parts[0] === 'other' && isset($parts[3])) ? htmlspecialchars($parts[3]) : htmlspecialchars($parts[0]);
                    $addrPost = htmlspecialchars($parts[1]);
                    $address_display = "$addrLine<br>$addrArea, $addrPost Melaka<br>Malaysia";
                }
            } else {
                $address_display = htmlspecialchars($user['address']);
            }
        }
        $isLoggedIn = true;
        $userName = $user['name'];
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Bakery House</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    
    <?php include 'header.php'; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
    <div class="toast-overlay" id="toastOverlay">
        <div class="toast-card">
            <div class="toast-icon"><i class="fas fa-check"></i></div>
            <h3>Profile Updated!</h3>
            <p>Your account information has been baked to perfection. Everything is up to date!</p>
            <button class="close-toast" onclick="closeToast()">Done</button>
        </div>
    </div>
    <?php endif; ?>

    <main class="profile-page">
        <div class="profile-container">
            <div class="profile-header">
                <h1>My Profile</h1>
                <p>View and manage your account information</p>
            </div>

            <div class="info-card">
                <h2><i class="fas fa-user-circle"></i> Account Information</h2>
                <div class="info-row">
                    <div class="info-label">FULL NAME:</div>
                    <div class="info-value"><?php echo $name; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">EMAIL:</div>
                    <div class="info-value"><?php echo $email; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">PHONE:</div>
                    <div class="info-value"><?php echo $phone; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">MEMBER SINCE:</div>
                    <div class="info-value"><?php echo $memberSince; ?></div>
                </div>
            </div>

            <div class="info-card">
                <div class="address-header">
                    <h2><i class="fas fa-map-marker-alt"></i> Default Address</h2>
                    <a href="manageaddress.php" class="btn-manage-address">
                        <i class="fas fa-cog"></i> Manage
                    </a>
                </div>
                <div class="info-row">
                    <div class="info-label">ADDRESS:</div>
                    <div class="info-value"><?php echo $address_display; ?></div>
                </div>
            </div>

           <div class="action-buttons">
                <a href="editprofile.php" class="btn btn-edit">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>

                <a href="changepassword.php" class="btn btn-change-password">
                    <i class="fas fa-key"></i> Change Password
                </a>

                <a href="logout.php" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
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

    <script src="profile.js"></script>
</body>
</html>