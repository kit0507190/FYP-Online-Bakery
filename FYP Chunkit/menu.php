<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BakeryHouse - Menu</title>
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>

<?php include 'header.php'; ?>

<!-- Menu Page -->
<section class="menu-page">
    <div class="container">
        <div class="menu-header-box">
            <h1 class="menu-title">Our Delicious Bakery Products</h1>
            <hr style="width: 60px; border: none; border-top: 3px solid #d4a76a; margin: 0 auto 20px; border-radius: 10px;">
            
            <hr class="menu-divider">
            <div class="search-filter-bar">
                <div class="search-box">
                    <input type="text" class="search-input" id="searchInput" placeholder="Search products...">
                    <button class="search-btn" id="searchBtn">🔍</button>
                </div>
                <div class="sort-filter">
                    <select class="sort-select" id="sortSelect">
                        <option value="name">Sort by Name</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="rating">Top Rated</option>
                    </select>
                </div>
            </div>
        </div>
            
        <div class="menu-layout">
            <!-- Categories Sidebar – Fully dynamic from database -->
            <div class="categories-sidebar">
                <h3 class="category-header">Categories</h3>

                <!-- Always-present "All Products" -->
                <div class="category-item">
                    <div class="category-main active" data-category="all">
                        <span>All Products</span>
                        <span class="category-arrow active">▼</span>
                    </div>
                    <div class="subcategories active">
                        <a class="subcategory-item active" data-subcategory="all">All Products</a>
                    </div>
                </div>

                <?php
                // Include your database connection
                // Make sure this file exists and returns a PDO instance named $pdo
                include 'db_connect.php';  

                // Plural display names – minimal and extensible
                $pluralMap = [
                    'cake'    => 'Cakes',
                    'pastry'  => 'Pastries',
                    'cookie'  => 'Cookies',
                    'bread'   => 'Bread'   // already plural
                ];

                // Fetch all categories
                $stmt = $pdo->query("SELECT id, name FROM categories ORDER BY id");
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($categories as $cat) {
                    $catName   = $cat['name'];
                    $catSlug   = strtolower($catName);
                    $display   = $pluralMap[$catSlug] ?? $catName . (substr($catName, -1) === 's' ? '' : 's');

                    echo '<div class="category-item">';
                    echo '  <div class="category-main" data-category="' . htmlspecialchars($catSlug) . '">';
                    echo '      <span>' . htmlspecialchars($display) . '</span>';
                    echo '      <span class="category-arrow">▼</span>';
                    echo '  </div>';
                    echo '  <div class="subcategories">';

                    // All items in this category
                    echo '      <a class="subcategory-item active" data-subcategory="all">All ' . htmlspecialchars($display) . '</a>';

                    // Fetch subcategories
                    $subStmt = $pdo->prepare("SELECT name FROM subcategories WHERE category_id = ? ORDER BY id");
                    $subStmt->execute([$cat['id']]);
                    $subs = $subStmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($subs as $sub) {
                        $subName = $sub['name'];

                        // Generate slug consistent with your JS filter logic
                        $subSlug = strtolower($subName);
                        $subSlug = preg_replace('/\s*&?\s*/', ' ', $subSlug);    // & → space
                        $subSlug = preg_replace('/\s+/', '-', $subSlug);         // spaces → dash
                        $subSlug = preg_replace('/[^a-z0-9-]/', '', $subSlug);   // remove special chars
                        $subSlug = trim($subSlug, '-');

                        // Small correction map to match existing JS behavior
                        // Remove entries as you standardize your product.subcategory values
                        $fix = [
                            'cute-mini-cake'          => 'mini',
                            'the-animal-series'       => 'animal',
                            'full-moon-gift-packages' => 'full-moon',
                            'wedding-gift-packages'   => 'wedding',
                            'fresh-cream-cake'        => 'fresh-cream',
                            'fondant-cake-design'     => 'fondant',
                            'puff-pastry'             => 'puff',
                            'whole-grain-bread'       => 'wholegrain',
                            'danish-pastries'         => 'danish',
                            'artisan-bread'           => 'artisan',
                        ];

                        if (isset($fix[$subSlug])) {
                            $subSlug = $fix[$subSlug];
                        }

                        echo '      <a class="subcategory-item" data-subcategory="' 
                             . htmlspecialchars($subSlug) . '">' 
                             . htmlspecialchars($subName) 
                             . '</a>';
                    }

                    echo '  </div>';
                    echo '</div>';
                }

                // Optional fallback if no categories exist
                if (empty($categories)) {
                    echo '<p style="padding: 15px; color: #777;">No categories available yet.</p>';
                }
                ?>
            </div>
            
            <!-- Products Section -->
            <div class="products-section">
                <!-- Active Category Display -->
                <div class="active-category" id="activeCategory">
                    All Products
                </div>
                
                <!-- Results Info -->
                <div class="results-info" id="resultsInfo">
                    Showing all products
                </div>
                
                <!-- Loading Spinner -->
                <div class="loading-spinner" id="loadingSpinner" style="display:none;">
                    <div class="spinner"></div>
                    <p>Loading delicious products...</p>
                </div>
                
                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
                    <!-- Products will be dynamically generated by JS -->
                </div>
                
                <!-- Pagination -->
                <div class="pagination" id="paginationControls" style="margin-top:18px; display:flex; gap:12px; align-items:center; justify-content:center;">
                    <button id="prevPageBtn">Prev</button>
                    <div class="page-indicator" id="pageIndicator">Page 1</div>
                    <button id="nextPageBtn">Next</button>
                </div>
                
                <!-- Recently Viewed -->
                <div class="recently-viewed" id="recentlyViewed" style="display: none; margin-top:30px;">
                    <h2 class="section-title">Recently Viewed</h2>
                    <div class="recent-products" id="recentProducts"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick View Modal -->
<div class="modal" id="quickViewModal">
    <div class="modal-content" id="quickViewContent"></div>
</div>


<!-- Toast -->
<div class="toast" id="toast" style="display:none;"></div>

<!-- Back to Top -->
<button class="back-to-top" id="backToTop" style="display:none;">↑</button>

<?php include 'footer.php'; ?>

<script src="menu.js"></script>

<script>
    async function forceSyncCart() {
        if (!window.isLoggedIn) return;
        
        const currentCart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
        
        try {
            await fetch('sync_cart.php?action=update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart: currentCart })
            });
            console.log("Cart synced to database");
        } catch (e) {
            console.error("Cart sync failed:", e);
        }
    }

    document.addEventListener('click', (e) => {
        if (e.target.innerText && e.target.innerText.includes('Add to Cart')) {
            setTimeout(forceSyncCart, 800);
        }
    });
</script>

</body>
</html>