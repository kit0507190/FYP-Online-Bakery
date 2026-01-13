<?php
/**
 * index.php - 访客首页（未登录状态）
 * 使用 header.php 组件，并引用外部 CSS 和 JS
 */
session_start();
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
            <div class="products-grid" id="featuredProducts"></div>
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
                    <p>Every item in our bakery is crafted with care. We believe that great food brings people together and creates lasting memories.</p>
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
                <button class="cta-btn" onclick="window.location.href='menu.html'">Order Now</button>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="mainpage.js"></script>
</body>
</html>