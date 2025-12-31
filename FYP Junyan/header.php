<?php
// header.php - 统一导航栏组件
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true; //
$userName = $isLoggedIn ? ($_SESSION['user_name'] ?? 'User') : ''; //
?>

<style>
    /* ============================================================
       统一 HEADER 核心样式
       ============================================================ */
    header.main-header {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 2000 !important;
        width: 100% !important;
        padding: 12px 0 !important;
        background-color: #ffffff !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
        display: flex !important;
        align-items: center !important;
    }

    .header-navbar {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 0 20px !important;
        width: 100% !important;
    }

    /* Logo 品牌区 */
    .logo-brand {
        display: flex !important;
        align-items: center !important;
        gap: 15px !important;
        text-decoration: none !important;
    }

    .logo-brand img { height: 60px !important; width: auto !important; }

    .brand-name {
        font-size: 24px !important;
        font-weight: 800 !important;
        color: #5a3921 !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
    }

    /* 导航链接 */
    .nav-links {
        display: flex !important;
        align-items: center !important;
        gap: 25px !important;
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* --- 核心修改：仅在鼠标滑过 (Hover) 时显示下划线 --- */
    .nav-links a {
        position: relative !important;
        color: #5a3921 !important; /* 默认深棕色 */
        font-weight: 600 !important;
        text-decoration: none !important;
        font-size: 16px !important;
        transition: color 0.3s ease !important;
    }

    /* 创建隐藏的下划线 */
    .nav-links a::after {
        content: '' !important;
        position: absolute !important;
        bottom: -5px !important;
        left: 0 !important;
        width: 0 !important; /* 初始宽度为 0 */
        height: 2.5px !important;
        background-color: #d4a76a !important; /* 金色下划线 */
        transition: width 0.3s ease !important; /* 平滑展开动画 */
    }

    /* 当鼠标滑过 (Hover) 或 链接处于 Active 状态时显示 */
    .nav-links a:hover::after, 
    .nav-links a.active::after {
        width: 100% !important; /* 宽度变为 100% */
    }

    /* 滑过时文字变色 */
    .nav-links a:hover, 
    .nav-links a.active {
        color: #d4a76a !important;
    }

    /* 购物车 */
    .cart-icon-wrapper {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        cursor: pointer !important;
        color: #5a3921 !important;
        font-weight: 600 !important;
    }

    .cart-badge {
        background-color: #e74c3c !important;
        color: white !important;
        border-radius: 50% !important;
        width: 18px !important;
        height: 18px !important;
        font-size: 11px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }

    /* 用户菜单 */
    .user-menu-wrapper { position: relative !important; }
    .user-icon-circle {
        width: 45px; height: 45px;
        background: linear-gradient(135deg, #d4a574, #b8864e);
        border: 3px solid #f8e8d8 !important; 
        border-radius: 50% !important;
        display: flex !important; align-items: center !important; justify-content: center !important;
        color: white !important; font-weight: bold !important; cursor: pointer !important;
        transition: transform 0.3s ease !important;
    }
    .user-icon-circle:hover { transform: scale(1.1) !important; }

    .dropdown-box {
        display: none; position: absolute; right: 0; top: 100%;
        background: white; min-width: 150px; margin-top: 10px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15); border-radius: 10px;
        z-index: 3000; overflow: hidden; border: 1px solid #f0f0f0;
    }
    .dropdown-box.show { display: block !important; }
    .dropdown-box a {
        display: block; padding: 12px 20px; color: #5a3921;
        text-decoration: none; border-bottom: 1px solid #f9f9f9;
        font-size: 14px;
    }
    .dropdown-box a:hover { background: #fff7ec; color: #d4a76a; }
</style>

<header class="main-header">
    <div class="header-navbar">
        <a href="mainpage.php" class="logo-brand">
            <img src="Bakery House Logo.png" alt="Bakery House">
            <span class="brand-name">Bakery House</span>
        </a>

        <ul class="nav-links">
            <?php $cur_file = basename($_SERVER['PHP_SELF']); ?>
            <li><a href="mainpage.php" class="<?= ($cur_file == 'mainpage.php') ? 'active' : '' ?>">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="about_us.php">About</a></li>
            <li><a href="contact_us.php">Contact</a></li>

            <li class="cart-icon-wrapper" onclick="window.location.href='cart.php'">
                <span>🛒 Cart</span>
                <span class="cart-badge">0</span>
            </li>

            <?php if ($isLoggedIn): ?>
                <li class="user-menu-wrapper">
                    <div class="user-icon-circle" onclick="toggleHeaderDropdown(event)">
                        <?= strtoupper(substr($userName, 0, 1)) ?>
                    </div>
                    <div class="dropdown-box" id="headerDropdownMenu">
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Log Out</a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="User_Login.php" style="background:#d4a76a; color:white; padding:8px 16px; border-radius:5px; text-decoration:none; font-weight:600;">Sign In</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>

<script>
    function toggleHeaderDropdown(e) {
        e.stopPropagation();
        const menu = document.getElementById('headerDropdownMenu');
        if (menu) menu.classList.toggle('show');
    }

    window.addEventListener('click', function(e) {
        const menu = document.getElementById('headerDropdownMenu');
        if (menu && menu.classList.contains('show')) {
            const avatar = document.querySelector('.user-icon-circle');
            if (avatar && !avatar.contains(e.target)) {
                menu.classList.remove('show');
            }
        }
    });
</script>