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
// Global flag and cart count function
window.isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

function updateHeaderCartCount() {
    const cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
    
    // 🟢 修改这里：不再用 cart.length，改用累加数量
    const totalItems = cart.reduce((sum, item) => sum + parseInt(item.quantity || 0), 0);

    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = totalItems;
        cartCountElement.style.display = totalItems > 0 ? 'flex' : 'none';
    }
}

/**
 * Fetch latest cart from server and update localStorage + UI
 */
async function refreshCartFromServer() {
    if (!window.isLoggedIn) {
        updateHeaderCartCount();
        return;
    }

    try {
        const response = await fetch('sync_cart.php?action=fetch');
        if (!response.ok) throw new Error('Fetch failed');

        const result = await response.json();

        if (result.status === 'success') {
            const serverCart = result.cart || [];
            // Save to localStorage so menu.js / cart.php see the same data
            localStorage.setItem('bakeryCart', JSON.stringify(serverCart));
            updateHeaderCartCount();
        }
    } catch (err) {
        console.warn("Cart sync failed:", err);
        // Still show whatever is in localStorage as fallback
        updateHeaderCartCount();
    }
}

// Run once page is ready
document.addEventListener('DOMContentLoaded', () => {
    // 1. Show local data immediately (fast first paint)
    updateHeaderCartCount();

    // 2. Then get fresh data from server
    refreshCartFromServer();

    // User dropdown menu
    const avatar = document.getElementById('userAvatar');
    const dropdown = document.getElementById('headerDropdownMenu');
    if (avatar && dropdown) {
        const toggleDropdown = (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        };
        avatar.addEventListener('click', toggleDropdown);
        document.addEventListener('click', () => dropdown.classList.remove('show'));
    }

    // Listen for localStorage changes from other tabs/windows
    window.addEventListener('storage', (e) => {
        if (e.key === 'bakeryCart') {
            updateHeaderCartCount();
        }
    });
});

// Expose globally so other pages (cart.php, menu.js) can call it directly
window.updateHeaderCartCount = updateHeaderCartCount;

// Cart link login check
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