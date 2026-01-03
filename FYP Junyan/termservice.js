/**
 * termservice.js
 */
document.addEventListener('DOMContentLoaded', () => {

    // 1. Hero 渐入动画
    setTimeout(() => {
        document.getElementById('heroTitle')?.classList.add('fade-in');
        document.getElementById('heroSubtitle')?.classList.add('fade-in');
    }, 200);

    // 2. 滚动检测：让条款板块平滑滑入
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