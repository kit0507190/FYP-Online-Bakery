document.addEventListener('DOMContentLoaded', function () {

        // ---------------------------------------------------------
        // PRODUCTS: keep your original items (IDs 1-29) and add 30-38
        // (I preserved your original array entries and appended new ones)
        // ---------------------------------------------------------


        // Shopping cart
        let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
        let favorites = JSON.parse(localStorage.getItem('bakeryFavorites')) || [];
        let recentlyViewed = JSON.parse(localStorage.getItem('bakeryRecentlyViewed')) || [];

        // DOM elements
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

        // Current filter state
        let currentCategory = 'cake'; // Default to cake
        let currentSubCategory = 'all';
        let currentSearch = '';
        let currentSort = 'name';
        let currentPage = 1;
        const productsPerPage = 9;

        // Initialize page
        function initPage() {
            renderProducts();
            updateCartCount();
            loadRecentlyViewed();
            setupEventListeners();
        }

        // Render products with pagination (prev/next)
        function renderProducts() {
            productsGrid.innerHTML = '';
            loadingSpinner.style.display = 'block';

            setTimeout(() => {
                let filteredProducts = filterProducts();

                // Sort products
                filteredProducts = sortProducts(filteredProducts);

                // Pagination maths
                const total = filteredProducts.length;
                const maxPage = Math.max(1, Math.ceil(total / productsPerPage));
                if (currentPage > maxPage) currentPage = maxPage;
                if (currentPage < 1) currentPage = 1;
                const startIndex = (currentPage - 1) * productsPerPage;
                const endIndex = startIndex + productsPerPage;
                const productsToShow = filteredProducts.slice(startIndex, endIndex);

                // Render
                if (productsToShow.length === 0) {
                    productsGrid.innerHTML = '<div class="no-products">No products found matching your criteria.</div>';
                } else {
                    productsToShow.forEach(product => {
                        productsGrid.innerHTML += createProductCard(product);
                    });
                }

                // Update results info and pagination buttons
                updateResultsInfo(total);
                pageIndicator.textContent = `Page ${currentPage} / ${maxPage}`;
                prevPageBtn.disabled = (currentPage <= 1);
                nextPageBtn.disabled = (currentPage >= maxPage);

                loadingSpinner.style.display = 'none';
                setupProductEventListeners();
            }, 150);
        }

        // Filter products based on current criteria
        function filterProducts() {
            return products.filter(product => {
                // Category filter
                if (product.category !== currentCategory) return false;

                // Subcategory filter
                if (currentSubCategory !== 'all' && product.subcategory !== currentSubCategory) return false;

                // Search filter
                if (currentSearch && !(product.name.toLowerCase().includes(currentSearch.toLowerCase()) ||
                    (product.description && product.description.toLowerCase().includes(currentSearch.toLowerCase())))) return false;

                return true;
            });
        }

        // Sort products
        function sortProducts(productsList) {
            switch(currentSort) {
                case 'price-low':
                    return [...productsList].sort((a, b) => a.price - b.price);
                case 'price-high':
                    return [...productsList].sort((a, b) => b.price - a.price);
                case 'rating':
                    return [...productsList].sort((a, b) => (b.rating || 0) - (a.rating || 0));
                case 'popular':
                    return [...productsList].sort((a, b) => (b.reviewCount || 0) - (a.reviewCount || 0));
                default:
                    return [...productsList].sort((a, b) => a.name.localeCompare(b.name));
            }
        }

        // Create product card HTML (string)
        function createProductCard(product) {
            const isFavorite = favorites.includes(product.id);
            const badge = (product.tags && product.tags.includes('popular')) ? 'popular' :
                          (product.tags && product.tags.includes('new')) ? 'new' : '';
            const ratingFloor = Math.floor(product.rating || 0);
            const stars = '★'.repeat(ratingFloor) + '☆'.repeat(Math.max(0, 5 - ratingFloor));
            // ensure description safe
            const desc = product.description ? product.description : '';
            return `
                <div class="product-card" data-id="${product.id}">
                    ${badge ? `<div class="product-badge ${badge}">${badge === 'popular' ? 'Popular' : 'New'}</div>` : ''}
                    <button class="favorite-btn ${isFavorite ? 'active' : ''}" data-id="${product.id}">${isFavorite ? '❤️' : '🤍'}</button>
                    <img src="${product.image}" alt="${product.name}" class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">${product.name}</h3>
                        <p class="product-price">RM ${product.price.toFixed(2)}</p>
                        <p class="product-size">${product.size || ''}</p>
                        <div class="product-rating"><span class="stars">${stars}</span><span>${product.rating || ''}</span><span class="rating-count">(${product.reviewCount || 0})</span></div>
                        <p class="product-description">${desc}</p>
                    </div>
                </div>
            `;
        }

        // Update results information
        function updateResultsInfo(totalProducts) {
            const showingStart = Math.min((currentPage - 1) * productsPerPage + 1, totalProducts);
            const showingEnd = Math.min(currentPage * productsPerPage, totalProducts);
            let infoText = `Showing ${totalProducts === 0 ? 0 : showingStart}-${showingEnd} of ${totalProducts} products`;
            if (currentSearch) infoText += ` for "${currentSearch}"`;
            resultsInfo.textContent = infoText;
            // update active category label
            updateActiveCategory();
        }

        // Update active category display text
        function updateActiveCategory() {
            const categoryNames = {'bread':'Bread','cake':'Cakes','pastry':'Pastries','cookie':'Cookies'};
            const subNames = {'all':`All ${categoryNames[currentCategory]}`,'5 inch':'5 inch Cake','cheese':'Cheese Flavour','chocolate':'Chocolate & Coffee','mini':'Cute Mini Cake','durian':'Durian Series','festival':'Festival','fondant':'Fondant Cake Design','fresh-cream':'Fresh Cream Cake','full-moon':'Full Moon Gift Packages','little':'Little Series','strawberry':'Strawberry Flavour','animal':'The Animal Series','vanilla':'Vanilla Flavour','wedding':'Wedding Gift Packages','croissant':'Croissants','danish':'Danish Pastries','tart':'Tarts','puff':'Puff Pastry','chocolatechip':'Chocolate Chip Cookies','butter':'Butter Cookies','oatmeal':'Oatmeal Cookies','special':'Special Cookies'};
            activeCategory.textContent = currentSubCategory !== 'all' ? (subNames[currentSubCategory] || 'Products') : (subNames['all'] || 'Products');
        }

        // 在 menu.js 中找到这个函数并修改
function viewProductDetails(productId) {
    addToRecentlyViewed(productId);
    // 修改这里：跳转到 php 页面，并把 ID 传过去
    window.location.href = 'product_detail.php?id=' + productId;
}

        // Quick view product in modal
        function quickViewProduct(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) return;
            addToRecentlyViewed(productId);
            const isFavorite = favorites.includes(product.id);
            quickViewContent.innerHTML = `
                <button class="close-modal" id="closeModal">×</button>
                <div style="display: flex; gap: 30px; padding: 30px;">
                    <div style="flex: 1;">
                        <img src="${product.image}" alt="${product.name}" style="width: 100%; border-radius: 10px;">
                    </div>
                    <div style="flex: 1;">
                        <h2 style="margin-bottom: 15px; color: #5a3921;">${product.name}</h2>
                        <p style="font-size: 24px; color: #d4a76a; font-weight: bold; margin-bottom: 15px;">RM ${product.price.toFixed(2)}</p>
                        <div style="display: flex; align-items: center; margin-bottom: 15px;"><span class="stars">${'★'.repeat(Math.floor(product.rating||0))}☆</span><span style="margin-left: 10px;">${product.rating || ''} (${product.reviewCount || 0} reviews)</span></div>
                        <p style="margin-bottom: 20px; line-height: 1.6;">${product.fullDescription || product.description || ''}</p>
                        <div style="margin-bottom: 20px;"><strong>Ingredients:</strong> ${product.ingredients || ''}</div>
                        <div style="margin-bottom: 20px;"><strong>Weight:</strong> ${product.weight || ''}</div>
                        <div style="margin-bottom: 20px;"><strong>Allergens:</strong> ${product.allergens || ''}</div>
                        <div style="display: flex; gap: 10px; margin-top: 25px;">
                            <button class="add-to-cart-btn" data-id="${product.id}" style="background: #d4a76a; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; flex: 2;">Add to Cart</button>
                            <button class="favorite-btn ${isFavorite ? 'active' : ''}" data-id="${product.id}" style="background: #f5f5f5; border: 1px solid #ddd; padding: 12px; border-radius: 5px; cursor: pointer;">${isFavorite ? '❤️' : '🤍'}</button>
                        </div>
                    </div>
                </div>
            `;
            quickViewModal.style.display = 'flex';
            document.getElementById('closeModal').addEventListener('click', closeQuickView);
            quickViewModal.addEventListener('click', (e) => { if (e.target === quickViewModal) closeQuickView(); });
            const addToCartBtn = quickViewContent.querySelector('.add-to-cart-btn');
            const favoriteBtn = quickViewContent.querySelector('.favorite-btn');
            addToCartBtn.addEventListener('click', () => { addToCart(product.id, 1); closeQuickView(); });
            favoriteBtn.addEventListener('click', () => { toggleFavorite(product.id); favoriteBtn.innerHTML = favorites.includes(product.id) ? '❤️' : '🤍'; favoriteBtn.classList.toggle('active'); });
        }

        function closeQuickView() { quickViewModal.style.display = 'none'; }

        // Recently viewed management (keep 5 most recent by default)
        function addToRecentlyViewed(productId) {
            recentlyViewed = recentlyViewed.filter(id => id !== productId);
            recentlyViewed.unshift(productId);
            recentlyViewed = recentlyViewed.slice(0, 5);
            localStorage.setItem('bakeryRecentlyViewed', JSON.stringify(recentlyViewed));
            loadRecentlyViewed();
        }

        function loadRecentlyViewed() {
            if (!recentlyViewed || recentlyViewed.length === 0) {
                recentlyViewedSection.style.display = 'none';
                return;
            }
            recentProductsContainer.innerHTML = '';
            recentlyViewed.forEach(pid => {
                const p = products.find(x => x.id === pid);
                if (p) {
                    recentProductsContainer.innerHTML += `<div class="recent-product-card" data-id="${p.id}"><img src="${p.image}" alt="${p.name}" class="recent-product-image"><h4>${p.name}</h4><p style="color:#d4a76a;font-weight:bold;margin:5px 0;">RM ${p.price.toFixed(2)}</p></div>`;
                }
            });
            recentlyViewedSection.style.display = 'block';
            document.querySelectorAll('.recent-product-card').forEach(card => { card.addEventListener('click', function(){ const id = parseInt(this.getAttribute('data-id')); viewProductDetails(id); }); });
        }

        // Toggle favorite
        function toggleFavorite(productId) {
            if (favorites.includes(productId)) favorites = favorites.filter(id => id !== productId);
            else favorites.push(productId);
            localStorage.setItem('bakeryFavorites', JSON.stringify(favorites));
            showToast(favorites.includes(productId) ? 'Added to favorites!' : 'Removed from favorites');
        }

        // Add to cart
        // --- 修改后的 addToCart 函数 ---
function addToCart(productId, quantity = 1) {
    // 🟢 核心修改点：统一使用 window.isLoggedIn 来判断
    // 这样只要 header 识别到你登录了（不管换哪个号），这里都会是 true
    if (window.isLoggedIn !== true) { 
        showLoginPrompt(); // 如果没登录，显示弹窗
        return;            // 拦截！
    }

    // 2. 如果已登录，继续执行原来的加购逻辑
    const product = products.find(p => p.id === productId);
    if (!product) return;
    
    const existing = cart.find(i => i.id === productId);
    if (existing) {
        existing.quantity += quantity;
    } else {
        cart.push({ 
            id: product.id, 
            name: product.name, 
            price: product.price, 
            image: product.image, 
            quantity: quantity 
        });
    }
    
    localStorage.setItem('bakeryCart', JSON.stringify(cart));
    updateCartCount();
    showToast(`${product.name} added to cart!`);
}



        function updateCartCount() {
    // 1. 计算购物车总数
    const total = cart.reduce((s, i) => s + i.quantity, 0);
    
    // 2. 将数量同步到 localStorage，这样跳转到其他页面（如 cart.php）时 header 能读到最新值
    localStorage.setItem('cartItemCount', total.toString());

    // 3. 更新当前页面 header 里的红色数字
    // 注意：header.php 中定义的类名是 .cart-count
    const cartCountElement = document.querySelector('.cart-count');
    
    if (cartCountElement) {
        cartCountElement.textContent = total;
    } else {
        console.warn('未找到 .cart-count 元素，请检查 header.php 是否包含该类名');
    }
}

        function showToast(msg) {
            toast.textContent = msg;
            toast.style.display = 'block';
            setTimeout(() => { toast.style.display = 'none'; }, 2500);
        }

        // Setup product event listeners (delegated attachments after render)
        function setupProductEventListeners() {
            // view-details
            // favorites
            document.querySelectorAll('.favorite-btn').forEach(btn => btn.addEventListener('click', (e) => { e.stopPropagation(); const id = parseInt(btn.getAttribute('data-id')); toggleFavorite(id); btn.innerHTML = favorites.includes(id) ? '❤️' : '🤍'; btn.classList.toggle('active'); }));

            // product-card click -> quick view
            document.querySelectorAll('.product-card').forEach(card => card.addEventListener('click', function(e) { if (!e.target.closest('.favorite-btn')) { const id = parseInt(this.getAttribute('data-id')); quickViewProduct(id); } }));
        }

        // Setup event listeners for controls and categories
        function setupEventListeners() {

    

    // ✅ 一定要防 null
    if (cartIcon) {
        cartIcon.addEventListener('click', () => {
            window.location.href = 'cart.php';
        });
    }




            document.querySelectorAll('.category-main').forEach(btn => btn.addEventListener('click', function(){
                const category = this.getAttribute('data-category');
                document.querySelectorAll('.category-main').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const arrow = this.querySelector('.category-arrow');
                if (arrow) arrow.classList.toggle('active');
                const sub = this.nextElementSibling;
                if (sub) sub.classList.toggle('active');
                if (category !== currentCategory) {
                    currentCategory = category;
                    currentSubCategory = 'all';
                    document.querySelectorAll('.subcategory-item').forEach(i => i.classList.remove('active'));
                    const allSub = this.nextElementSibling.querySelector('.subcategory-item[data-subcategory=\"all\"]');
                    if (allSub) allSub.classList.add('active');
                    currentPage = 1;
                    updateActiveCategory();
                    renderProducts();
                }
            }));

            document.querySelectorAll('.subcategory-item').forEach(item => item.addEventListener('click', function(e){
                e.preventDefault();
                document.querySelectorAll('.subcategory-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                currentSubCategory = this.getAttribute('data-subcategory');
                currentPage = 1;
                updateActiveCategory();
                renderProducts();
            }));

            searchBtn.addEventListener('click', () => { currentSearch = searchInput.value.trim(); currentPage = 1; renderProducts(); });
            searchInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') { currentSearch = searchInput.value.trim(); currentPage = 1; renderProducts(); } });

            sortSelect.addEventListener('change', () => { currentSort = sortSelect.value; currentPage = 1; renderProducts(); });

            prevPageBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderProducts(); } });
            nextPageBtn.addEventListener('click', () => {
                // compute max page based on current filters to avoid exceeding
                const total = filterProducts().length;
                const maxPage = Math.max(1, Math.ceil(total / productsPerPage));
                if (currentPage < maxPage) { currentPage++; renderProducts(); }
            });

            backToTop.addEventListener('click', () => { window.scrollTo({ top: 0, behavior: 'smooth' }); });
            window.addEventListener('scroll', () => { backToTop.style.display = window.pageYOffset > 300 ? 'block' : 'none'; });
        }

        // Initialize after DOM loaded
        // document.addEventListener('DOMContentLoaded', initPage);
		
		initPage();

        
});

// 🟢 把下面这两个函数移动到这一行（也就是最外面）
function showLoginPrompt() {
    const modal = document.getElementById('loginPromptModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeLoginPrompt() {
    const modal = document.getElementById('loginPromptModal');
    if (modal) {
        modal.style.display = 'none';
    }
}


