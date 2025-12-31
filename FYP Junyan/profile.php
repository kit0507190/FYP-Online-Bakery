<?php
// profile.php - 查看模式 (PDO版本)
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php"); // 统一跳转到你的登录页
    exit();
}

// 引入数据库连接
require_once 'config.php';

// 检查数据库连接
if (!isset($pdo)) {
    die("Database connection failed.");
}

$userId = $_SESSION['user_id'];

// 获取用户信息
try {
    $query = "SELECT name, email, phone, address, created_at FROM user_db WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $name = htmlspecialchars($user['name']);
        $email = htmlspecialchars($user['email']);
        $phone = htmlspecialchars($user['phone'] ?? 'Not provided');
        $memberSince = date("F j, Y", strtotime($user['created_at']));
        
        // 解析地址显示逻辑
        $address_display = "No default address set";
        if (!empty($user['address'])) {
            if (strpos($user['address'], '|') !== false) {
                $address_parts = explode('|', $user['address']);
                if (count($address_parts) >= 3) {
                    $address_area = htmlspecialchars($address_parts[0]);
                    $address_postcode = htmlspecialchars($address_parts[1]);
                    $address_line = htmlspecialchars($address_parts[2]);
                    $other_area = isset($address_parts[3]) ? htmlspecialchars($address_parts[3]) : '';
                    $display_area = ($address_area === 'other' && !empty($other_area)) ? $other_area : $address_area;
                    $address_display = $address_line . "<br>" . $display_area . ", " . $address_postcode . " Melaka<br>Malaysia";
                }
            } else {
                $address_display = htmlspecialchars($user['address']);
            }
        }
        
        // 用于 Header 的变量
        $isLoggedIn = true;
        $userName = $user['name'];
    } else {
        session_destroy();
        header("Location: User_Login.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error fetching user data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Bakery House</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="mainpage.php" class="logo">
                    <img src="Bakery House Logo.png" alt="BakeryHouse">
                </a>
                <ul class="nav-links">
                    <li><a href="mainpage.php">Home</a></li>
                    <li><a href="menu.html">Menu</a></li>
                    <li><a href="about_us.html">About</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li class="cart-icon" id="cartIcon">
                        <span>🛒 Cart</span>
                        <span class="cart-count">0</span>
                    </li>
                    
                    <?php if ($isLoggedIn): ?>
                        <li class="user-menu">
                            <div class="user-icon" onclick="toggleDropdown()">
                                <?php echo strtoupper(substr($userName, 0, 1)); ?>
                            </div>
                            
                            <div class="dropdown-menu" id="dropdownMenu">
                                <a href="profile.php">Profile</a>
                                <a href="logout.php">Log Out</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="User_Login.php" class="signup-btn">Sign In</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <div class="message-container">
        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div class="success-message">Profile updated successfully!</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">Error: <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
    </div>

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
                <h2><i class="fas fa-map-marker-alt"></i> Default Address</h2>
                <div class="info-row">
                    <div class="info-label">ADDRESS:</div>
                    <div class="info-value formatted-address"><?php echo $address_display; ?></div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="editprofile.php" class="btn btn-edit"><i class="fas fa-edit"></i> Edit Profile</a>
                <a href="changepassword.php" class="btn btn-change-password"><i class="fas fa-key"></i> Change Password</a>
                <a href="order_history.php" class="btn btn-history"><i class="fas fa-history"></i> Order History</a>
                <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo"><img src="Bakery House Logo.png" alt="BakeryHouse"></div>
                <p>Sweet & Delicious</p>
                <div class="footer-links">
                    <a href="mainpage.php">Home</a>
                    <a href="menu.html">Menu</a>
                    <a href="about_us.html">About</a>
                    <a href="contact.html">Contact</a>
                </div>
                <p>&copy; 2024 BakeryHouse. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
    function toggleDropdown(event) {
        // 防止点击事件向上传播到 window
        event.stopPropagation();
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('show');
    }

    // 点击页面其他任何地方关闭菜单
    window.onclick = function(event) {
        const dropdown = document.getElementById('dropdownMenu');
        const userIcon = document.querySelector('.user-icon');
        
        // 如果点击的不是图标也不是菜单内部，就关闭
        if (dropdown && !userIcon.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    }
</script>

</body>
</html>