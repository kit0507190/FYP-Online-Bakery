/**
 * mainpage.js - 控制主页动画和数据加载
 */

document.addEventListener('DOMContentLoaded', () => {

    /**
     * 1. 触发 Hero 板块动画
     * 延迟执行，给用户一点加载缓冲的时间
     */
    setTimeout(() => {
        const title = document.getElementById('heroTitle');
        const sub = document.getElementById('heroSubtitle');
        const btn = document.getElementById('heroBtn');
        
        if (title) title.classList.add('fade-in');
        if (sub) sub.classList.add('fade-in');
        if (btn) btn.classList.add('fade-in');
    }, 300);

    /**
     * 2. 加载精选产品 (Featured Products)
     */
    loadFeaturedProducts();

    /**
     * 3. 滚动检测核心逻辑：
     * 当用户滚动页面，板块进入屏幕视野 85% 位置时，添加 .active 类触发动画
     */
    function checkScroll() {
        const sections = document.querySelectorAll('.section');
        const triggerPoint = window.innerHeight * 0.85;

        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            
            if (sectionTop < triggerPoint) {
                // 激活大板块
                section.classList.add('active');
                
                // 激活该板块内所有卡片的渐显
                const cards = section.querySelectorAll('.category-card, .product-card, .testimonial-card');
                cards.forEach(card => card.classList.add('fade-in'));

                // 处理关于我们的特有动画
                if (section.id === 'about') {
                    const img = section.querySelector('.about-image');
                    const txt = section.querySelector('.about-text');
                    if (img) img.classList.add('slide-in-left');
                    if (txt) txt.classList.add('slide-in-right');
                }

                // 处理 CTA 动作区的渐显
                if (section.id === 'cta') {
                    const cta = section.querySelector('.cta-content');
                    if (cta) cta.classList.add('fade-in');
                }
            }
        });
    }

    // 页面加载时立即运行一次检查，防止首屏内容无法显示
    checkScroll();
    // 监听滚动事件
    window.addEventListener('scroll', checkScroll);

    /**
     * 4. 购物车按钮点击逻辑
     */
    const cartBtn = document.getElementById('cartIcon');
    if (cartBtn) {
        cartBtn.addEventListener('click', () => {
            window.location.href = 'cart.php';
        });
    }
});

/**
 * 加载产品数据引擎
 */
function loadFeaturedProducts() {
    // 虚拟产品数据库
    const products = [
        { id: 1, name: "DOUBLE OREO TEMPTATION", price: 128.00, image: "cake/Festival/Red Velvet 3D Christmas Tree Cake.jpg" },
        { id: 2, name: "Cherry Cream Cheese Danish", price: 13.20, image: "pastries/Danish Pastries/Cherry Cream Cheese Danish.jpg" },
        { id: 3, name: "Crusty Artisan Bread", price: 12.50, image: "bread/Artisan Bread/Crusty Artisan Bread.webp" },
        { id: 4, name: "Classic Lemon Glazed Loaf", price: 13.50, image: "bread/Sweet Bread/Starbucks Lemon Loaf.jpg" }
    ];

    const container = document.getElementById('featuredProducts');
    if (!container) return;

    // 动态拼接 HTML 字符串并注入容器
    container.innerHTML = products.map(p => `
        <div class="product-card" onclick="sessionStorage.setItem('currentProduct', ${p.id}); window.location.href='product-details.html'">
            <img src="${p.image}" alt="${p.name}" class="product-image">
            <div class="product-info">
                <h3 class="product-name">${p.name}</h3>
                <p class="product-price">RM ${p.price.toFixed(2)}</p>
            </div>
        </div>
    `).join('');
}