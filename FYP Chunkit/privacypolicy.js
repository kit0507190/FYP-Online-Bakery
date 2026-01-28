
document.addEventListener('DOMContentLoaded', () => {

    // 1. Hero animation triggered
    setTimeout(() => {
        document.getElementById('heroTitle')?.classList.add('fade-in');
        document.getElementById('heroSubtitle')?.classList.add('fade-in');
    }, 200);

    // * 2. Scroll detection: As the user scrolls down, content sections slide in one by one.
    function handleScrollAnimation() {
        const sections = document.querySelectorAll('.privacy-section');
        const triggerPoint = window.innerHeight * 0.85;

        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            if (sectionTop < triggerPoint) {
                section.classList.add('active');
            }
        });
    }

    
    handleScrollAnimation();
    window.addEventListener('scroll', handleScrollAnimation);
});