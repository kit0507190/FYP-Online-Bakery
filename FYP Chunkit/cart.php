<?php
// cart.php - 购物车主页面
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

<?php include 'footer.php'; ?>

<script>
    // --- 1. 变量初始化 ---
    let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
    const cartContainer = document.getElementById('cartContainer');

    // --- 2. 核心：同步函数 (把本地的操作发给数据库) ---
    async function syncCartToDB() {
        if (!window.isLoggedIn) return; 
        try {
            await fetch('sync_cart.php?action=update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart: cart })
            });
            console.log("Database sync successful.");
        } catch (e) {
            console.error("Sync error:", e);
        }
    }

    // --- 3. 核心：页面初始化 (确保账号数据隔离) ---
    async function initPage() {
        if (window.isLoggedIn) {
            try {
                // 🚀 从数据库拿当前账号的“真数据”
                const response = await fetch('sync_cart.php?action=fetch');
                const result = await response.json();
                
                if (result.status === 'success') {
                    // 🚀 强制用数据库的结果覆盖本地
                    cart = result.cart || [];
                    localStorage.setItem('bakeryCart', JSON.stringify(cart));
                }
            } catch (e) {
                console.error("Fetch error:", e);
            }
        }
        loadCartItems();
    }

    // --- 4. 渲染购物车 (已修复空购物车显示问题) ---
    function loadCartItems() {
        if (cart.length === 0) {
            // 🚀 这里就是之前丢失的代码：当购物车为空时显示的 HTML
            cartContainer.innerHTML = `
                <div class="empty-cart">
                    <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=500" alt="Empty Cart">
                    <h2>Your cart is empty</h2>
                    <p>Add some delicious bakery items to your cart!</p>
                    <a href="menu.php" class="continue-shopping">Continue Shopping</a>
                </div>`;
            updateHeaderCount(); // 同时也更新一下导航栏的数量显示
            return;
        }

        // --- 以下是原本有的逻辑：显示购物车商品 ---
        const displayCart = cart;
        
        let itemsHTML = `
            <div class="cart-list-header">
                <span class="header-label-total">TOTAL</span>
            </div>
            <div class="cart-items">`;
        
        displayCart.forEach(item => {
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

        // ... (底部的计算逻辑保持不变)
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
                </div>
            </div>`;

        cartContainer.innerHTML = itemsHTML;
        updateHeaderCount();
    }

    // --- 5. 修改数量和删除逻辑 (基于 ID 操作，不受排序影响) ---
    function updateQty(id, change) {
        const item = cart.find(i => i.id == id);
        if (item) {
            item.quantity = parseInt(item.quantity) + change;
            if (item.quantity <= 0) {
                removeItem(id);
            } else {
                finalizeChange();
            }
        }
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
    const count = cart.length;
    const countEl = document.querySelector('.cart-count');
    if (countEl) {
        countEl.textContent = count;
        countEl.style.display = count > 0 ? 'flex' : 'none';
    }
}

    // 初始化加载
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(initPage, 100);
    });
</script>
</body>
</html>