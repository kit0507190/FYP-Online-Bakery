document.addEventListener('DOMContentLoaded', function () {

    // Cart, favorites, recently viewed
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

    // State
    let currentCategory = 'all';
    let currentSubCategory = 'all';
    let currentSearch = '';
    let currentSort = 'name';
    let currentPage = 1;
    const productsPerPage = 9;

    // Initialize
    function initPage() {
        renderProducts();
        updateCartCount();
        loadRecentlyViewed();
        setupEventListeners();
        setupProductEventListeners();
    }

    // Render products
    function renderProducts() {
        productsGrid.innerHTML = '';
        loadingSpinner.style.display = 'block';

        setTimeout(() => {
            let filteredProducts = filterProducts();
            filteredProducts = sortProducts(filteredProducts);

            const total = filteredProducts.length;
            const maxPage = Math.max(1, Math.ceil(total / productsPerPage));
            currentPage = Math.min(currentPage, maxPage);

            const start = (currentPage - 1) * productsPerPage;
            const end = start + productsPerPage;
            const paginated = filteredProducts.slice(start, end);

            updateActiveCategoryDisplay(total, start, end);

            if (paginated.length === 0) {
                productsGrid.innerHTML = '<p class="no-products">No products match your filters.</p>';
            } else {
                paginated.forEach(product => {
                    productsGrid.appendChild(createProductCard(product));
                });
            }

            pageIndicator.textContent = `Page ${currentPage} of ${maxPage}`;
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage === maxPage;

            loadingSpinner.style.display = 'none';
            setupProductEventListeners(); // Important: re-attach listeners after new cards are created
        }, 400);
    }

    function filterProducts() {
        return products.filter(product => {
            const matchCategory = currentCategory === 'all' || product.category === currentCategory;
            const matchSub = currentSubCategory === 'all' || 
                            (product.subcategory && product.subcategory === currentSubCategory);
            const matchSearch = !currentSearch || 
                product.name.toLowerCase().includes(currentSearch.toLowerCase()) ||
                (product.description && product.description.toLowerCase().includes(currentSearch.toLowerCase()));
            return matchCategory && matchSub && matchSearch;
        });
    }

    function sortProducts(list) {
        return [...list].sort((a, b) => {
            switch (currentSort) {
                case 'name': return a.name.localeCompare(b.name);
                case 'price-low': return a.price - b.price;
                case 'price-high': return b.price - a.price;
                default: return 0;
            }
        });
    }

    function createProductCard(product) {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.dataset.id = product.id;

        const isFavorite = favorites.includes(product.id);
        const badge = (product.tags && product.tags.includes('popular')) ? 'popular' :
                      (product.tags && product.tags.includes('new')) ? 'new' : '';

        const ratingFloor = Math.floor(product.rating || 0);
        const stars = '★'.repeat(ratingFloor) + '☆'.repeat(5 - ratingFloor);
        const desc = product.description || '';

        card.innerHTML = `
            ${badge ? `<div class="product-badge ${badge}">${badge === 'popular' ? 'Popular' : 'New'}</div>` : ''}
            <button class="favorite-btn ${isFavorite ? 'active' : ''}" data-id="${product.id}">
                ${isFavorite ? '❤️' : '🤍'}
            </button>
            <img src="${product.image}" alt="${product.name}" class="product-image">
            <div class="product-info">
                <h3 class="product-name">${product.name}</h3>
                <p class="product-price">RM ${product.price.toFixed(2)}</p>
                <p class="product-size">${product.size || ''}</p>
                <div class="product-rating">
                    <span class="stars">${stars}</span>
                    <span>${product.rating || ''}</span>
                    <span class="rating-count">(${product.reviewCount || 0})</span>
                </div>
                <p class="product-description">${desc.substring(0, 80)}${desc.length > 80 ? '...' : ''}</p>
            </div>
        `;

        return card;
    }

    function quickViewProduct(id) {
        const product = products.find(p => p.id === id);
        if (!product) {
            showToast("Product not found");
            return;
        }

        addToRecentlyViewed(id);

        const isFavorite = favorites.includes(product.id);
        const ratingFloor = Math.floor(product.rating || 0);
        const stars = '★'.repeat(ratingFloor) + '☆'.repeat(5 - ratingFloor);

        quickViewContent.innerHTML = `
            <button class="close-modal" id="closeModal">×</button>
            <div style="display: flex; gap: 30px; padding: 30px;">
                <div style="flex: 1;">
                    <img src="${product.image}" alt="${product.name}" style="width: 100%; border-radius: 10px;">
                </div>
                <div style="flex: 1;">
                    <h2 style="margin-bottom: 15px; color: #5a3921;">${product.name}</h2>
                    <p style="font-size: 24px; color: #d4a76a; font-weight: bold; margin-bottom: 15px;">RM ${product.price.toFixed(2)}</p>
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <span class="stars">${stars}</span>
                        <span style="margin-left: 10px;">${product.rating || ''} (${product.reviewCount || 0} reviews)</span>
                    </div>
                    <p style="margin-bottom: 20px; line-height: 1.6;">${product.fullDescription || product.description || ''}</p>
                    <div style="margin-bottom: 20px;"><strong>Ingredients:</strong> ${product.ingredients || 'N/A'}</div>
                    <div style="margin-bottom: 20px;"><strong>Size:</strong> ${product.weight || 'N/A'}</div>
                    <div style="margin-bottom: 20px;"><strong>Allergens:</strong> ${product.allergens || 'N/A'}</div>
                    <div style="display: flex; gap: 10px; margin-top: 25px;">
                        <button class="add-to-cart-btn" data-id="${product.id}" 
                                style="background: #d4a76a; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; flex: 2;">
                            Add to Cart
                        </button>
                        <button class="favorite-btn modal-favorite ${isFavorite ? 'active' : ''}" data-id="${product.id}"
                                style="background: #f5f5f5; border: 1px solid #ddd; padding: 12px; border-radius: 5px; cursor: pointer;">
                            ${isFavorite ? '❤️' : '🤍'}
                        </button>
                    </div>
                </div>
            </div>
        `;

        quickViewModal.style.display = 'flex';

        // Attach modal event listeners
        document.getElementById('closeModal')?.addEventListener('click', closeQuickView);
        quickViewModal.addEventListener('click', (e) => {
            if (e.target === quickViewModal) closeQuickView();
        });

        quickViewContent.querySelector('.add-to-cart-btn')?.addEventListener('click', () => {
            addToCart(product.id, 1);
            closeQuickView();
        });

        quickViewContent.querySelector('.favorite-btn')?.addEventListener('click', () => {
            toggleFavorite(product.id);
            const btn = quickViewContent.querySelector('.favorite-btn');
            btn.innerHTML = favorites.includes(product.id) ? '❤️' : '🤍';
            btn.classList.toggle('active');
        });
    }

    function closeQuickView() {
        quickViewModal.style.display = 'none';
    }

    function setupEventListeners() {
        // Category toggles
        document.querySelectorAll('.category-main').forEach(el => {
            el.addEventListener('click', function() {
                document.querySelectorAll('.category-main').forEach(e => e.classList.remove('active'));
                document.querySelectorAll('.category-arrow').forEach(e => e.classList.remove('active'));
                document.querySelectorAll('.subcategories').forEach(e => e.classList.remove('active'));

                this.classList.add('active');
                this.querySelector('.category-arrow')?.classList.add('active');
                this.nextElementSibling?.classList.add('active');

                currentCategory = this.dataset.category;
                currentSubCategory = 'all';
                currentPage = 1;

                document.querySelectorAll('.subcategory-item').forEach(e => e.classList.remove('active'));
                this.nextElementSibling?.querySelector('[data-subcategory="all"]')?.classList.add('active');

                renderProducts();
            });
        });

        // Subcategory selection
        document.querySelectorAll('.subcategory-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.subcategory-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                currentSubCategory = this.dataset.subcategory;
                currentPage = 1;
                renderProducts();
            });
        });

        // Search
        searchBtn.addEventListener('click', () => {
            currentSearch = searchInput.value.trim();
            currentPage = 1;
            renderProducts();
        });
        searchInput.addEventListener('keypress', e => {
            if (e.key === 'Enter') {
                currentSearch = searchInput.value.trim();
                currentPage = 1;
                renderProducts();
            }
        });

        // Sort
        sortSelect.addEventListener('change', () => {
            currentSort = sortSelect.value;
            currentPage = 1;
            renderProducts();
        });

        // Pagination
        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderProducts();
            }
        });
        nextPageBtn.addEventListener('click', () => {
            const total = filterProducts().length;
            const maxPage = Math.ceil(total / productsPerPage);
            if (currentPage < maxPage) {
                currentPage++;
                renderProducts();
            }
        });

        // Back to top
        window.addEventListener('scroll', () => {
            backToTop.style.display = window.scrollY > 300 ? 'block' : 'none';
        });
        backToTop.addEventListener('click', () => window.scrollTo({top: 0, behavior: 'smooth'}));
    }

    function setupProductEventListeners() {
        // Favorite buttons
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent opening quick view
                const id = parseInt(btn.dataset.id);
                toggleFavorite(id);
                btn.innerHTML = favorites.includes(id) ? '❤️' : '🤍';
                btn.classList.toggle('active');
            });
        });

        // Click on product card → quick view (except favorite button)
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', (e) => {
                if (e.target.closest('.favorite-btn')) return; // skip if heart was clicked
                const id = parseInt(card.dataset.id);
                quickViewProduct(id);
            });
        });
    }

    function updateCartCount() {
        const total = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        localStorage.setItem('cartItemCount', total.toString());
        if (cartCount) {
            cartCount.textContent = total;
            cartCount.style.display = total > 0 ? 'flex' : 'none';
        }
    }

    function addToRecentlyViewed(productId) {
        recentlyViewed = recentlyViewed.filter(id => id !== productId);
        recentlyViewed.unshift(productId);
        recentlyViewed = recentlyViewed.slice(0, 10);
        localStorage.setItem('bakeryRecentlyViewed', JSON.stringify(recentlyViewed));
        loadRecentlyViewed();
    }

    function loadRecentlyViewed() {
        if (!recentlyViewed.length) {
            recentlyViewedSection.style.display = 'none';
            return;
        }
        recentProductsContainer.innerHTML = '';
        recentlyViewed.forEach(id => {
            const p = products.find(prod => prod.id === id);
            if (p) recentProductsContainer.appendChild(createProductCard(p));
        });
        recentlyViewedSection.style.display = 'block';

        setupProductEventListeners();
    }

    function toggleFavorite(productId) {
        if (favorites.includes(productId)) {
            favorites = favorites.filter(id => id !== productId);
            showToast('Removed from favorites');
        } else {
            favorites.push(productId);
            showToast('Added to favorites!');
        }
        localStorage.setItem('bakeryFavorites', JSON.stringify(favorites));
    }

    function addToCart(productId, quantity = 1) {
        if (window.isLoggedIn !== true) {
            showLoginPrompt();
            return;
        }

        const product = products.find(p => p.id == productId);
        if (!product) return;

        const existingIndex = cart.findIndex(i => i.id == productId);
        let finalQuantity = quantity;

        if (existingIndex > -1) {
            finalQuantity += cart[existingIndex].quantity;
            cart.splice(existingIndex, 1);
        }

        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            quantity: finalQuantity
        });

        localStorage.setItem('bakeryCart', JSON.stringify(cart));
        updateCartCount();
        showToast(`${product.name} added to cart!`);

        if (typeof syncCartToDB === 'function') syncCartToDB();
    }

    function showToast(message) {
        if (toast) {
            toast.textContent = message;
            toast.style.display = 'block';
            setTimeout(() => toast.style.display = 'none', 2500);
        }
    }

    function updateActiveCategoryDisplay(total, start, end) {
        let title = currentCategory === 'all' 
            ? 'All Products' 
            : currentCategory.charAt(0).toUpperCase() + currentCategory.slice(1) + 's';
        
        activeCategory.textContent = currentSubCategory === 'all' ? title : currentSubCategory;
        resultsInfo.textContent = total > 0 
            ? `Showing ${start + 1}-${Math.min(end, total)} of ${total}`
            : 'No products found';
    }

    // Start the page
    initPage();
});

// Login prompt functions (keep these outside if they're used globally)
function showLoginPrompt() {
    const modal = document.getElementById('loginPromptModal');
    if (modal) modal.style.display = 'flex';
}

function closeLoginPrompt() {
    const modal = document.getElementById('loginPromptModal');
    if (modal) modal.style.display = 'none';
}