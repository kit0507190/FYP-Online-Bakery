<?php
// cart.php - 购物车主页面（updated 2026 - trust server as source of truth）
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

// 1. 强制登录检查
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit;
}

?>

<?php
if (isset($_SESSION['checkout_error'])) {
    $errorMsg = $_SESSION['checkout_error'];
    unset($_SESSION['checkout_error']);  // Clear so it doesn't stick around
?>
    <div class="stock-error-alert">
        <i class="fas fa-exclamation-triangle"></i>
        <div class="alert-content">
            <strong>Oops! Not Enough Stock</strong>
            <p><?php echo htmlspecialchars($errorMsg); ?></p>
            <p>Please reduce the quantity or remove the item(s) shown above, then try checking out again.</p>
            <a href="checkout.php" class="btn-retry">Back to Checkout</a>  <!-- Or whatever your checkout page is -->
        </div>
    </div>
<?php } ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - BakeryHouse</title>
    <link rel="stylesheet" href="cart.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="header-styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="cart-content">
        <h1 class="cart-title">Shopping Cart</h1>
        <hr style="width: 60px; border: none; border-top: 3px solid #d4a76a; margin: 0 auto 20px; border-radius: 10px;">

        <div id="cartContainer"></div>

        <div class="recommended-section" id="recommendedSection" style="display: none;">
            <h2 class="section-title">You Might Also Like</h2>
            <div class="recommended-products" id="recommendedProducts"></div>
        </div>
    </div>
</div>

<button class="back-to-top" id="backToTop">↑</button>
<div class="toast" id="toast"></div>

<div id="customClearModal" class="custom-modal-overlay">
    <div class="custom-modal-box">
        <div class="modal-icon-wrapper">
    <span style="font-size: 40px; font-weight: bold; font-family: Arial, sans-serif;">!</span> 
</div>
        <h2 class="modal-title-custom">Clear Your Cart?</h2>
        <p class="modal-message-custom">
            Are you sure you want to remove all items from your cart? <br>This action cannot be undone.
        </p>
        <div class="modal-actions-custom">
            <button id="confirmClearBtn" class="btn-confirm-delete">Yes, Clear Cart</button>
            <button id="cancelClearBtn" class="btn-cancel-delete">Cancel</button>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
// --- 核心变更：不再在页面加载时直接读取 localStorage ---
// 我们让服务器成为唯一真相来源
let cart = [];  // 页面刚加载时强制为空 — 等待服务器数据

const cartContainer = document.getElementById('cartContainer');

function showToast(msg) {
    const toast = document.getElementById('toast');
    if (!toast) {
        console.warn("Toast #toast element not found in DOM");
        return;
    }
    
    toast.textContent = msg;
    toast.style.display = 'block';
    
    setTimeout(() => {
        toast.style.display = 'none';
    }, 2800);
}

// --- 同步本地 cart 到数据库 ---
async function syncCartToDB() {
    if (!window.isLoggedIn) return;

    try {
        const response = await fetch('sync_cart.php?action=update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart: cart })
        });

        const result = await response.json();

        if (!response.ok || result.status !== 'success') {
            throw new Error(result.message || 'Sync failed');
        }

        // ── New: handle stock adjustments ──
        if (result.adjusted && result.adjusted.length > 0) {
            let msg = "The following items were adjusted due to low stock:\n";
            result.adjusted.forEach(adj => {
                msg += `• ${adj.name || 'Item'} (wanted ${adj.requested}, only ${adj.available} left) → set to ${adj.set_to}\n`;
            });
            showToast(msg.trim());
            
            // Optional: reload from server to be in sync
            initPage();
            return;
        }

        console.log("Database sync successful.");
    } catch (e) {
        console.error("Sync error:", e);
        showToast("Failed to save cart — some changes may be lost");
    }
}
// --- 页面初始化：总是优先从服务器加载购物车 ---
async function initPage() {
    if (!window.isLoggedIn) {
        loadCartItems(); // 显示空购物车
        return;
    }

    try {
        const response = await fetch('sync_cart.php?action=fetch');
        const result = await response.json();

        if (result.status === 'success') {
            cart = result.cart || [];  // ← 信任服务器返回的数据
            localStorage.setItem('bakeryCart', JSON.stringify(cart));
        } else {
            console.warn("Server returned non-success:", result);
            cart = [];
            localStorage.removeItem('bakeryCart');
        }
    } catch (err) {
        console.error("Failed to load cart from server", err);
        // 失败时使用本地缓存作为 fallback（但显示提示）
        cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
        showToast("Couldn't connect to server — showing last known cart");
    }

    loadCartItems();
}

