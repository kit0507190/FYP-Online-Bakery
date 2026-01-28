document.addEventListener('DOMContentLoaded', () => {
    
    const heroTitle = document.getElementById('heroTitle');
    const heroSubtitle = document.getElementById('heroSubtitle');
    const heroBtn = document.getElementById('heroBtn');


    setTimeout(() => {
        if (heroTitle) heroTitle.classList.add('fade-in');
        if (heroSubtitle) heroSubtitle.classList.add('fade-in');
        if (heroBtn) heroBtn.classList.add('fade-in');
    }, 300);


    function checkScroll() {
        const sections = document.querySelectorAll('.section');
        const triggerPoint = window.innerHeight * 0.8;
        
        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            
            if (sectionTop < triggerPoint) {
              
                section.classList.add('active');
                
                
                const cards = section.querySelectorAll('.value-card, .team-member, .testimonial-card');
                cards.forEach(card => card.classList.add('fade-in'));
                
                
                if (section.id === 'about') {
                    const img = section.querySelector('.about-image');
                    const txt = section.querySelector('.about-text');
                    if (img) img.classList.add('slide-in-left');
                    if (txt) txt.classList.add('slide-in-right');
                }
                
                
                if (section.id === 'cta') {
                    const cta = section.querySelector('.cta-content');
                    if (cta) cta.classList.add('fade-in');
                }
            }
        });
    }

  
    checkScroll();
    window.addEventListener('scroll', checkScroll);

    const cartIcon = document.getElementById('cartIcon');
    if (cartIcon) {
        cartIcon.addEventListener('click', () => {
            window.location.href = 'cart.html';
        });
    }
});