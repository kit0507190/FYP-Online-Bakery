<?php
// 1. 启动会话并引入数据库连接
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php'; // 👈 确保这个文件名和你的数据库连接文件名一致

// 2. 如果没登录，直接跳转回登录页
if (!isset($_SESSION['user_id'])) {
    header("Location: User_Login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 3. 从数据库中获取该用户的购物车商品 (ID 和 数量)
try {
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM cart_items WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $db_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_items = [];
}

// 4. 将查到的数据存入一个 JavaScript 变量，方便下面 JS 调用
echo "<script>const dbCartItems = " . json_encode($db_items) . ";</script>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="footer.css">
    <title>Shopping Cart - BakeryHouse</title>

    <!-- 原本的 style 完全保留 -->
    <style>
        /* 基础样式 */
        :root {
            --main-color: #000000;
            --theme-color: #000000;
            --accent-color: #EFCE3D;
            --footer-color: #555;
            --footer-bg-color: #FFF7EC;
            --grey-color: #231a17;
            --grey-color-2: #888;
            --light-grey-color: #f1f1f1;
            --light-grey-color-2: #555555;
            --light-grey-color-3: #cccccc;
            --light-grey-color-4: #F1F2F6;
            --light-grey-color-5: #f1f7fb;
            --dark-grey-color: #333;
            --white-color: #fff;
            --black-color: #000;
            --offer-color: #ba0000;
            --purple-color: #c8a2c8;
            --yellow-color: #FFFF00;
            --bright-red: #EE4B2B;
            --line-through-color: #8c8c9a;
            --warning-color: #F70046;
            --border-color: #1d1d1d1a;
            --border-color-3: #cfcfcf;
            --border-color-2: #9b9b9b;
            --serif-font: 'AvenirNext LT Pro', serif;
            --serif-font-bold: 'AvenirNext LT Pro Bold', serif;
            --sans-serif-font: 'AvenirNext LT Pro', sans-serif;
            --main-font: 'BonvenoCF', sans-serif;
            --product-font: 'Josefin Sans', sans-serif;
            --default-font: 'AvenirNext LT Pro', sans-serif;
            --border-radius: 10px;
            --border-radius-2: 40px;
            --border-radius-3: 5px;
            --border-radius-4: 50px;
            --text-shadow: 1px 1px 2px #252525;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--main-font) !important;
            color: var(--main-color);
            font-weight: normal;
            margin: 0px;
            font-weight: 400;
            font-size: 16px;
            background: var(--white-color);
            letter-spacing: 0px;
            line-height: 1.4;
            overflow-y: scroll;
            overflow-x: hidden;
            padding-top: 80px; /* 为fixed header预留空间 */
            background-color: #fff7ec;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* 面包屑导航样式 - 与menu.html保持一致 */
.breadcrumb {
    margin: 25px 0 35px 0;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.breadcrumb-links {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: #666;
}

.breadcrumb-links a {
    color: #d4a76a;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}

.breadcrumb-links a:hover {
    color: #c2955a;
    text-decoration: underline;
}

.breadcrumb-separator {
    margin: 0 10px;
    color: #aaa;
    font-weight: 300;
}

.breadcrumb-current {
    color: #5a3921;
    font-weight: 600;
}
        
        /* Cart Content */
        .cart-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(90, 57, 33, 0.08);
            margin-bottom: 40px;
        }
        
        .cart-title {
            color: #5a3921;
            font-size: 32px;
            margin-bottom: 30px;
            text-align: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }
        
        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 50px 20px;
        }
        
        .empty-cart img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            opacity: 0.8;
        }
        
        .empty-cart h2 {
            color: #5a3921;
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .empty-cart p {
            color: #888;
            margin-bottom: 25px;
            font-size: 16px;
        }
        
        .continue-shopping {
            display: inline-block;
            background-color: #d4a76a;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .continue-shopping:hover {
            background-color: #c2955a;
        }
        
        /* Promo Section */
        .promo-section {
            background: linear-gradient(135deg, #f9f5f0 0%, #fff7ec 100%);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border: 2px dashed #d4a76a;
        }
        
        .promo-title {
            color: #5a3921;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .promo-input-group {
            display: flex;
            gap: 10px;
        }
        
        .promo-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .promo-input:focus {
            outline: none;
            border-color: #d4a76a;
        }
        
        .apply-promo-btn {
            background-color: #5a3921;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .apply-promo-btn:hover {
            background-color: #3d2616;
        }
        
        .promo-message {
            margin-top: 10px;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 5px;
            display: none;
        }
        
        .promo-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        
        .promo-error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        
        /* Cart Items */
        .cart-items {
            margin-bottom: 30px;
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 10px;
            margin-bottom: 15px;
            background: white;
            transition: box-shadow 0.3s;
        }
        
        .cart-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .cart-item-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }
        
        .cart-item-details {
            flex: 1;
        }
        
        .cart-item-name {
            color: #5a3921;
            font-size: 18px;
            margin-bottom: 8px;
        }
        
        .cart-item-price {
            color: #888;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .cart-item-total {
            color: #d4a76a;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .stock-info {
            font-size: 12px;
            margin-bottom: 12px;
        }
        
        .stock-warning {
            color: #e74c3c;
            font-weight: 600;
        }
        
        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .quantity-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .quantity-btn:hover {
            background-color: #f5f5f5;
            border-color: #d4a76a;
        }
        
        .quantity-input {
            width: 40px;
            height: 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }
        
        .remove-item {
            background-color: transparent;
            color: #e74c3c;
            border: 1px solid #e74c3c;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s;
        }
        
        .remove-item:hover {
            background-color: #e74c3c;
            color: white;
        }
        
        /* Cart Summary */
        .cart-summary {
            background: #f9f5f0;
            border-radius: 10px;
            padding: 25px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #ddd;
        }
        
        .summary-row:last-child {
            border-bottom: none;
        }
        
        .summary-total {
            font-size: 20px;
            font-weight: 600;
            color: #5a3921;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
        }
        
        .discount-row {
            color: #27ae60;
        }
        
        .checkout-btn {
            width: 100%;
            background-color: #d4a76a;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        .checkout-btn:hover {
            background-color: #c2955a;
        }
        
        .action-buttons {
            margin-top: 20px;
            text-align: center;
        }
        
        /* Recommended Products */
        .recommended-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }
        
        .section-title {
            font-size: 24px;
            color: #5a3921;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .recommended-products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .recommended-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .recommended-card:hover {
            transform: translateY(-5px);
        }
        
        .recommended-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .recommended-name {
            font-size: 16px;
            color: #5a3921;
            margin-bottom: 8px;
        }
        
        .recommended-price {
            color: #d4a76a;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .add-recommended-btn {
            background-color: #5a3921;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .add-recommended-btn:hover {
            background-color: #3d2616;
        }
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #d4a76a;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: none;
            font-size: 20px;
            cursor: pointer;
            display: none;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
            z-index: 999;
        }
        
        .back-to-top:hover {
            background-color: #c2955a;
            transform: translateY(-3px);
        }
        
        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 14px;
            display: none;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .toast.success {
            background-color: #27ae60;
        }
        
        .toast.error {
            background-color: #e74c3c;
        }
        
        /* Footer */
        footer {
            background-color: #5a3921;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }
        
        footer p {
            margin: 0;
            font-size: 14px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .cart-item {
                flex-direction: column;
                text-align: center;
            }
            
            .cart-item-image {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .cart-content {
                padding: 20px;
            }
            
            .promo-input-group {
                flex-direction: column;
            }
            
            .apply-promo-btn {
                width: 100%;
            }
            
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
            }
        }
    </style>

    <script>
    // 1. 🟢 这一步非常重要：把你 menu.js 顶部那个巨大的 products 数组完整复制到这里
    // 这样 cart.php 才知道 ID 为 1 的蛋糕叫什么名字、多少钱。
    const products = [
        /* 这里粘贴你 menu.js 里的 products 数组内容 */
        {
            id: 1,
            name: "A LITTLE SWEET",
            price: 98.00,
            image: "cake/A_Little_Sweet.jpg",
            // ... 其他产品
        },
        // ...
    ];

    // 2. 修改 loadCartItems 函数，让它读数据库传来的数据
    function loadCartItems() {
        // 🔴 找到你原来的这行并删掉：const cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
        
        // 🟢 改为使用 PHP 传过来的 dbCartItems
        const cart = dbCartItems; 
        const cartContainer = document.getElementById('cartItemsContainer');

        if (!cart || cart.length === 0) {
            cartContainer.innerHTML = '<div style="text-align:center; padding:50px;"><h3>Your cart is empty</h3><a href="menu.php" style="color:#d4a76a;">Go to Menu</a></div>';
            updateSummary(0);
            return;
        }

        let cartHTML = '';
        let subtotal = 0;

        cart.forEach(item => {
            // 根据数据库的 product_id 找到对应的详细信息
            const product = products.find(p => p.id == item.product_id);
            if (product) {
                const itemTotal = product.price * item.quantity;
                subtotal += itemTotal;
                
                cartHTML += `
                    <div class="cart-item">
                        <img src="${product.image}" alt="${product.name}">
                        <div class="item-details">
                            <h4>${product.name}</h4>
                            <p>RM ${product.price.toFixed(2)}</p>
                        </div>
                        <div class="item-quantity">
                            <button onclick="changeQty(${product.id}, -1)">-</button>
                            <span>${item.quantity}</span>
                            <button onclick="changeQty(${product.id}, 1)">+</button>
                        </div>
                        <div class="item-total">RM ${itemTotal.toFixed(2)}</div>
                        <button class="remove-btn" onclick="removeItem(${product.id})">🗑️</button>
                    </div>`;
            }
        });

        cartContainer.innerHTML = cartHTML;
        updateSummary(subtotal);
    }
</script>

    <!-- Header 样式 -->
    <link rel="stylesheet" href="header-styles.css">
</head>
<body>

<!-- ✅ Header（推荐之后用 include） -->
<?php include 'header.php'; ?>

<!-- Breadcrumb -->
<div class="container">
    <div class="breadcrumb">
        <div class="breadcrumb-links">
            <a href="index.php">Home</a>
            <span class="breadcrumb-separator">&gt;</span>
            <a href="menu.php">Menu</a>
            <span class="breadcrumb-separator">&gt;</span>
            <span class="breadcrumb-current">Shopping Cart</span>
        </div>
    </div>
</div>

<!-- Cart Content -->
<div class="container">
    <div class="cart-content">
        <h1 class="cart-title">Your Shopping Cart</h1>

        <div id="cartContainer">
            <!-- JS 动态生成 -->
        </div>

        <!-- Recommended Products -->
        <div class="recommended-section" id="recommendedSection" style="display: none;">
            <h2 class="section-title">You Might Also Like</h2>
            <div class="recommended-products" id="recommendedProducts"></div>
        </div>
    </div>
</div>

<!-- Back to Top -->
<button class="back-to-top" id="backToTop">↑</button>

<!-- Toast -->
<div class="toast" id="toast"></div>

<?php include 'footer.php'; ?>

<!-- ⚠️ 你的 JS 原封不动放回 -->
<script src="header-manager.js"></script>

<script>
        // Shopping cart with enhanced features
        let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
        let appliedPromo = null;

        // Available promo codes
        const promoCodes = {
            'WELCOME10': { discount: 0.1, type: 'percentage', minAmount: 20 },
            'BAKERY5': { discount: 5, type: 'fixed', minAmount: 15 },
            'SWEET15': { discount: 0.15, type: 'percentage', minAmount: 30 }
        };

        // Recommended products based on cart contents
        const recommendedProducts = [
            {
                id: 101,
                name: "Fresh Cream Puff",
                price: 4.50,
                image: "https://bakingamoment.com/wp-content/uploads/2022/02/IMG_0530-cream-puff.jpg",
                category: "pastry"
            },
            {
                id: 102,
                name: "Almond Croissant",
                price: 6.50,
                image: "https://images.unsplash.com/photo-1559620192-032c4bc4674e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60",
                category: "pastry"
            },
            {
                id: 103,
                name: "Fruit Tart",
                price: 8.00,
                image: "https://images.unsplash.com/photo-1488477181946-6428a0291777?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60",
                category: "pastry"
            },
            {
                id: 104,
                name: "Chocolate Brownie",
                price: 5.00,
                image: "https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60",
                category: "cookie"
            }
        ];

        // DOM elements
        const cartContainer = document.getElementById('cartContainer');
        const backToTop = document.getElementById('backToTop');
        const toast = document.getElementById('toast');
        const recommendedSection = document.getElementById('recommendedSection');
        const recommendedProductsContainer = document.getElementById('recommendedProducts');

        // Load cart items
        function loadCartItems() {
            if (cart.length === 0) {
                cartContainer.innerHTML = `
                    <div class="empty-cart">
                        <img src="https://images.unsplash.com/photo-1573865526739-10659fec78a5?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Empty Cart">
                        <h2>Your cart is empty</h2>
                        <p>Add some delicious bakery items to your cart!</p>
                        <a href="menu.php" class="continue-shopping">Continue Shopping</a>
                    </div>
                `;
                recommendedSection.style.display = 'none';
                return;
            }
            
            let cartHTML = `
                <div class="promo-section">
                    <h3 class="promo-title">Have a promo code?</h3>
                    <div class="promo-input-group">
                        <input type="text" class="promo-input" id="promoCode" placeholder="Enter promo code">
                        <button class="apply-promo-btn" id="applyPromo">Apply</button>
                    </div>
                    <div class="promo-message" id="promoMessage"></div>
                </div>
                
                <div class="cart-items" id="cartItems">
                    <!-- Cart items will be generated here -->
                </div>
                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">RM 0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee:</span>
                        <span>RM 5.00</span>
                    </div>
                    <div class="summary-row discount-row" id="discountRow" style="display: none;">
                        <span>Discount:</span>
                        <span id="discountAmount">-RM 0.00</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total:</span>
                        <span id="total">RM 0.00</span>
                    </div>
                    <button class="checkout-btn" id="checkoutBtn">Proceed to Checkout</button>
                    <div class="action-buttons">
                        <a href="menu.php" class="continue-shopping">Continue Shopping</a>
                    </div>
                </div>
            `;
            
            cartContainer.innerHTML = cartHTML;
            
            // Generate cart items
            const cartItems = document.getElementById('cartItems');
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                const stockStatus = getStockStatus(item.id);
                
                const itemHTML = `
                    <div class="cart-item" data-id="${item.id}">
                        <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                        <div class="cart-item-details">
                            <h3 class="cart-item-name">${item.name}</h3>
                            <p class="cart-item-price">RM ${item.price.toFixed(2)} each</p>
                            <p class="cart-item-total">Item Total: RM ${itemTotal.toFixed(2)}</p>
                            <p class="stock-info ${stockStatus === 'low' ? 'stock-warning' : ''}">
                                ${stockStatus === 'low' ? '⚠️ Low stock - order soon!' : '✓ In stock'}
                            </p>
                            <div class="cart-item-quantity">
                                <button class="quantity-btn minus" data-id="${item.id}">-</button>
                                <input type="text" class="quantity-input" value="${item.quantity}" readonly>
                                <button class="quantity-btn plus" data-id="${item.id}">+</button>
                            </div>
                            <button class="remove-item" data-id="${item.id}">Remove</button>
                        </div>
                    </div>
                `;
                cartItems.innerHTML += itemHTML;
            });
            
            // Update totals
            updateCartTotals();
            
            // Show recommended products
            loadRecommendedProducts();
            
            // Setup event listeners
            setupEventListeners();
        }

        // Get stock status for a product
        function getStockStatus(productId) {
            // Simulate stock status - in real app, this would come from backend
            const stockData = {
                1: 'high', 2: 'high', 3: 'low', 4: 'high', 5: 'high',
                6: 'high', 7: 'low', 8: 'high', 9: 'high', 10: 'high',
                11: 'high', 12: 'low', 13: 'high', 14: 'high', 15: 'high',
                16: 'high'
            };
            return stockData[productId] || 'high';
        }

        // Load recommended products
        function loadRecommendedProducts() {
            if (cart.length === 0) return;
            
            recommendedProductsContainer.innerHTML = '';
            recommendedProducts.forEach(product => {
                const productHTML = `
                    <div class="recommended-card">
                        <img src="${product.image}" alt="${product.name}" class="recommended-image">
                        <h4 class="recommended-name">${product.name}</h4>
                        <p class="recommended-price">RM ${product.price.toFixed(2)}</p>
                        <button class="add-recommended-btn" data-id="${product.id}">
                            Add to Cart
                        </button>
                    </div>
                `;
                recommendedProductsContainer.innerHTML += productHTML;
            });
            
            recommendedSection.style.display = 'block';
        }

        // Apply promo code
        function applyPromoCode() {
            const promoCode = document.getElementById('promoCode').value.trim().toUpperCase();
            const promoMessage = document.getElementById('promoMessage');
            
            if (!promoCode) {
                showPromoMessage('Please enter a promo code', 'error');
                return;
            }
            
            const promo = promoCodes[promoCode];
            if (!promo) {
                showPromoMessage('Invalid promo code', 'error');
                return;
            }
            
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            if (subtotal < promo.minAmount) {
                showPromoMessage(`Minimum order amount RM ${promo.minAmount} required`, 'error');
                return;
            }
            
            appliedPromo = { code: promoCode, ...promo };
            showPromoMessage(`Promo code applied! ${promoCode}`, 'success');
            updateCartTotals();
        }

        // Show promo message
        function showPromoMessage(message, type) {
            const promoMessage = document.getElementById('promoMessage');
            promoMessage.textContent = message;
            promoMessage.className = `promo-message ${type === 'success' ? 'promo-success' : 'promo-error'}`;
            promoMessage.style.display = 'block';
        }

        // Setup event listeners
        function setupEventListeners() {
            // Quantity buttons
            document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    updateQuantity(id, -1);
                });
            });
            
            document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    updateQuantity(id, 1);
                });
            }); 
            
            // Remove buttons
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    removeFromCart(id);
                });
            });
            
            // Checkout button
            const checkoutBtn = document.getElementById('checkoutBtn');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', () => {
                    if (cart.length > 0) {
                        window.location.href = 'payment.php';
                    }
                });
            }
            
            // Promo code
            const applyPromoBtn = document.getElementById('applyPromo');
            if (applyPromoBtn) {
                applyPromoBtn.addEventListener('click', applyPromoCode);
            }
            
            // Recommended products
            document.querySelectorAll('.add-recommended-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    addRecommendedToCart(id);
                });
            });
            
            // Back to top
            backToTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            // Promo code input - allow Enter key
            const promoCodeInput = document.getElementById('promoCode');
            if (promoCodeInput) {
                promoCodeInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        applyPromoCode();
                    }
                });
            }
        }

        // Add recommended product to cart
        function addRecommendedToCart(productId) {
            const product = recommendedProducts.find(p => p.id === productId);
            if (!product) return;
            
            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.image,
                    quantity: 1
                });
            }
            
            localStorage.setItem('bakeryCart', JSON.stringify(cart));
            loadCartItems();
            updateCartCount();
            showToast(`${product.name} added to cart!`, 'success');
        }

        // Update quantity
        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeFromCart(productId);
                } else {
                    localStorage.setItem('bakeryCart', JSON.stringify(cart));
                    loadCartItems();
                    updateCartCount();
                }
            }
        }

        // Remove from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            localStorage.setItem('bakeryCart', JSON.stringify(cart));
            loadCartItems();
            updateCartCount();
            showToast('Item removed from cart', 'error');
        }

        // Update cart totals
        function updateCartTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const deliveryFee = 5.00;
            let discount = 0;
            
            // Calculate discount if promo applied
            if (appliedPromo) {
                if (appliedPromo.type === 'percentage') {
                    discount = subtotal * appliedPromo.discount;
                } else {
                    discount = appliedPromo.discount;
                }
                // Ensure discount doesn't exceed subtotal
                discount = Math.min(discount, subtotal);
            }
            
            const total = subtotal + deliveryFee - discount;
            
            const subtotalElement = document.getElementById('subtotal');
            const totalElement = document.getElementById('total');
            
            if (subtotalElement) {
                subtotalElement.textContent = `RM ${subtotal.toFixed(2)}`;
            }
            
            if (totalElement) {
                totalElement.textContent = `RM ${total.toFixed(2)}`;
            }
            
            // Update discount display
            const discountRow = document.getElementById('discountRow');
            const discountAmount = document.getElementById('discountAmount');
            
            if (discount > 0 && discountRow && discountAmount) {
                discountAmount.textContent = `-RM ${discount.toFixed(2)}`;
                discountRow.style.display = 'flex';
            } else if (discountRow) {
                discountRow.style.display = 'none';
            }
        }

        // Update cart count in header
        function updateCartCount() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            
            // 更新header中的购物车数量
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = totalItems;
            }
            
            // 保存到localStorage
            localStorage.setItem('cartItemCount', totalItems.toString());
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            toast.textContent = message;
            toast.className = `toast ${type}`;
            toast.style.display = 'block';
            
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        // Scroll event for back to top button
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });

        // Initialize page
        function initPage() {
            loadCartItems();
            updateCartCount();
            
            // Load any saved promo code
            const savedPromo = localStorage.getItem('appliedPromo');
            if (savedPromo) {
                try {
                    appliedPromo = JSON.parse(savedPromo);
                    const promoCodeInput = document.getElementById('promoCode');
                    if (promoCodeInput && appliedPromo.code) {
                        promoCodeInput.value = appliedPromo.code;
                        applyPromoCode();
                    }
                } catch (e) {
                    console.error('Error parsing saved promo:', e);
                }
            }
            
            // 监听购物车变化
            window.addEventListener('storage', (e) => {
                if (e.key === 'bakeryCart') {
                    cart = JSON.parse(e.newValue) || [];
                    loadCartItems();
                    updateCartCount();
                }
            });
        }

        // 页面加载完成后初始化
        document.addEventListener('DOMContentLoaded', () => {
            // 等待header加载完成后初始化购物车
            setTimeout(() => {
                initPage();
            }, 100);
        });
    </script>

</body>
</html>
