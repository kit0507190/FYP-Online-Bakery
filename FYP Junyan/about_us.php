<?php
/**
 * about_us.php - 关于我们
 * 核心功能：展示品牌历史、团队成员及核心价值。
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeryHouse - About Us</title>
    <link rel="stylesheet" href="about_us.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <section class="hero about-hero">
        <div class="hero-content">
            <h1 id="heroTitle">Our Story</h1>
            <p id="heroSubtitle">Discover the passion behind BakeryHouse</p>
            <button class="hero-btn" id="heroBtn" onclick="window.location.href='menu.php'">Explore Our Menu</button>
        </div>
    </section>

    <section class="section" id="about">
        <div class="container">
            <h2 class="section-title">Our Journey</h2>
            <div class="about-content">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1558961363-fa8fdf82db35?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="About BakeryHouse">
                </div>
                <div class="about-text">
                    <h3>From Humble Beginnings</h3>
                    <p>BakeryHouse was founded in 2010 by Lim See Yuan Shane, Wong Chun Kit and Lim Jun Yan with a simple mission: to bring the finest artisan baked goods to our community. What started as a small neighborhood bakery has grown into a beloved local institution.</p>
                    <p>Our passion for baking drives us to create delicious, high-quality products using only the best ingredients. We believe that great food brings people together and creates lasting memories.</p>
                    <p>Every item in our bakery is crafted with care, from our signature sourdough bread to our decadent cakes and pastries. We source our ingredients locally whenever possible and never use artificial preservatives.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="values">
        <div class="container">
            <h2 class="section-title">Our Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">🥐</div>
                    <h3 class="value-title">Quality Ingredients</h3>
                    <p>We source only the finest ingredients, from organic flours to locally-sourced dairy and seasonal fruits. Quality is never compromised in our recipes.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">👨‍🍳</div>
                    <h3 class="value-title">Artisan Craftsmanship</h3>
                    <p>Our bakers combine traditional techniques with innovative approaches to create unique, handcrafted baked goods with exceptional flavor and texture.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">❤️</div>
                    <h3 class="value-title">Community Focus</h3>
                    <p>We're proud to be part of our local community, supporting local farmers and participating in neighborhood events and initiatives.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="team">
        <div class="container">
            <h2 class="section-title">Meet Our Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <img src="shane.jpg" alt="Lim See Yuan Shane" class="member-photo">
                    <div class="member-info">
                        <h3 class="member-name">Lim See Yuan Shane</h3>
                        <p class="member-role">Founder & Head Baker</p>
                        <p>Shane trained at Le Cordon Bleu in Paris and brings 15 years of baking experience to BakeryHouse.</p>
                    </div>
                </div>
                <div class="team-member">
                    <img src="ck.jpg" alt="Wong Chun Kit" class="member-photo">
                    <div class="member-info">
                        <h3 class="member-name">Wong Chun Kit</h3>
                        <p class="member-role">Co-Founder & Pastry Chef</p>
                        <p>Wong Chun Kit in French pastries and desserts, creating our signature cakes and delicate pastries.</p>
                    </div>
                </div>
                <div class="team-member">
                    <img src="junyan.jpg" alt="Lim Jun Yan" class="member-photo">
                    <div class="member-info">
                        <h3 class="member-name">Lim Jun Yan</h3>
                        <p class="member-role">Bread Specialist</p>
                        <p>Lim Jun Yan is our sourdough expert, maintaining our 10-year-old starter and creating our artisan breads.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section testimonials" id="testimonials">
        <div class="container">
            <h2 class="section-title">What Our Customers Say</h2>
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <p class="testimonial-text">"The best bakery in town! Their croissants are absolutely divine and taste just like the ones I had in Paris. The team is always so friendly and helpful."</p>
                    <p class="testimonial-author">- Kee Cheng Wei</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"I ordered a custom birthday cake for my daughter, and it was both beautiful and delicious. Everyone raved about it! The attention to detail is incredible."</p>
                    <p class="testimonial-author">- Mandy Thoo Wei Xuen</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"Their sourdough bread is my weekly staple. Crusty on the outside, soft on the inside - perfection! I love supporting a local business with such high standards."</p>
                    <p class="testimonial-author">- Chuah Woon Long</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section cta-section" id="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Taste the Difference?</h2>
                <p>Visit us today and experience the quality of BakeryHouse for yourself.</p>
                <button class="cta-btn" onclick="window.location.href='menu.php'">Order Now</button>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="about_us.js"></script>
</body>
</html>