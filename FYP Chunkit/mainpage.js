/**
 * mainpage.js - Control homepage animations and data loading
 */

document.addEventListener('DOMContentLoaded', () => {

    /**
     * 1. Trigger Hero section animations
     * Delay execution to give users a bit of loading buffer time
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
     * 2. Core scroll detection logic:
     * When the user scrolls the page and a section enters 85% of the viewport height, add the .active class to trigger animations
     */
    function checkScroll() {
        const sections = document.querySelectorAll('.section');
        const triggerPoint = window.innerHeight * 0.85;

        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            
            if (sectionTop < triggerPoint) {
                // Activate major sectors
                section.classList.add('active');
                
                // Activate fade-in for all cards within this section
                const cards = section.querySelectorAll('.category-card, .product-card, .testimonial-card');
                cards.forEach(card => card.classList.add('fade-in'));

                // Handle special animations for the About Us section
                if (section.id === 'about') {
                    const img = section.querySelector('.about-image');
                    const txt = section.querySelector('.about-text');
                    if (img) img.classList.add('slide-in-left');
                    if (txt) txt.classList.add('slide-in-right');
                }

                // Handle fade-in for the CTA section
                if (section.id === 'cta') {
                    const cta = section.querySelector('.cta-content');
                    if (cta) cta.classList.add('fade-in');
                }
            }
        });
    }

    // Run a check immediately when the page loads to prevent the first screen content from not displaying
    checkScroll();
    // Listen for scroll events
    window.addEventListener('scroll', checkScroll);

    /**
     * 3. Cart button click logic
     */
    const cartBtn = document.getElementById('cartIcon');
    if (cartBtn) {
        cartBtn.addEventListener('click', () => {
            window.location.href = 'cart.php';
        });
    }

    // Note: loadFeaturedProducts() has been removed
    // The "Best Selling Products" section is now populated server-side in mainpage.php
});