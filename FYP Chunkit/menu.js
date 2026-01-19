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
    // --- 3. 核心初始化 ---
    // --- 3. 核心初始化 ---
    async function initPage() {
        // A. 从 URL 中读取分类、子分类以及要自动打开的产品 ID
        const urlParams = new URLSearchParams(window.location.search);
        const catParam = urlParams.get('category');
        const subParam = urlParams.get('subcategory');
        const openId = urlParams.get('open_id'); // 👈 新增：获取要弹窗的产品 ID

        if (catParam) {
            currentCategory = catParam;
            currentSubCategory = subParam || 'all';
        }

        setupEventListeners();
        // B. 同步侧边栏的视觉状态
        syncSidebarUI();

        if (loadingSpinner) loadingSpinner.style.display = 'block';

        try {
            const response = await fetch('get_products.php');
            if (!response.ok) throw new Error('Network Error');
            products = await response.json();

            if (window.isLoggedIn === true) {
                const favRes = await fetch('get_user_favorites.php');
                if (favRes.ok) {
                    favorites = await favRes.json();
                }
            }

            renderProducts();
            updateCartCount();
            loadRecentlyViewed();

            // --- ✨ 新增：如果 URL 带有 open_id，则自动触发弹窗 ---
            if (openId) {
                const productId = parseInt(openId);
                // 稍微延迟执行，确保 renderProducts 已经把 DOM 渲染好了
                setTimeout(() => {
                    quickViewProduct(productId);
                }, 300); 
            }
            // ----------------------------------------------

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
                    <p class="product-size">${product.shortSize || ''}</p>
                  
<div class="product-rating" style="margin-bottom: 10px;"> 
    <span class="stars">${'★'.repeat(Math.floor(product.rating))}${'☆'.repeat(5-Math.floor(product.rating))}</span>
    <span class="rating-count" style="font-size: 14px;">
        ${product.rating} (${product.reviewCount} Reviews | ${product.soldCount} Sold)
    </span>
</div>
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
// --- 7. 优化后的 Quick View (同步 Favorites 的高级设计 + 补全销量信息) ---
function quickViewProduct(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;
    
    addToRecentlyViewed(productId);
    
    // 检查当前收藏状态
    const isFavorite = favorites.includes(parseInt(product.id));

    // 重新构建内容：Favorites 的排版 + Menu 的数据
    quickViewContent.innerHTML = `
        <button class="close-modal" id="closeModal" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 28px; cursor: pointer; color: #888; z-index: 10;">×</button>
        
        <div style="display: flex; gap: 40px; padding: 40px; align-items: flex-start;">
            <div style="flex: 1.1; position: sticky; top: 0;">
                <img src="${product.image}" alt="${product.name}" style="width: 100%; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); object-fit: cover;">
            </div>

            <div style="flex: 1; display: flex; flex-direction: column;">
                <h2 style="margin-bottom: 10px; color: #5a3921; font-size: 1.8rem; line-height: 1.2;">${product.name}</h2>
                
                <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px; font-size: 0.95rem;">
                    <span style="color: #ffc107; font-size: 1.1rem;">${'★'.repeat(Math.floor(product.rating || 0))}☆</span>
                    <span style="color: #5a3921; font-weight: 600;">${product.rating || '0.0'}</span>
                    <span style="color: #ddd;">|</span>
                    <span style="color: #888;">${product.reviewCount || 0} Reviews</span>
                    <span style="color: #ddd;">|</span>
                    <span style="color: #d4a76a; font-weight: 600;">${product.soldCount || 0} Sold</span>
                </div>

                <p style="font-size: 28px; color: #d4a76a; font-weight: 700; margin-bottom: 25px;">RM ${parseFloat(product.price).toFixed(2)}</p>
                
                <div style="border-top: 1px solid #f0f0f0; padding-top: 20px; margin-bottom: 25px;">
                    <p style="line-height: 1.8; color: #666; font-size: 1rem;">${product.fullDescription || product.description || ''}</p>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 30px;">
                    <div style="display: flex; border-bottom: 1px dashed #eee; padding-bottom: 10px;">
                        <span style="width: 105px; color: #a1887f; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Ingredients</span>
                        <span style="flex: 1; color: #555; font-size: 0.9rem;">${product.ingredients || 'Natural ingredients'}</span>
                    </div>
                    <div style="display: flex; border-bottom: 1px dashed #eee; padding-bottom: 10px;">
                        <span style="width: 105px; color: #a1887f; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Size</span>
                        <span style="flex: 1; color: #555; font-size: 0.9rem;">${product.fullSize || product.shortSize || 'Standard'}</span>
                    </div>
                    <div style="display: flex; border-bottom: 1px dashed #eee; padding-bottom: 10px;">
                        <span style="width: 105px; color: #a1887f; font-weight: 600; font-size: 0.9rem; text-transform: uppercase;">Allergens</span>
                        <span style="flex: 1; color: #e57373; font-size: 0.9rem; font-weight: 500;">${product.allergens || 'None'}</span>
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

    quickViewModal.style.display = 'flex';
    
    // 重新绑定按钮事件
    document.getElementById('closeModal').onclick = () => quickViewModal.style.display = 'none';
    
    document.getElementById('modalAddToCartBtn').onclick = () => { 
        addToCart(product.id, 1);
        quickViewModal.style.display = 'none'; 
    };

    const modalFavBtn = document.getElementById('modalFavBtn');
    modalFavBtn.onclick = () => {
        if (window.isLoggedIn !== true) { showLoginPrompt(); return; }
        const isNowActive = modalFavBtn.classList.toggle('active');
        modalFavBtn.innerHTML = isNowActive ? '❤️' : '🤍';
        toggleFavorite(product.id); // 调用原有的逻辑
    };
}

    // --- 7. 全能逻辑搜索 (包含所有子分类名称匹配) ---
    function filterProducts() {
        const searchTerm = currentSearch.trim().toLowerCase();

        // 🚀 逻辑 A：智能搜索模式 (当搜索框有内容时)
        if (searchTerm) {
            // 完整子分类名字映射表
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
                // 1. 匹配产品名称 (例如: Red Velvet)
                const nameMatch = p.name.toLowerCase().includes(searchTerm);
                
                // 2. 匹配大分类 (例如: cake, bread, pastry)
                const catMatch = p.category && p.category.toLowerCase().includes(searchTerm);
                
                // 3. 匹配子分类
                const rawSub = p.subcategory ? p.subcategory.replace(/['"]+/g, '').toLowerCase() : '';
                // 匹配原始代号 (例如: "artisan")
                const subRawMatch = rawSub.includes(searchTerm);
                // 匹配完整显示名称 (例如: "Artisan Bread")
                const displaySubName = subNameMapping[rawSub] || '';
                const subDisplayMatch = displaySubName.toLowerCase().includes(searchTerm);
                
                // 只要满足任意一个条件，就搜出来
                return nameMatch || catMatch || subRawMatch || subDisplayMatch;
            });
        }

        // 🚀 逻辑 B：侧边栏浏览模式 (搜索框为空时)
        return products.filter(product => {
            if (product.category !== currentCategory) return false;
            const cleanSub = product.subcategory ? product.subcategory.replace(/['"]+/g, '') : '';
            if (currentSubCategory !== 'all' && cleanSub !== currentSubCategory) return false;
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
        // 如果正在搜索，标题显示搜索关键词
        if (currentSearch.trim()) {
            activeCategory.textContent = `Search Results for "${currentSearch}"`;
            return;
        }

        const categoryNames = {'bread':'Bread','cake':'Cakes','pastry':'Pastries','cookie':'Cookies'};
        const subNames = {
            'all': `All ${categoryNames[currentCategory] || 'Products'}`,
            '5 inch':'5 inch Cake','cheese':'Cheese Flavour','chocolate':'Chocolate & Coffee',
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
            activeCategory.textContent = currentSubCategory !== 'all' 
                ? (subNames[currentSubCategory] || 'Products') 
                : (subNames['all'] || 'Products');
        }
    }

    function updateResultsInfo(total) {
        const showingStart = Math.min((currentPage - 1) * productsPerPage + 1, total);
        const showingEnd = Math.min(currentPage * productsPerPage, total);
        if (resultsInfo) resultsInfo.textContent = `Showing ${total === 0 ? 0 : showingStart}-${showingEnd} of ${total} products ${currentSearch ? ` for "${currentSearch}"` : ''}`;
        updateActiveCategory();
    }

    

    // menu.js 约第 262 行
function loadRecentlyViewed() {
    if (!recentlyViewedSection) return;
    
    // 如果没有最近浏览的数据，隐藏该区域；否则显示
    if (recentlyViewed.length === 0) {
        recentlyViewedSection.style.display = 'none';
        return;
    }
    
    recentlyViewedSection.style.display = 'block';
    recentProductsContainer.innerHTML = '';

    recentlyViewed.forEach(pid => {
        const p = products.find(x => x.id === pid);
        if (p) {
            // 只保留图片和名字，移除了价格
            recentProductsContainer.innerHTML += `
                <div class="recent-product-card" data-id="${p.id}">
                    <img src="${p.image}" alt="${p.name}" class="recent-product-image">
                    <h4 class="recent-product-name">${p.name}</h4>
                </div>`;
        }
    });

    // 绑定点击事件：点击最近浏览的产品，弹出对应的信息弹窗
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

    /**
     * 根据当前的 currentCategory 和 currentSubCategory 同步侧边栏视觉效果
     */
    function syncSidebarUI() {
        // 1. 清除所有旧状态
        document.querySelectorAll('.category-main').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.category-arrow').forEach(a => a.classList.remove('active'));
        document.querySelectorAll('.subcategories').forEach(s => s.classList.remove('active'));
        document.querySelectorAll('.subcategory-item').forEach(i => i.classList.remove('active'));

        // 2. 激活对应的父分类
        const targetMain = document.querySelector(`.category-main[data-category="${currentCategory}"]`);
        if (targetMain) {
            targetMain.classList.add('active');
            
            // 展开子菜单
            const arrow = targetMain.querySelector('.category-arrow');
            if (arrow) arrow.classList.add('active');
            
            const subContainer = targetMain.nextElementSibling;
            if (subContainer && subContainer.classList.contains('subcategories')) {
                subContainer.classList.add('active');
                
                // 3. 激活对应的子分类项
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