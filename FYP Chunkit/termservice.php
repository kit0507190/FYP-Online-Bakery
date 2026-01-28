<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Bakery House</title>
    <link rel="stylesheet" href="termservice.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="footer.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <section class="hero terms-hero">
        <div class="hero-content">
            <h1 id="heroTitle">Terms of Service</h1>
            <p id="heroSubtitle">Please review our rules and guidelines for a sweet experience.</p>
        </div>
    </section>

    <main class="terms-container">
        <div class="container">
            <div class="terms-card">
                <div class="terms-header">
                    <i class="fas fa-file-invoice"></i>
                    <h2>Terms & Conditions</h2>
                    <p>By using BakeryHouse, you agree to follow the recipes for success outlined below. These terms ensure a fair and safe environment for all our customers.</p>
                </div>

                <div class="terms-content">
                    <article class="terms-section">
                        <h3><i class="fas fa-check-circle"></i> 1. Acceptance of Terms</h3>
                        <p>By accessing BakeryHouse's website and purchasing our products, you accept and agree to be bound by these Terms of Service. If you do not agree, please refrain from using our services.</p>
                    </article>

                    <article class="terms-section">
                        <h3><i class="fas fa-user-plus"></i> 2. Account Registration</h3>
                        <p>To enjoy our full service, you may need to create an account. You are responsible for:</p>
                        <ul class="styled-list">
                            <li>Providing accurate and current information.</li>
                            <li>Maintaining the confidentiality of your password.</li>
                            <li>All activities that occur under your account.</li>
                        </ul>
                    </article>

                    <article class="terms-section">
                        <h3><i class="fas fa-shopping-basket"></i> 3. Ordering & Payment</h3>
                        <p>All orders are subject to availability. Prices may change based on seasonal ingredients, but the price at the time of your order is final.</p>
                        <div class="highlight-box">
                            <p><strong>Payment Notice:</strong> Full payment is required at the time of checkout via our secure payment partners.</p>
                        </div>
                    </article>

                    <article class="terms-section">
                        <h3><i class="fas fa-truck"></i> 4. Delivery Policy</h3>
                        <p>We aim to deliver your fresh bakes as quickly as possible. Please note:</p>
                        <ul class="styled-list">
                            <li>Delivery times are estimates and may vary due to traffic or weather.</li>
                            <li>Ensure someone is available to receive the order to maintain food freshness.</li>
                            <li>Accuracy of the delivery address is the customer's responsibility.</li>
                        </ul>
                    </article>

                    <article class="terms-section">
                        <h3><i class="fas fa-undo-alt"></i> 5. Returns & Refunds</h3>
                        <p>Due to the perishable nature of food, we do not accept returns. However, if there is a quality issue, please contact us within <strong>2 hours</strong> of receiving your order with photographic evidence.</p>
                    </article>

                    <article class="terms-section">
                        <h3><i class="fas fa-balance-scale"></i> 6. Governing Law</h3>
                        <p>These terms are governed by the laws of Malaysia. Any disputes will be resolved within the jurisdiction of the courts in Melaka.</p>
                    </article>

                    <div class="terms-footer">
                        <p><strong>Last Updated:</strong> January 1, 2024</p>
                        <p>Need clarification? <a href="contact_us.php">Contact our team</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="termservice.js"></script>
</body>
</html>