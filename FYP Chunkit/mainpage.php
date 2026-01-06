<?php
session_start();
require_once 'config.php';

// 检查用户是否登录
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse - Home</title>
    <style>
        /* 原来的所有CSS样式保持不变，只在这里添加新样式 */
        
        /* ========== 新增的导航栏样式 ========== */
        
        /* 用户菜单容器 */
        .user-menu {
            position: relative;  /* 相对定位，下拉菜单会基于这个元素定位 */
            display: inline-block;  /* 行内块元素，可以和其他导航链接并排 */
        }
        
        /* 用户图标 */
        .user-icon {
            width: 45px;  /* 宽度45像素 */
            height: 45px;  /* 高度45像素 */
            background: linear-gradient(135deg, #d4a574, #b8864e);  /* 渐变背景色 */
            border-radius: 50%;  /* 圆形（50%就是完美的圆） */
            display: flex;  /* 使用flexbox布局 */
            align-items: center;  /* 垂直居中 */
            justify-content: center;  /* 水平居中 */
            color: white;  /* 文字颜色白色 */
            font-weight: bold;  /* 文字加粗 */
            cursor: pointer;  /* 鼠标变成手型，表示可以点击 */
            border: 3px solid #f8e8d8;  /* 边框 */
        }
        
        /* 鼠标悬停在用户图标上时 */
        .user-icon:hover {
            transform: scale(1.1);  /* 放大1.1倍 */
        }
        
        /* 下拉菜单 - 默认隐藏 */
        .dropdown-menu {
            display: none;  /* 默认不显示 */
            position: absolute;  /* 绝对定位 */
            right: 0;  /* 靠右对齐 */
            top: 100%;  /* 在用户图标下方 */
            background: white;  /* 白色背景 */
            min-width: 150px;  /* 最小宽度150像素 */
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);  /* 阴影效果 */
            border-radius: 10px;  /* 圆角 */
            z-index: 1000;  /* 确保在最上层 */
            margin-top: 10px;  /* 距离用户图标10像素 */
        }
        
        /* 显示下拉菜单的类 */
        .dropdown-menu.show {
            display: block;  /* 显示为块级元素 */
        }
        
        /* 下拉菜单中的链接 */
        .dropdown-menu a {
            display: block;  /* 块级元素，占满整行 */
            padding: 12px 20px;  /* 内边距：上下12px，左右20px */
            text-decoration: none;  /* 去掉下划线 */
            color: #5a3921;  /* 文字颜色 */
            border-bottom: 1px solid #f0f0f0;  /* 底部边框，作为分隔线 */
        }
        
        /* 鼠标悬停在菜单链接上 */
        .dropdown-menu a:hover {
            background: #fdf6f0;  /* 浅色背景 */
            color: #d4a574;  /* 改变文字颜色 */
        }
        
        /* 最后一个链接不要底部边框 */
        .dropdown-menu a:last-child {
            border-bottom: none;  /* 去掉底部边框 */
        }
        
        /* ========== 原来的所有CSS样式保持不变 ========== */
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
            
            --bs-body-font-family: var(--main-font);
            --bs-body-font-size: 16px;
            --bs-body-font-weight: 400;
            --bs-body-line-height: 1.4;
            --bs-body-color: var(--main-color);
            --bs-body-text-align: left;
            --bs-body-bg: var(--white-color);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
            font-weight: var(--bs-body-font-weight);
            line-height: var(--bs-body-line-height);
            color: var(--bs-body-color);
            text-align: var(--bs-body-text-align);
            background-color: var(--bs-body-bg);
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
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
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background-color: #fff;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo img {
            height: 70px;
            width: auto;
            transition: opacity 0.3s;
        }
        
        .logo img:hover {
            opacity: 0.8;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            gap: 25px;
            align-items: center;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #5a3921;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }
        
        .nav-links a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: #d4a76a;
            transition: width 0.3s;
        }
        
        .nav-links a:hover:after {
            width: 100%;
        }
        
        .nav-links a:hover {
            color: #d4a76a;
        }
        
        .nav-links a.active {
            color: #d4a76a;
        }
        
        .nav-links a.active:after {
            width: 100%;
        }
        
        .cart-icon {
            position: relative;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .signup-btn {
            background-color: #d4a76a;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .signup-btn:hover {
            background-color: #c2955a;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
                        url('https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 180px 0 100px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        
        .hero p {
            font-size: 24px;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease 0.2s;
        }
        
        .hero-btn {
            background-color: #d4a76a;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease 0.4s;
        }
        
        .hero-btn:hover {
            background-color: #c2955a;
        }
        
        /* Animation Classes */
        .fade-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        
        .slide-in-left {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }
        
        .slide-in-right {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }
        
        .scale-in {
            opacity: 1 !important;
            transform: scale(1) !important;
        }
        
        /* Section Styles */
        .section {
            padding: 80px 0;
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }
        
        .section.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        .section-title {
            font-size: 32px;
            margin-bottom: 30px;
            text-align: center;
            color: #5a3921;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            width: 80px;
            height: 3px;
            background-color: #d4a76a;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        
        /* Categories Section */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .category-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            cursor: pointer;
            text-align: center;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s ease;
        }
        
        .category-card:nth-child(1) { transition-delay: 0.1s; }
        .category-card:nth-child(2) { transition-delay: 0.2s; }
        .category-card:nth-child(3) { transition-delay: 0.3s; }
        .category-card:nth-child(4) { transition-delay: 0.4s; }
        
        .category-card:hover {
            transform: translateY(-5px);
        }
        
        .category-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .category-card:hover .category-image {
            transform: scale(1.05);
        }
        
        .category-name {
            padding: 20px;
            font-size: 20px;
            font-weight: 600;
            color: #5a3921;
        }
        
        /* Featured Products */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            cursor: pointer;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s ease;
        }
        
        .product-card:nth-child(1) { transition-delay: 0.1s; }
        .product-card:nth-child(2) { transition-delay: 0.2s; }
        .product-card:nth-child(3) { transition-delay: 0.3s; }
        .product-card:nth-child(4) { transition-delay: 0.4s; }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .product-card:hover .product-image {
            transform: scale(1.05);
        }
        
        .product-info {
            padding: 15px;
        }
        
        .product-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .product-price {
            color: #d4a76a;
            font-weight: bold;
            font-size: 16px;
        }
        
        /* About Section */
        .about-content {
            display: flex;
            align-items: center;
            gap: 40px;
        }
        
        .about-image {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.8s ease;
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .about-text {
            flex: 1;
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.8s ease 0.2s;
        }
        
        .about-text h3 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #5a3921;
        }
        
        .about-text p {
            margin-bottom: 15px;
            font-size: 16px;
            line-height: 1.7;
        }
        
        /* Testimonials */
        .testimonials {
            background-color: #fff7ec;
            padding: 80px 0;
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .testimonial-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s ease;
        }
        
        .testimonial-card:nth-child(1) { transition-delay: 0.1s; }
        .testimonial-card:nth-child(2) { transition-delay: 0.2s; }
        .testimonial-card:nth-child(3) { transition-delay: 0.3s; }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .testimonial-author {
            font-weight: 600;
            color: #5a3921;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(rgba(90, 57, 33, 0.8), rgba(90, 57, 33, 0.8)), 
                        url('https://images.unsplash.com/photo-1555507032-6f4d1c1e1e1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 0;
        }
        
        .cta-content {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        
        .cta-content h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        
        .cta-content p {
            font-size: 18px;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-btn {
            background-color: #d4a76a;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .cta-btn:hover {
            background-color: #c2955a;
        }
        
        /* Footer */
        footer {
            background-color: #5a3921;
            color: white;
            text-align: center;
            padding: 30px 0;
        }
        
        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        
        .footer-logo img {
            height: 60px;
            width: auto;
        }
        
        .footer-links {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #d4a76a;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-links {
                gap: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .hero h1 {
                font-size: 36px;
            }
            
            .hero p {
                font-size: 18px;
            }
            
            .about-content {
                flex-direction: column;
            }
            
            .about-image, .about-text {
                transform: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation - 这是唯一修改的部分 -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="mainpage.php" class="logo">
                    <img src="Bakery House Logo.png" alt="BakeryHouse">
                </a>
                <ul class="nav-links">
                    <li><a href="#" class="active">Home</a></li>
                    <li><a href="menu.html">Menu</a></li>
                    <li><a href="about_us.html">About</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li class="cart-icon" id="cartIcon">
                        <span>🛒 Cart</span>
                        <span class="cart-count">0</span>
                    </li>
                    
                    <?php if ($isLoggedIn): ?>
                        <!-- 只有一个用户图标菜单 -->
                        <li class="user-menu">
                            <!-- 用户图标：显示用户名字的首字母 -->
                            <div class="user-icon" onclick="toggleDropdown()">
                                <?php echo strtoupper(substr($userName, 0, 1)); ?>
                            </div>
                            
                            <!-- 下拉菜单：点击用户图标时显示 -->
                            <div class="dropdown-menu" id="dropdownMenu">
                                <!-- Profile链接 -->
                                <a href="profile.php">Profile</a>
                                <!-- Log Out链接 -->
                                <a href="index.html">Log Out</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <!-- 未登录时显示登录按钮 -->
                        <li>
                            <a href="User_Login.php" class="signup-btn">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- 从这里开始，所有内容保持不变 -->
    <section class="hero">
        <div class="hero-content">
            <h1 id="heroTitle">Happiness Is a Piece of Cake</h1>
            <p id="heroSubtitle">Our Favorite Bakery House in Town</p>
            <button class="hero-btn" id="heroBtn" onclick="window.location.href='menu.html'">Explore Our Menu</button>
        </div>
    </section>

    <section class="section" id="categories">
        <div class="container">
            <h2 class="section-title">Shop by Categories</h2>
            <div class="categories-grid">
                <div class="category-card" onclick="window.location.href='menu.html?category=cake'">
                    <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Cakes" class="category-image">
                    <div class="category-name">Cakes</div>
                </div>
                <div class="category-card" onclick="window.location.href='menu.html?category=bread'">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Bread" class="category-image">
                    <div class="category-name">Bread</div>
                </div>
                <div class="category-card" onclick="window.location.href='menu.html?category=pastry'">
                    <img src="https://images.unsplash.com/photo-1559620192-032c4bc4674e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Pastries" class="category-image">
                    <div class="category-name">Pastries</div>
                </div>
                <div class="category-card" onclick="window.location.href='menu.html?category=cookie'">
                    <img src="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Cookies" class="category-image">
                    <div class="category-name">Cookies</div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="featured">
        <div class="container">
            <h2 class="section-title">Best Selling Products</h2>
            <div class="products-grid" id="featuredProducts">
                <!-- Featured products will be dynamically loaded -->
            </div>
        </div>
    </section>

    <section class="section" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1558961363-fa8fdf82db35?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="About BakeryHouse">
                </div>
                <div class="about-text">
                    <h3>Our Story</h3>
                    <p>BakeryHouse was founded with a simple mission: to bring the finest artisan baked goods to our community. Our passion for baking drives us to create delicious, high-quality products using only the best ingredients.</p>
                    <p>Every item in our bakery is crafted with care, from our signature sourdough bread to our decadent cakes and pastries. We believe that great food brings people together and creates lasting memories.</p>
                    <p>Visit us today and taste the difference that passion and quality ingredients make!</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section testimonials" id="testimonials">
        <div class="container">
            <h2 class="section-title">What Our Customers Say</h2>
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <p class="testimonial-text">"The best bakery in town! Their croissants are absolutely divine and taste just like the ones I had in Paris."</p>
                    <p class="testimonial-author">- Kee Cheng Wei.</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"I ordered a custom birthday cake for my daughter, and it was both beautiful and delicious. Everyone raved about it!"</p>
                    <p class="testimonial-author">- Mandy Thoo Wei Xuen.</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"Their sourdough bread is my weekly staple. Crusty on the outside, soft on the inside - perfection!"</p>
                    <p class="testimonial-author">- Chuah Woon Long.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section cta-section" id="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Taste the Difference?</h2>
                <p>Order online for pickup or delivery and experience the quality of BakeryHouse for yourself.</p>
                <button class="cta-btn" onclick="window.location.href='menu.html'">Order Now</button>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="Bakery House Logo.png" alt="BakeryHouse">
                </div>
                <p>Sweet & Delicious</p>
                <div class="footer-links">
                    <a href="#">Home</a>
                    <a href="menu.html">Menu</a>
                    <a href="about_us.html">About</a>
                    <a href="contact.html">Contact</a>
                    <a href="privacypolicy.html">Privacy Policy</a>
                    <a href="termservice.html">Terms of Service</a>
                </div>
                <p>&copy; 2024 BakeryHouse. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // 原来的JavaScript保持不变，只添加下拉菜单功能
        
        // ========== 新增的下拉菜单功能 ==========
        
        // 切换下拉菜单显示/隐藏
        function toggleDropdown() {
            // 获取下拉菜单元素
            const dropdown = document.getElementById('dropdownMenu');
            
            // 切换'show'类：如果有就移除，没有就添加
            dropdown.classList.toggle('show');
        }
        
        // 点击页面其他地方时关闭下拉菜单
        window.addEventListener('click', function(event) {
            // 获取下拉菜单和用户图标元素
            const dropdown = document.getElementById('dropdownMenu');
            const userIcon = document.querySelector('.user-icon');
            
            // 检查点击的位置
            // 如果点击的不是用户图标，也不是下拉菜单内部
            if (!userIcon.contains(event.target) && !dropdown.contains(event.target)) {
                // 移除'show'类，关闭下拉菜单
                dropdown.classList.remove('show');
            }
        });
        
        // ========== 原来的JavaScript保持不变 ==========
        const featuredProducts = [
            {
                id: 1,
                name: "Artisan Sourdough Bread",
                price: 12.50,
                image: "https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60",
                category: "bread"
            },
            {
                id: 2,
                name: "New York Cheesecake",
                price: 45.00,
                image: "https://images.unsplash.com/photo-1524351199678-941a58a3df50?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60",
                category: "cake"
            },
            {
                id: 3,
                name: "Butter Croissant",
                price: 5.50,
                image: "https://images.unsplash.com/photo-1559620192-032c4bc4674e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60",
                category: "pastry"
            },
            {
                id: 4,
                name: "Chocolate Chip Cookies",
                price: 8.00,
                image: "https://images.unsplash.com/photo-1499636136210-6f4ee915583e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60",
                category: "cookie"
            }
        ];

        let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];

        const featuredProductsContainer = document.getElementById('featuredProducts');
        const cartIcon = document.getElementById('cartIcon');
        const cartCount = document.querySelector('.cart-count');
        const heroTitle = document.getElementById('heroTitle');
        const heroSubtitle = document.getElementById('heroSubtitle');
        const heroBtn = document.getElementById('heroBtn');

        function loadFeaturedProducts() {
            featuredProductsContainer.innerHTML = '';
            featuredProducts.forEach(product => {
                const productHTML = `
                    <div class="product-card" data-id="${product.id}">
                        <img src="${product.image}" alt="${product.name}" class="product-image">
                        <div class="product-info">
                            <h3 class="product-name">${product.name}</h3>
                            <p class="product-price">RM ${product.price.toFixed(2)}</p>
                        </div>
                    </div>
                `;
                featuredProductsContainer.innerHTML += productHTML;
            });
            
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function() {
                    const productId = parseInt(this.getAttribute('data-id'));
                    viewProductDetails(productId);
                });
            });
        }

        function viewProductDetails(productId) {
            sessionStorage.setItem('currentProduct', productId);
            window.location.href = 'product-details.html';
        }

        function updateCartCount() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;
        }

        function checkScroll() {
            const sections = document.querySelectorAll('.section');
            const windowHeight = window.innerHeight;
            const triggerPoint = windowHeight * 0.8;
            
            sections.forEach(section => {
                const sectionTop = section.getBoundingClientRect().top;
                
                if (sectionTop < triggerPoint) {
                    section.classList.add('active');
                    
                    const cards = section.querySelectorAll('.category-card, .product-card, .testimonial-card');
                    cards.forEach(card => {
                        card.classList.add('fade-in');
                    });
                    
                    if (section.id === 'about') {
                        const aboutImage = section.querySelector('.about-image');
                        const aboutText = section.querySelector('.about-text');
                        
                        if (aboutImage) aboutImage.classList.add('slide-in-left');
                        if (aboutText) aboutText.classList.add('slide-in-right');
                    }
                    
                    if (section.id === 'cta') {
                        const ctaContent = section.querySelector('.cta-content');
                        if (ctaContent) ctaContent.classList.add('fade-in');
                    }
                }
            });
        }

        function initPage() {
            setTimeout(() => {
                heroTitle.classList.add('fade-in');
                heroSubtitle.classList.add('fade-in');
                heroBtn.classList.add('fade-in');
            }, 300);
            
            loadFeaturedProducts();
            updateCartCount();
            
            checkScroll();
            
            window.addEventListener('scroll', checkScroll);
        }

        function setupEventListeners() {
            cartIcon.addEventListener('click', () => {
                window.location.href = 'cart.html';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            initPage();
            setupEventListeners();
        });
    </script>
</body>
</html>