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

<?php
// Database connection
$servername = "127.0.0.1";
$username   = "root";           // ← change if different
$password   = "";               // ← change if you have password
$dbname     = "bakeryhouse";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "<script>console.error('Database connection failed: " . addslashes($conn->connect_error) . "');</script>";
    $products = [];
} else {
    $sql = "SELECT 
                p.id, p.name, p.price, p.category_id, 
                c.name AS category_name, p.subcategory, 
                p.stock, p.description, p.image, p.created_at
            FROM products p
            JOIN categories c ON p.category_id = c.id";

    $result = $conn->query($sql);

    $products = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Add lowercase category string for JS compatibility
            $row['category'] = strtolower($row['category_name']);
            unset($row['category_name']);

            // Make sure subcategory is always an array
            $row['subcategory'] = $row['subcategory'] 
                ? json_decode($row['subcategory'], true) 
                : [];

            // Convert price to float/number
            $row['price'] = (float) $row['price'];

            // Make IDs integers (good practice)
            $row['id'] = (int) $row['id'];
            $row['category_id'] = (int) $row['category_id'];

            $products[] = $row;
        }
    } else {
        echo "<script>console.error('Query failed: " . addslashes($conn->error) . "');</script>";
        $products = [];
    }

    $conn->close();
}
?>

<!-- Pass products to JavaScript -->
<script>
    const products = <?php echo json_encode($products); ?>;

    // Just in case - extra safety
    products.forEach(p => {
        if (typeof p.price !== 'number') {
            p.price = parseFloat(p.price) || 0;
        }
        if (!Array.isArray(p.subcategory)) {
            p.subcategory = [];
        }
    });

    console.log("Products loaded from database:", products.length, "items");
    if (products.length > 0) {
        console.log("First product:", products[0]);
    }
</script>


<!-- Breadcrumb -->
<div class="container">
    <div class="breadcrumb">
        <a href="index.php">Home</a> > 
        <span>Menu</span>
    </div>
</div>

<!-- Menu Page -->
<section class="menu-page">
    <div class="container">
        <h1 style="text-align: center; margin: 20px 0; font-size: 32px;">Our Delicious Bakery Products</h1>
        
        <!-- Search and Filter Bar -->
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
                    <option value="rating">Highest Rated</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>
        </div>
        
        <div class="menu-layout">
            <!-- Categories Sidebar -->
            <div class="categories-sidebar">
                <h3 class="category-header">Categories</h3>
                
                <!-- Cake Category -->
                <div class="category-item">
                    <div class="category-main active" data-category="cake">
                        <span>Cakes</span>
                        <span class="category-arrow active">▼</span>
                    </div>
                    <div class="subcategories active">
                        <a class="subcategory-item active" data-subcategory="all">All Cakes</a>
                        <a class="subcategory-item" data-subcategory="5 inch">5 inch Cake</a>
                        <a class="subcategory-item" data-subcategory="cheese">Cheese Flavour</a>
                        <a class="subcategory-item" data-subcategory="chocolate">Chocolate & Coffee</a>
                        <a class="subcategory-item" data-subcategory="strawberry">Strawberry Flavour</a>
                        <a class="subcategory-item" data-subcategory="vanilla">Vanilla Flavour</a>
                        <a class="subcategory-item" data-subcategory="durian">Durian Series</a>
                        <a class="subcategory-item" data-subcategory="animal">The Animal Series</a>
                        <a class="subcategory-item" data-subcategory="fondant">Fondant Cake Design</a>
                        <a class="subcategory-item" data-subcategory="fresh-cream">Fresh Cream Cake</a>
                        <a class="subcategory-item" data-subcategory="festival">Festival</a>
                        <a class="subcategory-item" data-subcategory="little">Little Series</a>
                        <a class="subcategory-item" data-subcategory="mini">Cute Mini Cake</a>
                    </div>
                </div>

                <!-- Bread Category -->
                <div class="category-item">
                    <div class="category-main" data-category="bread">
                        <span>Bread</span>
                        <span class="category-arrow">▼</span>
                    </div>
                    <div class="subcategories">
                        <a class="subcategory-item active" data-subcategory="all">All Bread</a>
                        <a class="subcategory-item" data-subcategory="sourdough">Sourdough Bread</a>
                        <a class="subcategory-item" data-subcategory="wholegrain">Whole Grain Bread</a>
                        <a class="subcategory-item" data-subcategory="artisan">Artisan Bread</a>
                        <a class="subcategory-item" data-subcategory="sweet">Sweet Bread</a>
                    </div>
                </div>

                <!-- Pastry Category -->
                <div class="category-item">
                    <div class="category-main" data-category="pastry">
                        <span>Pastries</span>
                        <span class="category-arrow">▼</span>
                    </div>
                    <div class="subcategories">
                        <a class="subcategory-item active" data-subcategory="all">All Pastries</a>
                        <a class="subcategory-item" data-subcategory="croissant">Croissants</a>
                        <a class="subcategory-item" data-subcategory="danish">Danish Pastries</a>
                        <a class="subcategory-item" data-subcategory="tart">Tarts</a>
                        <a class="subcategory-item" data-subcategory="puff">Puff Pastry</a>
                    </div>
                </div>
            </div>
            
            <!-- Products Section -->
            <div class="products-section">
                <div class="active-category" id="activeCategory">All Cakes</div>
                <div class="results-info" id="resultsInfo">Showing all cakes</div>
                
                <div class="loading-spinner" id="loadingSpinner" style="display:none;">
                    <div class="spinner"></div>
                    <p>Loading delicious products...</p>
                </div>
                
                <div class="products-grid" id="productsGrid">
                    <!-- Products will be dynamically generated -->
                </div>
                
                <div class="pagination" id="paginationControls" style="margin-top:18px; display:flex; gap:12px; align-items:center; justify-content:center;">
                    <button id="prevPageBtn">Prev</button>
                    <div class="page-indicator" id="pageIndicator">Page 1</div>
                    <button id="nextPageBtn">Next</button>
                </div>
                
                <div class="load-more-container" style="display:none;">
                    <button class="load-more-btn" id="loadMoreBtn" style="display: none;">Load More Products</button>
                </div>
                
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

<button class="back-to-top" id="backToTop" style="display:none;">↑</button>
<div class="toast" id="toast" style="display:none;"></div>

<?php include 'footer.php'; ?>

<script src="menu.js?v=<?php echo time(); ?>"></script>

</body>
</html>