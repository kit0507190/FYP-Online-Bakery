document.addEventListener('DOMContentLoaded', function () {

    // --- 1. 变量定义 ---
    let products = []; 
    let cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
    let favorites = []; // 初始为空，稍后从数据库同步
    let recentlyViewed = JSON.parse(localStorage.getItem('bakeryRecentlyViewed')) || [];

    // --- 2. 获取 DOM 元素 ---
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

    let currentCategory = 'cake'; 
    let currentSubCategory = 'all';
    let currentSearch = '';
    let currentSort = 'name';
    let currentPage = 1;
    const productsPerPage = 9;

    // --- 3. 核心初始化 ---
    async function initPage() {
        setupEventListeners();
        if (loadingSpinner) loadingSpinner.style.display = 'block';

        try {
            // A. 获取产品
            const response = await fetch('get_products.php');
            if (!response.ok) throw new Error('Network Error');
            products = await response.json();

            // B. 同步数据库收藏状态
            if (window.isLoggedIn === true) {
                const favRes = await fetch('get_user_favorites.php');
                if (favRes.ok) {
                    favorites = await favRes.json();
                }
            }

            renderProducts();
            updateCartCount();
            loadRecentlyViewed();
        } catch (error) {
            console.error("加载出错:", error);
            if(productsGrid) productsGrid.innerHTML = '<div class="no-products">System loading failed.</div>';
        } finally {
            if (loadingSpinner) loadingSpinner.style.display = 'none';
        }
    }

    // --- 4. 监听器 (保持不变) ---
    function setupEventListeners() {
        document.querySelectorAll('.category-main').forEach(btn => {
            btn.addEventListener('click', function() {
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
                    const allSub = this.nextElementSibling?.querySelector('.subcategory-item[data-subcategory="all"]');
                    if (allSub) allSub.classList.add('active');
                    currentPage = 1;
                    updateActiveCategory();
                    renderProducts();
                }
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

    // --- 5. 渲染逻辑 ---
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
        if (pageIndicator) pageIndicator.textContent = `Page ${currentPage} / ${maxPage}`;
        setupProductEventListeners();
    }

    function createProductCard(product) {
        const isFav = favorites.includes(parseInt(product.id));
        const badge = (product.tags && product.tags.includes('popular')) ? 'popular' :
                      (product.tags && product.tags.includes('new')) ? 'new' : '';
        const stars = '★'.repeat(Math.floor(product.rating || 0)) + '☆'.repeat(Math.max(0, 5 - Math.floor(product.rating||0)));
        return `
            <div class="product-card" data-id="${product.id}">
                ${badge ? `<div class="product-badge ${badge}">${badge === 'popular' ? 'Popular' : 'New'}</div>` : ''}
                <button class="favorite-btn ${isFav ? 'active' : ''}" data-id="${product.id}">${isFav ? '❤️' : '🤍'}</button>
                <img src="${product.image}" alt="${product.name}" class="product-image">
                <div class="product-info">
                    <h3 class="product-name">${product.name}</h3>
                    <p class="product-price">RM ${product.price.toFixed(2)}</p>
                    <p class="product-size">${product.size || ''}</p>
                    <div class="product-rating"><span class="stars">${stars}</span><span>${product.rating || ''}</span></div>
                    <p class="product-description">${product.description || ''}</p>
                </div>
            </div>`;
    }

    function setupProductEventListeners() {
        document.querySelectorAll('.favorite-btn').forEach(btn => btn.addEventListener('click', (e) => { 
            e.stopPropagation(); 
            // 列表页点击：直接调用逻辑
            toggleFavorite(parseInt(btn.getAttribute('data-id'))); 
        }));

        document.querySelectorAll('.product-card').forEach(card => card.addEventListener('click', function(e) { 
            if (!e.target.closest('.favorite-btn')) { 
                quickViewProduct(parseInt(this.getAttribute('data-id'))); 
            } 
        }));
    }

    // --- 6. 核心收藏逻辑 ---
    async function toggleFavorite(id) {
        if (window.isLoggedIn !== true) { showLoginPrompt(); return; }

        const product = products.find(p => p.id == id);
        const pName = product ? product.name : 'Product';

        // 注意：这里我们不再等待请求完成才更新，而是由各个按钮的点击事件负责即时反馈
        // 此函数主要负责发送请求和更新全局数组
        try {
            const response = await fetch('toggle_favorite.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: id, product_name: pName })
            });
            const result = await response.json();
            
            if (result.status === 'success') {
                if (result.action === 'added') {
                    if (!favorites.includes(id)) favorites.push(id);
                    showToast(`Added ${pName} to favorites! ❤️`);
                } else {
                    favorites = favorites.filter(x => x !== id);
                    showToast('Removed from favorites 🤍');
                }
                // 更新背景列表状态
                renderProducts(); 
            }
        } catch (e) { console.error(e); }
    }

    // --- 7. 修正后的 Quick View (包含 Inch 逻辑和极速反馈) ---
function quickViewProduct(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;
    
    addToRecentlyViewed(productId); //
    
    // 检查当前状态
    const isFavorite = favorites.includes(parseInt(product.id)); //

    quickViewContent.innerHTML = `
        <button class="close-modal" id="closeModal">×</button>
        <div style="display: flex; gap: 30px; padding: 30px;">
            <div style="flex: 1;">
                <img src="${product.image}" alt="${product.name}" style="width: 100%; border-radius: 10px;">
            </div>
            <div style="flex: 1;">
                <h2 style="margin-bottom: 15px; color: #5a3921;">${product.name}</h2>
                <p style="font-size: 24px; color: #d4a76a; font-weight: bold; margin-bottom: 15px;">RM ${product.price.toFixed(2)}</p>
                <div style="margin-bottom: 15px;">
                    <span class="stars">${'★'.repeat(Math.floor(product.rating||0))}☆</span>
                    <span>${product.rating || ''} (${product.reviewCount || 0} reviews)</span>
                </div>
                <p style="margin-bottom: 20px; line-height: 1.6;">${product.fullDescription || product.description || ''}</p>
                <p><strong>Ingredients:</strong> ${product.ingredients || ''}</p>
                
                <p><strong>Inch:</strong> ${product.inch || ''}</p>
                
                <p><strong>Allergens:</strong> ${product.allergens || ''}</p>
                
                <div style="display: flex; gap: 10px; margin-top: 25px; align-items: stretch;">
                    <button class="add-to-cart-btn" 
                            style="background: #d4a76a; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; flex: 1; font-weight: bold;">
                        Add to Cart
                    </button>
                    
                    <button class="modal-fav-btn ${isFavorite ? 'active' : ''}" 
                            data-id="${product.id}"
                            style="position: relative; background: #f5f5f5; border: 1px solid #ddd; border-radius: 5px; cursor: pointer; width: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        ${isFavorite ? '❤️' : '🤍'}
                    </button>
                </div>
            </div>
        </div>`;

    quickViewModal.style.display = 'flex';
    
    // 绑定关闭按钮
    document.getElementById('closeModal').onclick = () => quickViewModal.style.display = 'none';
    
    // 绑定加入购物车按钮
    quickViewContent.querySelector('.add-to-cart-btn').onclick = () => { 
        addToCart(product.id, 1);
        quickViewModal.style.display = 'none'; 
    };

    // 绑定收藏按钮（极速反馈逻辑）
    const modalFavBtn = quickViewContent.querySelector('.modal-fav-btn');
    modalFavBtn.onclick = () => {
        if (window.isLoggedIn !== true) { showLoginPrompt(); return; }

        // 立即切换视觉状态 (0 延迟)
        const isNowActive = modalFavBtn.classList.toggle('active');
        modalFavBtn.innerHTML = isNowActive ? '❤️' : '🤍';

        // 后台发送请求同步数据库
        toggleFavorite(product.id);
    };
}

    // --- 7. 辅助函数 ---
    function filterProducts() {
        return products.filter(product => {
            if (product.category !== currentCategory) return false;
            const cleanSub = product.subcategory ? product.subcategory.replace(/['"]+/g, '') : '';
            if (currentSubCategory !== 'all' && cleanSub !== currentSubCategory) return false;
            if (currentSearch && !(product.name.toLowerCase().includes(currentSearch.toLowerCase()) || (product.description && product.description.toLowerCase().includes(currentSearch.toLowerCase())))) return false;
            return true;
        });
    }

    function sortProducts(list) {
        switch(currentSort) {
            case 'price-low': return [...list].sort((a, b) => a.price - b.price);
            case 'price-high': return [...list].sort((a, b) => b.price - a.price);
            case 'rating': return [...list].sort((a, b) => (b.rating || 0) - (a.rating || 0));
            case 'popular': return [...list].sort((a, b) => (b.reviewCount || 0) - (a.reviewCount || 0));
            default: return [...list].sort((a, b) => a.name.localeCompare(b.name));
        }
    }

    function updateActiveCategory() {
        const categoryNames = {'bread':'Bread','cake':'Cakes','pastry':'Pastries','cookie':'Cookies'};
        const subNames = {'all':`All ${categoryNames[currentCategory]}`,'5 inch':'5 inch Cake','cheese':'Cheese Flavour','chocolate':'Chocolate & Coffee','mini':'Cute Mini Cake','durian':'Durian Series','festival':'Festival','fondant':'Fondant Cake Design','fresh-cream':'Fresh Cream Cake','full-moon':'Full Moon Gift Packages','little':'Little Series','strawberry':'Strawberry Flavour','animal':'The Animal Series','vanilla':'Vanilla Flavour','wedding':'Wedding Gift Packages','croissant':'Croissants','danish':'Danish Pastries','tart':'Tarts','puff':'Puff Pastry','sourdough':'Sourdough','wholegrain':'Whole Grain Bread','artisan':'Artisan Bread','sweet':'Sweet Bread'};
        if (activeCategory) activeCategory.textContent = currentSubCategory !== 'all' ? (subNames[currentSubCategory] || 'Products') : (subNames['all'] || 'Products');
    }

    function updateResultsInfo(total) {
        const showingStart = Math.min((currentPage - 1) * productsPerPage + 1, total);
        const showingEnd = Math.min(currentPage * productsPerPage, total);
        if (resultsInfo) resultsInfo.textContent = `Showing ${total === 0 ? 0 : showingStart}-${showingEnd} of ${total} products ${currentSearch ? ` for "${currentSearch}"` : ''}`;
        updateActiveCategory();
    }

    

    function loadRecentlyViewed() {
        if (!recentlyViewedSection || recentlyViewed.length === 0) return;
        recentProductsContainer.innerHTML = '';
        recentlyViewed.forEach(pid => {
            const p = products.find(x => x.id === pid);
            if (p) {
                recentProductsContainer.innerHTML += `<div class="recent-product-card" data-id="${p.id}"><img src="${p.image}" alt="${p.name}"><h4>${p.name}</h4><p>RM ${p.price.toFixed(2)}</p></div>`;
            }
        });
        document.querySelectorAll('.recent-product-card').forEach(card => card.addEventListener('click', function(){ quickViewProduct(parseInt(this.getAttribute('data-id'))); }));
    }

    function addToRecentlyViewed(id) {
        recentlyViewed = recentlyViewed.filter(x => x !== id);
        recentlyViewed.unshift(id);
        recentlyViewed = recentlyViewed.slice(0, 5);
        localStorage.setItem('bakeryRecentlyViewed', JSON.stringify(recentlyViewed));
        loadRecentlyViewed();
    }

    function addToCart(productId, quantity = 1) {
    if (window.isLoggedIn !== true) { showLoginPrompt(); return; }
    
    const product = products.find(p => p.id == productId);
    if (!product) return;

    // --- 核心逻辑：确保新加的在最上面 ---
    const existingIndex = cart.findIndex(i => i.id == productId);
    let finalQuantity = quantity;

    if (existingIndex > -1) {
        // 如果已经存在，先拿走它原来的数量，然后把它从数组中删掉
        finalQuantity = cart[existingIndex].quantity + quantity;
        cart.splice(existingIndex, 1);
    }

    // 将商品（不管是新加的还是更新的）重新推入数组末尾
    cart.push({ 
        id: product.id, 
        name: product.name, 
        price: product.price, 
        image: product.image, 
        quantity: finalQuantity 
    });
    // ----------------------------------

    localStorage.setItem('bakeryCart', JSON.stringify(cart));
    updateCartCount();
    showToast(`${product.name} added to cart!`);
    
    // 触发同步（由于 menu.js 没有内置 syncCartToDB，你可以调用你 menu.php 里写的 forceSyncCart）
    if (typeof forceSyncCart === 'function') {
        forceSyncCart();
    }
}

    function updateCartCount() {
        const total = cart.reduce((s, i) => s + i.quantity, 0);
        localStorage.setItem('cartItemCount', total.toString());
        if (cartCount) cartCount.textContent = total;
    }

    function showToast(msg) { if (toast) { toast.textContent = msg; toast.style.display = 'block'; setTimeout(() => { toast.style.display = 'none'; }, 2500); } }

    initPage();
});

function showLoginPrompt() { const m = document.getElementById('loginPromptModal'); if (m) m.style.display = 'flex'; }
function closeLoginPrompt() { const m = document.getElementById('loginPromptModal'); if (m) m.style.display = 'none'; }