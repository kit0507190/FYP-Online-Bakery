/**
 * about_us.js - 处理关于我们页面的动画与交互
 */
document.addEventListener('DOMContentLoaded', () => {
    
    const heroTitle = document.getElementById('heroTitle');
    const heroSubtitle = document.getElementById('heroSubtitle');
    const heroBtn = document.getElementById('heroBtn');

    /**
     * 1. 触发 Hero 板块动画
     */
    setTimeout(() => {
        if (heroTitle) heroTitle.classList.add('fade-in');
        if (heroSubtitle) heroSubtitle.classList.add('fade-in');
        if (heroBtn) heroBtn.classList.add('fade-in');
    }, 300);

    /**
     * 2. 滚动检测核心逻辑
     */
    function checkScroll() {
        const sections = document.querySelectorAll('.section');
        const triggerPoint = window.innerHeight * 0.8;
        
        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            
            if (sectionTop < triggerPoint) {
                // 激活大板块
                section.classList.add('active');
                
                // 激活内部元素动画
                const cards = section.querySelectorAll('.value-card, .team-member, .testimonial-card');
                cards.forEach(card => card.classList.add('fade-in'));
                
                // 处理 Story 部分特有的滑入
                if (section.id === 'about') {
                    const img = section.querySelector('.about-image');
                    const txt = section.querySelector('.about-text');
                    if (img) img.classList.add('slide-in-left');
                    if (txt) txt.classList.add('slide-in-right');
                }
                
                // 处理 CTA 动作区
                if (section.id === 'cta') {
                    const cta = section.querySelector('.cta-content');
                    if (cta) cta.classList.add('fade-in');
                }
            }
        });
    }

    // 初始化检查与滚动监听
    checkScroll();
    window.addEventListener('scroll', checkScroll);

    /**
     * 3. 购物车点击逻辑
     */
    const cartIcon = document.getElementById('cartIcon');
    if (cartIcon) {
        cartIcon.addEventListener('click', () => {
            window.location.href = 'cart.html';
        });
    }
});