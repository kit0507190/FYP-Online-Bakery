<?php
// header.php - 统一导航栏组件（已修复多账号同步 Bug 版）

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userName   = $isLoggedIn ? ($_SESSION['user_name'] ?? 'User') : '';
$cur_file  = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="header.css">

<header class="main-header">
    <div class="header-navbar navbar">

        <a href="mainpage.php" class="logo-brand">
            <img src="Bakery House Logo.png" alt="Bakery House">
            <span class="brand-name">Bakery House</span>
        </a>

        <ul class="nav-links">
            <li><a href="mainpage.php" class="<?= ($cur_file === 'mainpage.php') ? 'active' : '' ?>">Home</a></li>
            <li><a href="menu.php" class="<?= ($cur_file === 'menu.php') ? 'active' : '' ?>">Menu</a></li>
            <li><a href="about_us.php" class="<?= ($cur_file === 'about_us.php') ? 'active' : '' ?>">About</a></li>
            <li><a href="contact_us.php" class="<?= ($cur_file === 'contact_us.php') ? 'active' : '' ?>">Contact</a></li>

            <li class="cart-icon cart-icon-wrapper">
                <a href="cart.php" class="cart-link" onclick="return checkCartLogin(event)">
                    🛒 Cart <span class="cart-count">0</span>
                </a>
            </li>

            <?php if ($isLoggedIn): ?>
                <li class="user-menu-wrapper">
                    <div class="user-icon-circle" id="userAvatar">
                        <?= strtoupper(substr($userName, 0, 1)) ?>
                    </div>
                    <div class="dropdown-box" id="headerDropdownMenu">
                        <a href="profile.php">Profile</a>
                        <a href="favorites.php">My Favorites</a>
                        <a href="purchase_history.php">Purchase History</a>
                        <a href="logout.php">Log Out</a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="User_Login.php" class="sign-in-btn">Sign In</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>

<script>
// 1. 全局登录开关
window.isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

/**
 * 🚀 核心函数：更新 Header 显示的数量
 * 增加处理：如果数量为 0，自动隐藏红点；如果大于 0，则显示。
 */
function updateHeaderCartCount() {
    const cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + (parseInt(item.quantity) || 0), 0);
    
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = totalItems;
        
        // 关键修复：处理显示和隐藏逻辑
        if (totalItems > 0) {
            cartCountElement.style.display = 'flex'; // 或者 'block'，取决于你的 CSS
        } else {
            cartCountElement.style.display = 'none';
        }
    }
}

/**
 * 🚀 核心函数：强制从服务器同步
 * 修改点：无论同步成功还是失败，最后都要调用一次 updateHeaderCartCount()
 */
async function refreshCartFromServer() {
    if (!window.isLoggedIn) {
        updateHeaderCartCount(); 
        return;
    }

    try {
        const response = await fetch('sync_cart.php?action=fetch');
        const result = await response.json();
        
        if (result.status === 'success') {
            const serverCart = result.cart || [];
            localStorage.setItem('bakeryCart', JSON.stringify(serverCart));
        }
    } catch (e) {
        console.error("Header cart sync failed:", e);
    } finally {
        // 🚀 无论 fetch 成功还是报错，都要刷新 UI 显示本地或最新数据
        updateHeaderCartCount();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // 1. 🚀 第一步：立即显示本地 LocalStorage 的数据（让用户进页面瞬间看到数字）
    updateHeaderCartCount();

    // 2. 🚀 第二步：再去后台同步最新的数据库数据
    refreshCartFromServer();

    // 用户头像下拉菜单逻辑
    const avatar = document.getElementById('userAvatar');
    const dropdown = document.getElementById('headerDropdownMenu');
    if (avatar && dropdown) {
        avatar.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });
        document.addEventListener('click', () => dropdown.classList.remove('show'));
    }

    // 监听其他标签页的变动（例如在另一个窗口加了购物车，主页也要动）
    window.addEventListener('storage', (e) => {
        if (e.key === 'bakeryCart') updateHeaderCartCount();
    });
});

// 暴露一个全局函数，方便 menu.js 或 cart.php 手动触发更新
window.refreshHeaderCart = updateHeaderCartCount;

function checkCartLogin(event) {
    if (!window.isLoggedIn) {
        event.preventDefault();
        const modal = document.getElementById('loginPromptModal');
        if (modal) modal.style.display = 'flex';
        return false;
    }
    return true;
}

function closeLoginPrompt() {
    const modal = document.getElementById('loginPromptModal');
    if (modal) modal.style.display = 'none';
}
</script>

<div class="modal" id="loginPromptModal" style="display:none; z-index: 9999; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
    <div class="modal-content" style="max-width: 350px; text-align: center; padding: 30px; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.2); margin: auto;">
        <div style="font-size: 50px; margin-bottom: 15px;">🧁</div>
        <h2 style="color: #5a3921; margin-bottom: 10px;">Please Sign In</h2>
        <p style="color: #888; margin-bottom: 25px; line-height: 1.5;">You need to log in to your account before viewing your cart or adding items.</p>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <button onclick="window.location.href='User_Login.php'" style="background: #d4a76a; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 16px;">Go to Login</button>
            <button onclick="closeLoginPrompt()" style="background: none; border: none; color: #aaa; cursor: pointer; text-decoration: underline; font-size: 14px;">Maybe Later</button>
        </div>
    </div>
</div>