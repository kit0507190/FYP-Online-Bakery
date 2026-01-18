<?php
// 1. 初始化 Session：必须在页面任何内容输出前调用，用于访问 $_SESSION 变量
session_start();

// 2. 引入外部配置文件：获取数据库连接信息
require_once 'config.php';

// 3. 检查用户登录状态：
//    isset() 检查变量是否定义
//    $_SESSION['logged_in'] 是在登录成功页面设置的标志位
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// 4. 获取用户名：如果已登录，从 Session 获取名字，否则为空字符串
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse - Home</title>
    <link rel="stylesheet" href="mainpage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="footer.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <section class="hero">
        <div class="hero-content">
            <h1 id="heroTitle">Happiness Is a Piece of Cake</h1>
            <p id="heroSubtitle">Our Favorite Bakery House in Town</p>
            <button class="hero-btn" id="heroBtn" onclick="window.location.href='menu.php'">Explore Our Menu</button>
        </div>
    </section>

    <section class="section" id="categories">
        <div class="container">
            <h2 class="section-title">Shop by Categories</h2>
            <div class="categories-grid">
                <div class="category-card" onclick="window.location.href='menu.php?category=cake'">
                    <img src="cake/Baby_Pandaa.jpg" alt="Cakes" class="category-image">
                    <div class="category-name">Cakes</div>
                </div>
                <div class="category-card" onclick="window.location.href='menu.php?category=bread'">
                    <img src="bread/Sweet Bread/Alsatian Kugelhopf Sweet Bread.webp" alt="Bread" class="category-image">
                    <div class="category-name">Bread</div>
                </div>
                <div class="category-card" onclick="window.location.href='menu.php?category=pastry'">
                    <img src="pastries/Puff Pastry/Mascarpone Puff Pastry.jpg" alt="Pastries" class="category-image">
                    <div class="category-name">Pastries</div>
                </div>
                <div class="category-card" onclick="window.location.href='menu.php?category=cookie'">
                    <img src="cookies/Butter Cookies/Chocolate Butter Swirl Cookies.jpg" alt="Cookies" class="category-image">
                    <div class="category-name">Cookies</div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="featured">
        <div class="container">
            <h2 class="section-title">Best Selling Products</h2>
            <div class="products-grid" id="featuredProducts"></div>
        </div>
    </section>

    <section class="section" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500" alt="About BakeryHouse">
                </div>
                <div class="about-text">
                    <h3>Our Story</h3>
                    <p>BakeryHouse was founded with a simple mission: to bring the finest artisan baked goods to our community. Our passion for baking drives us to create delicious, high-quality products using only the best ingredients.</p>
                    <p>Every item in our bakery is crafted with care. We believe that great food brings people together and creates lasting memories.</p>
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
                    <p class="testimonial-text">"The best bakery in town! Their croissants are absolutely divine."</p>
                    <p class="testimonial-author">- Kee Cheng Wei.</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"I ordered a custom birthday cake, and it was both beautiful and delicious."</p>
                    <p class="testimonial-author">- Mandy Thoo Wei Xuen.</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"Their sourdough bread is my weekly staple. Perfection!"</p>
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
                <button class="cta-btn" onclick="window.location.href='menu.php'">Order Now</button>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="mainpage.js"></script>
</body>
</html>