// --- 渲染购物车 ---
function loadCartItems() {
    if (cart.length === 0) {
        cartContainer.innerHTML = `
            <div class="empty-cart">
                <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=500" alt="Empty Cart">
                <h2>Your cart is empty</h2>
                <p>Add some delicious bakery items to your cart!</p>
                <a href="menu.php" class="continue-shopping">Continue Shopping</a>
            </div>`;
        updateHeaderCount();
        return;
    }

    let itemsHTML = `
        <div class="cart-list-header">
            <span class="header-label-total">TOTAL</span>
        </div>
        <div class="cart-items">`;
    
    cart.forEach(item => {
        const itemTotal = (parseFloat(item.price) * parseInt(item.quantity)).toFixed(2);
        itemsHTML += `
            <div class="cart-item">
                <img src="${item.image}" class="cart-item-image">
                <div class="cart-item-details">
                    <div class="cart-item-header">
                        <h3 class="cart-item-name">${item.name}</h3>
                        <p class="cart-item-total">RM ${itemTotal}</p>
                    </div>
                    
                    <p class="cart-item-price">RM ${parseFloat(item.price).toFixed(2)} each</p>
                    
                    <div class="cart-item-quantity">
                        <button class="quantity-btn" onclick="updateQty(${item.id}, -1)">-</button>
                        <input type="text" class="quantity-input" value="${item.quantity}" readonly>
                        <button class="quantity-btn" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                    <button class="remove-item" onclick="removeItem(${item.id})">Remove</button>
                </div>
            </div>`;
    });
    itemsHTML += '</div>';

    const subtotal = cart.reduce((sum, i) => sum + (parseFloat(i.price) * parseInt(i.quantity)), 0).toFixed(2);
    const total = (parseFloat(subtotal) + 5.00).toFixed(2);

    itemsHTML += `
        <div class="cart-summary">
            <div class="summary-row"><span>Subtotal:</span><span>RM ${subtotal}</span></div>
            <div class="summary-row"><span>Delivery Fee:</span><span>RM 5.00</span></div>
            <div class="summary-row summary-total"><span>Total:</span><span class="final-total-amount">RM ${total}</span></div>
            <button class="checkout-btn" onclick="window.location.href='payment.php'">Proceed to Checkout</button>
            <div class="action-buttons">
                <a href="menu.php" class="continue-shopping">Continue Shopping</a>
                <button class="clear-cart-btn" onclick="clearCart()">Clear Cart</button>
            </div>
        </div>`;

    cartContainer.innerHTML = itemsHTML;
    updateHeaderCount();
}

// --- 清空购物车 ---
function clearCart() {
    if (cart.length === 0) {
        showToast("Your cart is already empty!");
        return;
    }

    // 以前是 confirm()，现在改成让弹窗显示
    const modal = document.getElementById('customClearModal');
    if (modal) {
        modal.classList.add('active');
    }
}

// --- 修改数量 / 删除 ---
function updateQty(id, change) {
    const item = cart.find(i => i.id == id);
    if (!item) return;

    let newQty = parseInt(item.quantity) + change;

    if (newQty <= 0) {
        removeItem(id);
        return;
    }

    // ── Improved stock check ──
    if (change > 0) {
        const maxStock = item.maxStock ?? item.stock ?? 9999; // fallback
        if (newQty > maxStock) {
            showToast(`Only ${maxStock} available in stock`);
            return;
        }
    }

    item.quantity = newQty;
    finalizeChange();
}

function removeItem(id) {
    cart = cart.filter(i => i.id != id);
    finalizeChange();
}

function finalizeChange() {
    localStorage.setItem('bakeryCart', JSON.stringify(cart));
    loadCartItems();
    syncCartToDB();
}

function updateHeaderCount() {
    const totalItems = cart.reduce((sum, item) => sum + parseInt(item.quantity || 0), 0);
    
    const countEl = document.querySelector('.cart-count');
    if (countEl) {
        countEl.textContent = totalItems;
        countEl.style.display = totalItems > 0 ? 'flex' : 'none';
    }
}

// === 重要：如果你的系统有登出功能，请在登出时调用这个函数 ===
function clearCartOnLogout() {
    localStorage.removeItem('bakeryCart');
    cart = [];
    // 可以在这里添加跳转或其他逻辑
}

// 初始化
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(initPage, 100);

    // --- 新增：处理自定义弹窗的按钮点击 ---
    const modal = document.getElementById('customClearModal');
    const confirmBtn = document.getElementById('confirmClearBtn');
    const cancelBtn = document.getElementById('cancelClearBtn');

    // 如果用户点 "Yes, Clear Cart"
    if (confirmBtn) {
        confirmBtn.addEventListener('click', () => {
            // 执行真正的清空逻辑
            cart = [];
            localStorage.setItem('bakeryCart', JSON.stringify(cart));
            loadCartItems();
            syncCartToDB();
            showToast("Cart has been cleared");
            
            // 关闭弹窗
            modal.classList.remove('active');
        });
    }

    // 如果用户点 "Cancel"
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            modal.classList.remove('active');
        });
    }
});
</script>
</body>
</html>