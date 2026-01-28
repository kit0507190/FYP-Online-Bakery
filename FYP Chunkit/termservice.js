
document.addEventListener('DOMContentLoaded', () => {

    // 1. Hero Fade-in Animation
    setTimeout(() => {
        document.getElementById('heroTitle')?.classList.add('fade-in');
        document.getElementById('heroSubtitle')?.classList.add('fade-in');
    }, 200);

    // 2. Scroll detection: Allow the terms and conditions section to slide in smoothly.
    function revealSections() {
        const sections = document.querySelectorAll('.terms-section');
        const triggerPoint = window.innerHeight * 0.85;

        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            if (sectionTop < triggerPoint) {
                section.classList.add('active');
            }
        });
    }

    revealSections();
    window.addEventListener('scroll', revealSections);
});