/**
 * contact_us.js - 修正后的版本
 */
document.addEventListener('DOMContentLoaded', () => {

    // 1. Hero 动画
    const title = document.getElementById('heroTitle');
    const sub = document.getElementById('heroSubtitle');
    setTimeout(() => {
        if (title) title.classList.add('fade-in');
        if (sub) sub.classList.add('fade-in');
    }, 200);

    // 2. 表单处理：移除所有阻止默认行为的操作
    const form = document.getElementById('contactForm');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = form.querySelector('.submit-btn');
            
            // 关键：不使用 e.preventDefault()
            // 仅使用一丁点延迟来更新文字，确保浏览器已经开始提交动作
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Baking Message...';
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none';
            }, 10);
        });
    }
});

/**
 * 关闭遮罩层
 */
function closeToast() {
    const toast = document.getElementById('toastOverlay');
    if (toast) {
        toast.style.transition = '0.3s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }
}