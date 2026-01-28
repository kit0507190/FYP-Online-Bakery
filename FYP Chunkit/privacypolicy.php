<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Bakery House</title>
    <link rel="stylesheet" href="privacypolicy.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="footer.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <section class="hero privacy-hero">
        <div class="hero-content">
            <h1 id="heroTitle">Privacy Policy</h1>
            <p id="heroSubtitle">How we bake privacy and security into our services.</p>
        </div>
    </section>

    <main class="privacy-container">
        <div class="container">
            <div class="privacy-card">
                <div class="privacy-header">
                    <i class="fas fa-user-shield"></i>
                    <h2>Our Commitment to Your Privacy</h2>
                    <p>At BakeryHouse, we treat your personal data with the same care we put into our artisan bread. We believe in transparency and want you to understand exactly how your information is handled.</p>
                </div>

                <div class="privacy-content">
                    <article class="privacy-section">
                        <h3><i class="fas fa-database"></i> 1. Information Collection</h3>
                        <p>We collect information that helps us deliver your favorite treats to your doorstep. This includes:</p>
                        <ul class="styled-list">
                            <li><strong>Personal Identity:</strong> Your name and date of birth (to send you birthday cake vouchers!).</li>
                            <li><strong>Contact Details:</strong> Your email address, phone number, and physical delivery addresses.</li>
                            <li><strong>Order History:</strong> Details about the products you have purchased and your favorite flavors.</li>
                            <li><strong>Technical Data:</strong> Your IP address, browser type, and how you interact with our website to improve our user experience.</li>
                        </ul>
                    </article>

                    <article class="privacy-section">
                        <h3><i class="fas fa-cookie-bite"></i> 2. Cookies & Tracking</h3>
                        <p>Our website uses "cookies" to enhance your shopping experience. Cookies allow our site to remember your cart items even if you close the browser.</p>
                        <div class="highlight-box">
                            <p><strong>Note:</strong> You can choose to disable cookies in your browser, but please note that some parts of our bakery shop may not function correctly as a result.</p>
                        </div>
                    </article>

                    <article class="privacy-section">
                        <h3><i class="fas fa-heart"></i> 3. How We Use Your Data</h3>
                        <p>We don't just collect data; we use it to make your experience better:</p>
                        <ul class="styled-list">
                            <li>To process and deliver your orders efficiently.</li>
                            <li>To manage your account and provide customer support.</li>
                            <li>To notify you about special seasonal promotions or new menu items (only if you opt-in).</li>
                            <li>To protect our website from fraudulent transactions.</li>
                        </ul>
                    </article>

                    <article class="privacy-section">
                        <h3><i class="fas fa-share-alt"></i> 4. Information Sharing</h3>
                        <p>We respect your inbox and your identity. <strong>We will never sell, rent, or trade your personal information to third parties.</strong> We only share data with trusted partners essential for our business:</p>
                        <ul class="styled-list">
                            <li><strong>Delivery Partners:</strong> So they know where to bring your bread.</li>
                            <li><strong>Payment Processors:</strong> Secure gateways that handle payment data (we do not store your full payment details).</li>
                            <li><strong>IT Services:</strong> Providers who help maintain our secure website infrastructure.</li>
                        </ul>
                    </article>

                    <article class="privacy-section">
                        <h3><i class="fas fa-lock"></i> 5. Data Security</h3>
                        <p>Security is the "yeast" that makes our trust grow. We implement industry-standard encryption (SSL) and secure server protocols to ensure your data is safe from unauthorized access.</p>
                    </article>

                    <article class="privacy-section">
                        <h3><i class="fas fa-file-contract"></i> 6. Policy Updates</h3>
                        <p>BakeryHouse may update this policy occasionally to reflect changes in legal requirements or our service. We encourage you to review this page periodically.</p>
                    </div>

                    <div class="privacy-footer">
                        <p><strong>Effective Date:</strong> January 1, 2024</p>
                        <p>If you have any questions, feel free to <a href="contact_us.php">Contact Us</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

   <?php include 'footer.php'; ?>

    <script src="privacypolicy.js"></script>
</body>
</html>