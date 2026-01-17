
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BakeryHouse - Menu</title>
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>

<!-- 只多这一行 -->
<?php include 'header.php'; ?>



<!-- ↓↓↓ 从这里开始：下面全部照抄 menu.html，一行不改 ↓↓↓ -->

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
                    <!-- Active Category Display -->
                    <div class="active-category" id="activeCategory">
                        All Cakes
                    </div>
                    
                    <!-- Results Info -->
                    <div class="results-info" id="resultsInfo">
                        Showing all cakes
                    </div>
                    
                    <!-- Loading Spinner -->
                    <div class="loading-spinner" id="loadingSpinner" style="display:none;">
                        <div class="spinner"></div>
                        <p>Loading delicious products...</p>
                    </div>
                    
                    <!-- Products Grid -->
                    <div class="products-grid" id="productsGrid">
                        <!-- Products will be dynamically generated -->
                    </div>
                    
                    <!-- Pagination Controls (Prev / Page / Next) -->
                    <div class="pagination" id="paginationControls" style="margin-top:18px; display:flex; gap:12px; align-items:center; justify-content:center;">
                        <button id="prevPageBtn">Prev</button>
                        <div class="page-indicator" id="pageIndicator">Page 1</div>
                        <button id="nextPageBtn">Next</button>
                    </div>
                    
                    <!-- The old Load More kept but hidden (not used) -->
                    <div class="load-more-container" style="display:none;">
                        <button class="load-more-btn" id="loadMoreBtn" style="display: none;">Load More Products</button>
                    </div>
                    
                    <!-- Recently Viewed -->
                    <div class="recently-viewed" id="recentlyViewed" style="display: none; margin-top:30px;">
                        <h2 class="section-title">Recently Viewed</h2>
                        <div class="recent-products" id="recentProducts">
                            <!-- Recently viewed products will be dynamically loaded -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick View Modal -->
    <div class="modal" id="quickViewModal">
        <div class="modal-content" id="quickViewContent">
            <!-- Quick view content will be dynamically loaded -->
        </div>
    </div>

    

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" style="display:none;">↑</button>

    <!-- Toast Notification -->
    <div class="toast" id="toast" style="display:none;"></div>

    <?php include 'footer.php'; ?>
	
	<!-- ⚠️ JS 一定在这里，和 menu.html 一模一样 -->
	<script src="menu.js"></script>
	
</body>
</html>