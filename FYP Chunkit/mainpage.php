<?php
// 1. Start session
session_start();

// 2. Connect to database (this creates $pdo)
require_once 'db_connect.php';

// 3. Check login status
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userName   = $isLoggedIn ? $_SESSION['user_name'] : '';
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
                    <img src="product_images/Baby_Pandaa.jpg" alt="Cakes" class="category-image">
                    <div class="category-name">Cakes</div>
                </div>
                <div class="category-card" onclick="window.location.href='menu.php?category=bread'">
                    <img src="product_images/Alsatian Kugelhopf Sweet Bread.webp" alt="Bread" class="category-image">
                    <div class="category-name">Bread</div>
                </div>
                <div class="category-card" onclick="window.location.href='menu.php?category=pastry'">
                    <img src="product_images/Mascarpone Puff Pastry.jpg" alt="Pastries" class="category-image">
                    <div class="category-name">Pastries</div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="featured">
        <div class="container">
            <h2 class="section-title">Recommended Products</h2>
            <div class="products-grid">

                <?php
// ────────────────────────────────────────────────
//   Load Recommended Products
// ────────────────────────────────────────────────
try {
    $stmt = $pdo->prepare("
        SELECT 
            p.id, p.name, p.price, p.image,
            LOWER(c.name) AS category,          -- Lowercase for URL/JS consistency
            s.name AS subcategory
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories s ON p.subcategory_id = s.id
        WHERE p.deleted_at IS NULL
        ORDER BY p.sold_count DESC, p.id DESC
        LIMIT 4
    ");
    $stmt->execute();
    $featured = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($featured)) {
        echo '<p style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; color: #666;">
                No best-selling products available yet.
              </p>';
    } else {
        foreach ($featured as $product) {
            // All images are stored directly in product_images/
            $img_path = $product['image'] ?? '';
            if ($img_path && !str_starts_with($img_path, 'http') && !str_starts_with($img_path, '/')) {
                $img_path = 'product_images/' . $img_path;
            } else if (empty($img_path)) {
                $img_path = 'product_images/placeholder.jpg'; // fallback
            }

            $name_esc = htmlspecialchars($product['name'] ?? 'Unnamed Product');
            $price    = number_format((float)($product['price'] ?? 0), 2);
            $id_esc   = htmlspecialchars($product['id'] ?? '0');
            ?>
            <div class="product-card" 
     onclick="window.location.href='menu.php?open_id=<?= htmlspecialchars($product['id']) ?>&category=<?= htmlspecialchars($product['category'] ?? 'all') ?>'">
     
                <img src="<?= htmlspecialchars($img_path) ?>" 
                     alt="<?= $name_esc ?>" 
                     class="product-image"
                     loading="lazy"
                     onerror="this.src='product_images/placeholder.jpg'; this.alt='Image not available';">
                <div class="product-info">
                    <h3 class="product-name"><?= $name_esc ?></h3>
                    <p class="product-price">RM <?= $price ?></p>
                </div>
            </div>
            <?php
        }
    }
} catch (Exception $e) {
    // In production: log the error instead of displaying it
    // error_log("Featured products error: " . $e->getMessage());
    echo '<p style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; color: #c0392b;">
            Sorry, we couldn\'t load the best sellers right now.
          </p>';
}
?>

            </div>
        </div>
    </section>

    <section class="section" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=800&h=1000&auto=format&fit=crop" alt="BakeryHouse Story">
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