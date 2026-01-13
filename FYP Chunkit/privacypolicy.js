/**
 * privacypolicy.js
 */
document.addEventListener('DOMContentLoaded', () => {

    // 1. Hero 动画触发
    setTimeout(() => {
        document.getElementById('heroTitle')?.classList.add('fade-in');
        document.getElementById('heroSubtitle')?.classList.add('fade-in');
    }, 200);

    /**
     * 2. 滚动检测：当用户向下滚动时，内容板块逐个滑入
     */
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

    // 页面加载时执行一次，随后监听滚动
    handleScrollAnimation();
    window.addEventListener('scroll', handleScrollAnimation);
});