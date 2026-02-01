document.addEventListener('DOMContentLoaded', function () {

    // --- 1. Variable Definition ---
    let products = []; 
    let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
    let favorites = []; // Initially empty, will sync from database later
    let recentlyViewed = JSON.parse(localStorage.getItem('bakeryRecentlyViewed')) || [];

    // --- 2. Get DOM Elements ---
    const productsGrid = document.getElementById('productsGrid');
    const cartIcon = document.getElementById('cartIcon');
    const cartCount = document.querySelector('.cart-count');
    const activeCategory = document.getElementById('activeCategory');
    const resultsInfo = document.getElementById('resultsInfo');
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const sortSelect = document.getElementById('sortSelect');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const quickViewModal = document.getElementById('quickViewModal');
    const quickViewContent = document.getElementById('quickViewContent');
    const backToTop = document.getElementById('backToTop');
    const toast = document.getElementById('toast');
    const recentlyViewedSection = document.getElementById('recentlyViewed');
    const recentProductsContainer = document.getElementById('recentProducts');
    const prevPageBtn = document.getElementById('prevPageBtn');
    const nextPageBtn = document.getElementById('nextPageBtn');
    const pageIndicator = document.getElementById('pageIndicator');

    let currentCategory = 'all'; 
    let currentSubCategory = 'all';
    let currentSearch = '';
    let currentSort = 'name';
    let currentPage = 1;
    const productsPerPage = 9;

    // --- 3. Core Initialization ---
    async function initPage() {
    // 1. Set default filter values
    currentCategory    = 'all';
    currentSubCategory = 'all';
    currentPage        = 1;

    // 2. Read URL parameters to override defaults if present
    const urlParams = new URLSearchParams(window.location.search);
    const catParam  = urlParams.get('category');
    const subParam  = urlParams.get('subcategory');
    const openId    = urlParams.get('open_id');

    console.log('URL Params - category:', catParam, 'subcategory:', subParam, 'open_id:', openId);
    console.log('Initial currentCategory:', currentCategory, 'currentSubCategory:', currentSubCategory);

    if (catParam) {
        currentCategory    = catParam.toLowerCase();
        currentSubCategory = subParam ? subParam.toLowerCase() : 'all';
    }

    if (!catParam) {
    currentCategory    = 'all';
    currentSubCategory = 'all';
    // Also force sidebar to clean state
    document.querySelectorAll('.category-main.active, .subcategory-item.active, .subcategories.active')
        .forEach(el => el.classList.remove('active'));
}

    // 3. Prepare UI
    setupEventListeners();

    if (loadingSpinner) {
        loadingSpinner.style.display = 'block';
    }

    try {
        // 4. Load products
        const response = await fetch('get_products.php');
        if (!response.ok) {
            throw new Error(`Failed to load products: ${response.status}`);
        }
        products = await response.json();

        // 5. Load favorites if user is logged in
favorites = [];  // NEW: Default to empty array
if (window.isLoggedIn === true) {
    try {
        const favResponse = await fetch('get_user_favorites.php');
        if (!favResponse.ok) {
            console.warn('Favorites fetch failed:', favResponse.status);
        } else {
            const favData = await favResponse.json();
            
            // NEW: Extract only the product IDs (since that's all we need for checking favorites.includes(id))
            if (Array.isArray(favData)) {
                favorites = favData
                    .map(item => Number(item.id))   // Get 'id' from each object
                    .filter(id => !isNaN(id) && id > 0);  // Clean up invalid IDs
            }
        }
    } catch (favErr) {
        console.warn('Favorites failed to load:', favErr);
        // NEW: Non-critical — continue with empty favorites if fails
    }
}

        // 6. Now that we have data — sync UI & render
        syncSidebarUI();       // make sidebar visually match current filter
        renderProducts();      // ← this is the key line that was missing

        updateCartCount();
        loadRecentlyViewed();

        // 7. Optional: auto-open product quick view from URL
        if (openId) {
            const productId = parseInt(openId, 10);
            if (!isNaN(productId)) {
                setTimeout(() => {
                    quickViewProduct(productId);
                    
                    // Clean URL param without reload
                    const url = new URL(window.location);
                    url.searchParams.delete('open_id');
                    window.history.replaceState(null, '', url);
                }, 300);
            }
        }
    } catch (error) {
        console.error('Initialization failed:', error);
        if (productsGrid) {
            productsGrid.innerHTML = `
                <div class="no-products">
                    <p>Failed to load products. Please try again later.</p>
                    <small>${error.message}</small>
                </div>`;
        }
    } finally {
        if (loadingSpinner) {
            loadingSpinner.style.display = 'none';
        }
    }
}

    // --- 4. Listener (remains unchanged) ---
    function setupEventListeners() {
        document.querySelectorAll('.category-main').forEach(btn => {
            btn.addEventListener('click', function() {
                const category = this.getAttribute('data-category');
                document.querySelectorAll('.category-main').forEach(other => {
            if (other !== this) {
                other.classList.remove('active');
                const otherArrow = other.querySelector('.category-arrow');
                if (otherArrow) otherArrow.classList.remove('active');
                const otherSub = other.nextElementSibling;
                if (otherSub && otherSub.classList.contains('subcategories')) {
                    otherSub.classList.remove('active');
                }
            }
        }); 

                this.classList.toggle('active');
        
        const arrow = this.querySelector('.category-arrow');
        if (arrow) arrow.classList.toggle('active');

        const sub = this.nextElementSibling;
        if (sub && sub.classList.contains('subcategories')) {
            sub.classList.toggle('active');
        }

        // ── 3. If we actually opened a new category → reset subcategory to 'all' ──
        if (this.classList.contains('active') && category !== currentCategory) {
            currentCategory = category;
            currentSubCategory = 'all';

            // Reset all subcategory active states
            document.querySelectorAll('.subcategory-item').forEach(i => i.classList.remove('active'));

            // Activate the "All XXX" item
            const allSub = sub?.querySelector('.subcategory-item[data-subcategory="all"]');
            if (allSub) allSub.classList.add('active');

            currentPage = 1;
            updateActiveCategory();
            renderProducts();
        }
        // If we just closed it by clicking again → do nothing extra
    });
});

        document.querySelectorAll('.subcategory-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.subcategory-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                currentSubCategory = this.getAttribute('data-subcategory');
                currentPage = 1;
                updateActiveCategory();
                renderProducts();
            });
        });

        searchBtn?.addEventListener('click', () => { currentSearch = searchInput.value.trim(); currentPage = 1; renderProducts(); });
        searchInput?.addEventListener('keypress', (e) => { if (e.key === 'Enter') { currentSearch = searchInput.value.trim(); currentPage = 1; renderProducts(); } });
        sortSelect?.addEventListener('change', () => { currentSort = sortSelect.value; currentPage = 1; renderProducts(); });
        prevPageBtn?.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderProducts(); } });
        nextPageBtn?.addEventListener('click', () => {
            const total = filterProducts().length;
            const maxPage = Math.max(1, Math.ceil(total / productsPerPage));
            if (currentPage < maxPage) { currentPage++; renderProducts(); }
        });
        if (cartIcon) { cartIcon.addEventListener('click', () => { window.location.href = 'cart.php'; }); }
        backToTop?.addEventListener('click', () => { window.scrollTo({ top: 0, behavior: 'smooth' }); });
        window.addEventListener('scroll', () => { if (backToTop) backToTop.style.display = window.pageYOffset > 300 ? 'block' : 'none'; });
    }

    // --- 5. Rendering Logic ---
    function renderProducts() {
        if (!productsGrid) return;
        productsGrid.innerHTML = '';
        let filtered = filterProducts();
        filtered = sortProducts(filtered);

        const total = filtered.length;
        const maxPage = Math.max(1, Math.ceil(total / productsPerPage));
        if (currentPage > maxPage) currentPage = maxPage;
        
        const startIndex = (currentPage - 1) * productsPerPage;
        const toShow = filtered.slice(startIndex, startIndex + productsPerPage);

        if (toShow.length === 0) {
            productsGrid.innerHTML = '<div class="no-products">No products found.</div>';
        } else {
            toShow.forEach(p => productsGrid.innerHTML += createProductCard(p));
        }
        updateResultsInfo(total);

        // 1. Update page number text (changed to uppercase PAGE for better design)
        if (pageIndicator) {
            pageIndicator.textContent = `PAGE ${currentPage} / ${maxPage}`;
        }

        // 2. Optimize Prev button: add icon and automatically handle disabled state
        if (prevPageBtn) {
            prevPageBtn.innerHTML = `<span>←</span> Prev`;
            prevPageBtn.disabled = (currentPage === 1); // If it's the first page, button is greyed out and unclickable
        }

        // 3. Optimize Next button: add icon and automatically handle disabled state
        if (nextPageBtn) {
            nextPageBtn.innerHTML = `Next <span>→</span>`;
            nextPageBtn.disabled = (currentPage === maxPage); // If it's the last page, button is greyed out and unclickable
        }

        setupProductEventListeners();
        setupProductCardClick();
    }

    function setupProductCardClick() {
    document.querySelectorAll('.product-card').forEach(card => {
        // Prevent favorite button clicks from opening quick view
        const favBtn = card.querySelector('.favorite-btn');
        if (favBtn) {
            favBtn.addEventListener('click', e => e.stopPropagation());
        }

        card.addEventListener('click', function(e) {
            // Optional: ignore clicks on favorite button / out-of-stock overlay etc.
            if (e.target.closest('.favorite-btn') || e.target.closest('.out-of-stock-overlay')) {
                return;
            }

            const productId = parseInt(this.getAttribute('data-id'));
            if (!isNaN(productId)) {
                quickViewProduct(productId);
            }
        });
    });
}

    function createProductCard(product) {
    const isFav = favorites.includes(parseInt(product.id));
    const badge = (product.tags && product.tags.includes('popular')) ? 'popular' :
                  (product.tags && product.tags.includes('new')) ? 'new' : '';
    const stars = '★'.repeat(Math.floor(product.rating || 0)) + '☆'.repeat(Math.max(0, 5 - Math.floor(product.rating||0)));

    // ── NEW: stock check ──
    const stock = parseInt(product.stock) || 0;
    const isOutOfStock = stock <= 0;

    return `
        <div class="product-card ${isOutOfStock ? 'out-of-stock' : ''}" data-id="${product.id}">
            ${badge ? `<div class="product-badge ${badge}">${badge === 'popular' ? 'Popular' : 'New'}</div>` : ''}
            <button class="favorite-btn ${isFav ? 'active' : ''}" data-id="${product.id}">${isFav ? '❤️' : '🤍'}</button>
            <div class="product-image-wrapper">
                <img src="${product.image}" alt="${product.name}" class="product-image">
                ${isOutOfStock ? `
                    <div class="out-of-stock-overlay">
                        <span class="out-of-stock-text">Out of Stock</span>
                    </div>
                ` : ''}
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.name}</h3>
                <p class="product-price">RM ${product.price.toFixed(2)}</p>
                <p class="product-size">${product.size || ''}</p>
                
                <div class="product-rating" style="margin-bottom: 10px;"> 
                    <span class="stars">${stars}</span>
                    <span class="rating-count" style="font-size: 14px;">
                        ${product.rating} (${product.reviewCount} Reviews | ${product.soldCount} Sold)
                    </span>
                </div>
                <p class="product-description">${product.description || ''}</p>
            </div>
        </div>`;
}

    function setupProductEventListeners() {
        document.querySelectorAll('.favorite-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();   // prevent card click from firing

        const productId = parseInt(btn.getAttribute('data-id'));  // assuming you use data-id on the button

        if (window.isLoggedIn !== true) {
            // Close quick view modal if it's currently open
            const quickViewModal = document.getElementById('quickViewModal');
            if (quickViewModal && quickViewModal.style.display !== 'none') {
                quickViewModal.style.display = 'none';
            }

            showLoginPrompt();
            return;  // stop here — don't try to toggle favorite
        }

        // Logged in → proceed with normal favorite toggle
        toggleFavorite(productId);
    });
});
    }

    // --- 6. Core Collection Logic ---
    async function toggleFavorite(productId) {
    if (!window.isLoggedIn) {
        showLoginPrompt();
        return;
    }

    // NEW: Disable button temporarily to prevent double-clicks
    const btn = event.currentTarget;  // Assumes called from onclick on the button
    if (btn) btn.disabled = true;

    try {
        const response = await fetch('toggle_favorite.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        });

        if (!response.ok) throw new Error('Network error');

        const data = await response.json();

        if (data.status === 'success') {
            // NEW: Update local favorites from server response (reliable sync)
            favorites = data.favorites.map(id => Number(id));

            // NEW: Re-render UI to update all hearts immediately
            renderProducts();  // Refresh product grid

            // NEW: If quickview is open, refresh it too
            if (quickViewModal && quickViewModal.style.display !== 'none') {
                quickViewProduct(productId);
            }

            showToast(data.action === 'added' 
                ? 'Added to favorites!' 
                : 'Removed from favorites!');
        } else {
            showToast(data.message || 'Failed to update favorite');
        }
    } catch (err) {
        console.error('Toggle favorite failed:', err);
        showToast('Error updating favorite — please try again');
    } finally {
        // NEW: Re-enable button
        if (btn) btn.disabled = false;
    }
}

// --- 7. Optimized Quick View (Advanced design syncing with Favorites + complete sales info) ---
// --- 7. Optimized Quick View (Fully synced with Favorites page design) ---
function quickViewProduct(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;
    
    addToRecentlyViewed(productId);
    
    // Check if the current product is in the favorites
    const isFavorite = favorites.includes(parseInt(product.id));
    

    quickViewContent.innerHTML = `
        <button class="close-modal" id="closeModal" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 28px; cursor: pointer; color: #888; z-index: 10;">×</button>
        
        <div style="display: flex; gap: 40px; padding: 40px; align-items: flex-start;" class="modal-body-flex">
            <div style="flex: 1.1; position: sticky; top: 0;">
                <img src="${product.image}" alt="${product.name}" style="width: 100%; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); object-fit: cover;">
            </div>

            <div style="flex: 1; display: flex; flex-direction: column;">
                <h2 style="margin-bottom: 10px; color: #5a3921; font-size: 1.8rem; line-height: 1.2;">${product.name}</h2>
                
                <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px; font-size: 0.95rem;">
                    <span style="color: #ffc107; font-size: 1.1rem;">${'★'.repeat(Math.floor(product.rating || 0))}☆</span>
                    <span style="color: #5a3921; font-weight: 600;">${product.rating || '0.0'}</span>
                    <span style="color: #ddd;">|</span>
                    <span style="color: #888;">${product.review_count || product.reviewCount || 0} Reviews</span>
                    <span style="color: #ddd;">|</span>
                    <span style="color: #d4a76a; font-weight: 600;">${product.sold_count || product.soldCount || 0} Sold</span>
                </div>
                
                <div style="margin-bottom: 25px; font-size: 1.8rem; font-weight: 700; color: #d4a76a;">
                    RM ${parseFloat(product.price).toFixed(2)}
                </div>
                
                <div style="border-top: 1px solid #f0f0f0; padding-top: 20px; margin-bottom: 25px;">
                    <p style="line-height: 1.8; color: #666; font-size: 1rem;">
                        ${product.full_description || product.description || 'No description available.'}
                    </p>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 30px; background: #f9f5f2; padding: 15px; border-radius: 10px;">
                    <div style="display: flex; border-bottom: 1px dashed #eee; padding-bottom: 10px;">
                        <span style="width: 105px; color: #a1887f; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Ingredients</span>
                        <span style="flex: 1; color: #555; font-size: 0.9rem;">${product.ingredients || 'Natural ingredients'}</span>
                    </div>
                    <div style="display: flex; border-bottom: 1px dashed #eee; padding-bottom: 10px;">
                        <span style="width: 105px; color: #a1887f; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Size</span>
                        <span style="flex: 1; color: #555; font-size: 0.9rem;">${product.size || 'Standard'}</span>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: auto;">
                    <button class="add-to-cart-btn" id="modalAddToCartBtn"
                            style="background: #d4a76a; color: white; border: none; padding: 15px 30px; border-radius: 10px; cursor: pointer; flex: 1; font-weight: 700; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(212, 167, 106, 0.3);">
                        Add to Cart
                    </button>
                    <button class="modal-fav-btn ${isFavorite ? 'active' : ''}" id="modalFavBtn"
                            style="background: #fff; border: 1px solid #ddd; border-radius: 10px; cursor: pointer; width: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px; transition: all 0.2s;">
                        ${isFavorite ? '❤️' : '🤍'}
                    </button>
                </div>
            </div>
        </div>
    `;

    // Show modal
    quickViewModal.style.display = 'flex';
    
    // Bind close event
    document.getElementById('closeModal').onclick = () => quickViewModal.style.display = 'none';
    
    // Bind add to cart event
    document.getElementById('modalAddToCartBtn').onclick = () => { 
    const success = addToCart(product.id, 1);   // ← capture return value
    
    if (success) {
        quickViewModal.style.display = 'none'; 
    }
    // else → keep modal open so user sees the toast / error
};

    // Bind favorite toggle event
    const modalFavBtn = document.getElementById('modalFavBtn');
    modalFavBtn.onclick = () => {
        if (window.isLoggedIn !== true) { showProLoginmpt(); return; }
        
        // Visual immediate feedback
        const isNowActive = modalFavBtn.classList.toggle('active');
        modalFavBtn.innerHTML = isNowActive ? '❤️' : '🤍';
        
        // Call original favorite logic
        toggleFavorite(parseInt(product.id));
    };
}

    // --- 7. Comprehensive logic search (including all subcategory name matches) ---
    function filterProducts() {
        const searchTerm = currentSearch.trim().toLowerCase();

        // ── A. Search mode (when user typed something) ──
        if (searchTerm) {
            // Complete subcategory name mapping
            const subNameMapping = {
                // Cakes Subcategories
                '5 inch': '5 inch Cake',
                'cheese': 'Cheese Flavour',
                'chocolate': 'Chocolate & Coffee',
                'mini': 'Cute Mini Cake',
                'durian': 'Durian Series',
                'festival': 'Festival',
                'fondant': 'Fondant Cake Design',
                'fresh-cream': 'Fresh Cream Cake',
                'full-moon': 'Full Moon Gift Packages',
                'little': 'Little Series',
                'strawberry': 'Strawberry Flavour',
                'animal': 'The Animal Series',
                'vanilla': 'Vanilla Flavour',
                'wedding': 'Wedding Gift Packages',
                // Pastries Subcategories
                'croissant': 'Croissants',
                'danish': 'Danish Pastries',
                'tart': 'Tarts',
                'puff': 'Puff Pastry',
                // Bread Subcategories
                'sourdough': 'Sourdough',
                'wholegrain': 'Whole Grain Bread',
                'artisan': 'Artisan Bread',
                'sweet': 'Sweet Bread'
            };

            return products.filter(p => {
                // 1. Match product name (e.g., Red Velvet)
                const nameMatch = p.name.toLowerCase().includes(searchTerm);
                
                // 2. Match main category (e.g., cake, bread, pastry)
                const catMatch = p.category && p.category.toLowerCase().includes(searchTerm);
                
                // 3. Match subcategory
                const rawSub = p.subcategory ? p.subcategory.replace(/['"]+/g, '').toLowerCase() : '';
                // Match raw code (e.g., "artisan")
                const subRawMatch = rawSub.includes(searchTerm);
                // Match complete display name (e.g., "Artisan Bread")
                const displaySubName = subNameMapping[rawSub] || '';
                const subDisplayMatch = displaySubName.toLowerCase().includes(searchTerm);
                
                // Return true if any condition is met
                return nameMatch || catMatch || subRawMatch || subDisplayMatch;
            });
        }

        // ── B. Category browsing mode (no search term) ──
        return products.filter(product => {
            // Special case: show ALL products when top-level "all" is selected
            if (currentCategory === 'all') {
                return true;
            }

            // Normal case: must match main category
            if (product.category?.toLowerCase() !== currentCategory) {
            return false;
        }

            // When subcategory is 'all' → show all products in this category
            if (currentSubCategory === 'all') {
                return true;
            }

            // Specific subcategory
            const cleanSub = product.subcategory ? product.subcategory.replace(/['"]+/g, '').toLowerCase() : '';
        return cleanSub === currentSubCategory.toLowerCase();
        });
    }

    function sortProducts(list) {
    switch(currentSort) {
        case 'price-low': return [...list].sort((a, b) => a.price - b.price);
        case 'price-high': return [...list].sort((a, b) => b.price - a.price);
        case 'rating': return [...list].sort((a, b) => (b.rating || 0) - (a.rating || 0));
        case 'recent': return [...list].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));  // New case: Sort by created_at descending (newest first)
        default: return [...list].sort((a, b) => a.name.localeCompare(b.name));
    }
}

    function updateActiveCategory() {
        // If searching, display the search keyword in the title
        if (currentSearch.trim()) {
            activeCategory.textContent = `Search Results for "${currentSearch}"`;
            return;
        }

        const categoryNames = {
        'all': 'All Products',
        'cake': 'Cakes',
        'bread': 'Bread',
        'pastry': 'Pastries',
        'cookie': 'Cookies'
    };
        const subNames = {
            'all': categoryNames[currentCategory] || 'All Products',
            'cheese':'Cheese Flavour','chocolate':'Chocolate & Coffee',
            'mini':'Cute Mini Cake','durian':'Durian Series','festival':'Festival',
            'fondant':'Fondant Cake Design','fresh-cream':'Fresh Cream Cake',
            'full-moon':'Full Moon Gift Packages','little':'Little Series',
            'strawberry':'Strawberry Flavour','animal':'The Animal Series',
            'vanilla':'Vanilla Flavour','wedding':'Wedding Gift Packages',
            'croissant':'Croissants','danish':'Danish Pastries','tart':'Tarts',
            'puff':'Puff Pastry','sourdough':'Sourdough','wholegrain':'Whole Grain Bread',
            'artisan':'Artisan Bread','sweet':'Sweet Bread'
        };

        if (activeCategory) {
            activeCategory.textContent = 
                subNames[currentSubCategory] || 
                (categoryNames[currentCategory] || 'Products');
        }
    }

    function updateResultsInfo(total) {
        const showingStart = Math.min((currentPage - 1) * productsPerPage + 1, total);
        const showingEnd = Math.min(currentPage * productsPerPage, total);
        if (resultsInfo) resultsInfo.textContent = `Showing ${total === 0 ? 0 : showingStart}-${showingEnd} of ${total} products ${currentSearch ? ` for "${currentSearch}"` : ''}`;
        updateActiveCategory();
    }

    

    // menu.js around line 262
function loadRecentlyViewed() {
    if (!recentlyViewedSection) return;
    
    // If there is no recently viewed data, hide the section; otherwise, show it
    if (recentlyViewed.length === 0) {
        recentlyViewedSection.style.display = 'none';
        return;
    }
    
    recentlyViewedSection.style.display = 'block';
    recentProductsContainer.innerHTML = '';

    recentlyViewed.forEach(pid => {
        const p = products.find(x => x.id === pid);
        if (p) {
            // Only the image and name are kept; the price has been removed.
            recentProductsContainer.innerHTML += `
                <div class="recent-product-card" data-id="${p.id}">
                    <img src="${p.image}" alt="${p.name}" class="recent-product-image">
                    <h4 class="recent-product-name">${p.name}</h4>
                </div>`;
        }
    });

    // Bind click event: Clicking on a recently viewed product will pop up a corresponding information pop-up window.
    document.querySelectorAll('.recent-product-card').forEach(card => {
        card.addEventListener('click', function() {
            quickViewProduct(parseInt(this.getAttribute('data-id')));
        });
    });
}

    function addToRecentlyViewed(id) {
        recentlyViewed = recentlyViewed.filter(x => x !== id);
        recentlyViewed.unshift(id);
        recentlyViewed = recentlyViewed.slice(0, 5);
        localStorage.setItem('bakeryRecentlyViewed', JSON.stringify(recentlyViewed));
        loadRecentlyViewed();
    }

function addToCart(productId, quantity = 1) {
    if (window.isLoggedIn !== true) {
        const quickView = document.getElementById('quickViewModal');
        if (quickView && quickView.style.display !== 'none') {
            quickView.style.display = 'none';
        }
        showLoginPrompt(); 
        return false;
    }

    const product = products.find(p => p.id == productId);
    if (!product) {
        showToast("Product not found");
        return false;
    }

    const available = Number(product.stock) || 0;
    if (available <= 0) {
        showToast("Sorry, this product is currently out of stock!");
        return false;
    }

    // ── NEW: calculate how many are already in cart ──
    let alreadyInCart = 0;
    const existing = cart.find(item => item.id == productId);
    if (existing) {
        alreadyInCart = Number(existing.quantity) || 0;
    }

    const requestedTotal = alreadyInCart + quantity;

    if (requestedTotal > available) {
        if (alreadyInCart > 0) {
        
            showToast(`Only ${available} available — cannot add more.`);
        }
        return false;
    }
    
    // ── Cart logic ──
    if (!Array.isArray(cart)) cart = [];

const existingIndex = cart.findIndex(item => item.id == productId);
let finalQuantity = quantity; 

if (existingIndex > -1) {
    // 【Key modification point】: If the product is already in the cart, first "pluck" it out of the array
    const existingItem = cart.splice(existingIndex, 1)[0];
    
    // Update quantity
    existingItem.quantity += finalQuantity;
    
    // Put it back at the front of the array (so it becomes the first item again)
    cart.unshift(existingItem);
} else {
    // If it's a new product, put it at the front directly
    cart.unshift({ 
        id: product.id, 
        name: product.name, 
        price: Number(product.price), 
        image: product.image, 
        quantity: finalQuantity,
        maxStock: Number(product.stock)
    });
}

    // Save
    localStorage.setItem('bakeryCart', JSON.stringify(cart));
    
    updateCartCount();
    if (typeof updateHeaderCartCount === 'function') {
        updateHeaderCartCount();
    }

    showToast(`Added ${product.name} to cart!`);

    // async server sync (non-blocking)
    if (typeof forceSyncCart === 'function') {
        forceSyncCart().catch(err => console.warn("Cart sync failed", err));
    }

    return true;   // ← success
}

// --- menu.js 里的修改 ---
function updateCartCount() {
    // 1. Reload the latest cart from local storage
    const currentCart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
    
    // 🟢 Key modification: Calculate total item count (e.g., 4 cakes + 4 breads = 8)
    const totalCount = currentCart.reduce((sum, item) => sum + parseInt(item.quantity || 0), 0);
    
    // 2. Store the total in local storage for reference by other pages.
    localStorage.setItem('cartItemCount', totalCount.toString());
    
    // 3. Update the number label in the current Menu page Header
    const localCount = document.querySelector('.cart-count'); 
    if (localCount) {
        localCount.textContent = totalCount;
        localCount.style.display = totalCount > 0 ? 'flex' : 'none';
    }
}

    function showToast(msg) { if (toast) { toast.textContent = msg; toast.style.display = 'block'; setTimeout(() => { toast.style.display = 'none'; }, 2500); } }

    /**
     * Synchronize the sidebar visual effects based on the current currentCategory and currentSubCategory
     */
    function syncSidebarUI() {
        // 1. Clear all old states
        document.querySelectorAll('.category-main').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.category-arrow').forEach(a => a.classList.remove('active'));
        document.querySelectorAll('.subcategories').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.subcategory-item').forEach(i => i.classList.remove('active'));

        // 2. Activate the corresponding parent category
        const targetMain = document.querySelector(`.category-main[data-category="${currentCategory}"]`);
        if (targetMain) {
            targetMain.classList.add('active');
            
            // Expand the submenu
            const arrow = targetMain.querySelector('.category-arrow');
            if (arrow) arrow.classList.add('active');
            
            const subContainer = targetMain.nextElementSibling;
            if (subContainer && subContainer.classList.contains('subcategories')) {
                subContainer.classList.add('active');
                
                // 3. Activate the corresponding subcategory item
                const targetSub = subContainer.querySelector(`.subcategory-item[data-subcategory="${currentSubCategory}"]`);
                if (targetSub) {
                    targetSub.classList.add('active');
                }
            }
        }
        updateActiveCategory();
    }


    initPage();
});

function showLoginPrompt() { const m = document.getElementById('loginPromptModal'); if (m) m.style.display = 'flex'; }
function closeLoginPrompt() { const m = document.getElementById('loginPromptModal'); if (m) m.style.display = 'none'; }