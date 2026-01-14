<?php
// header.php - 统一导航栏组件（最终版）

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userName   = $isLoggedIn ? ($_SESSION['user_name'] ?? 'User') : '';
$cur_file  = basename($_SERVER['PHP_SELF']);
?>

<!-- Header CSS（只负责 header 样式） -->
<link rel="stylesheet" href="header.css">     
      
<header class="main-header">
    <div class="header-navbar navbar">

        <!-- Logo / Brand -->
        <a href="mainpage.php" class="logo-brand">
            <img src="Bakery House Logo.png" alt="Bakery House">
            <span class="brand-name">Bakery House</span>
        </a>

        <!-- Navigation -->
        <ul class="nav-links">

            <li>
                <a href="mainpage.php"
                   class="<?= ($cur_file === 'mainpage.php') ? 'active' : '' ?>">
                    Home
                </a>
            </li>

            <li>
                <a href="menu.php"
                   class="<?= ($cur_file === 'menu.php') ? 'active' : '' ?>">
                    Menu
                </a>
            </li>

            <li>
                <a href="about_us.php"
                   class="<?= ($cur_file === 'about_us.php') ? 'active' : '' ?>">
                    About
                </a>
            </li>

            <li>
                <a href="contact_us.php"
                   class="<?= ($cur_file === 'contact_us.php') ? 'active' : '' ?>">
                    Contact
                </a>
            </li>

            <!-- Cart（永远显示，不做 session 判断） -->
            <li class="cart-icon cart-icon-wrapper">
                <a href="cart.php" class="cart-link">
                    🛒 Cart
                    <span class="cart-count">
                        <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
                    </span>
                </a>
            </li>

            <!-- User -->
            <?php if ($isLoggedIn): ?>
                <li class="user-menu-wrapper">
                    <div class="user-icon-circle" id="userAvatar">
                        <?= strtoupper(substr($userName, 0, 1)) ?>
                    </div>

                    <div class="dropdown-box" id="headerDropdownMenu">
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Log Out</a>
                    </div>
                </li>
            <?php else: ?>
                <li>
                    <a href="User_Login.php" class="sign-in-btn">
                        Sign In
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </div>
</header>

<!-- Header JS（只处理 header dropdown，不干扰其他 JS） -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- 1. 用户头像下拉菜单逻辑 ---
    const avatar = document.getElementById('userAvatar');
    const dropdown = document.getElementById('headerDropdownMenu');
    
    if (avatar && dropdown) {
        avatar.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });

        document.addEventListener('click', () => {
            dropdown.classList.remove('show');
        });
    }

    // --- 2. 核心：购物车数量同步逻辑 ---
    function updateHeaderCartCount() {
        // 从 localStorage 中读取名为 'bakeryCart' 的购物车数据
        const cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
        
        // 计算购物车中所有商品的总数量 (quantity)
        const totalItems = cart.reduce((sum, item) => sum + (parseInt(item.quantity) || 0), 0);
        
        // 找到 Header 中显示数字的元素
        const cartCountElement = document.querySelector('.cart-count');
        
        if (cartCountElement) {
            // 将计算出的总数更新到页面上
            cartCountElement.textContent = totalItems;
            
            // 如果你希望数量为 0 时隐藏红点，可以开启下面这段逻辑：
            // cartCountElement.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    }

    // 页面一加载就立刻执行一次同步
    updateHeaderCartCount();

    // 监听 'storage' 事件：
    // 当你在“菜单页”添加商品导致 localStorage 变化时，
    // 其他已经打开的页面（如“主页”）会自动感知并更新 Header 数字。
    window.addEventListener('storage', (e) => {
        if (e.key === 'bakeryCart') {
            updateHeaderCartCount();
        }
    });
    
    // 自定义事件：如果你在同一个页面的 JS 里修改了购物车，也可以触发这个刷新
    window.addEventListener('cartUpdated', updateHeaderCartCount);
});
</script>
