/**
 * profile.js - 个人资料页交互逻辑
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. 原有按钮交互
    const actionButtons = document.querySelectorAll('.btn');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            // 悬停逻辑
        });
    });

    // 2. 成功提示 Toast 自动处理
    const toast = document.querySelector('.toast');
    if (toast) {
        // 4秒后自动开始消失动画
        setTimeout(() => {
            toast.classList.add('hiding');
            // 动画结束后移除元素
            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 4000);

        // 3. 核心功能：清理 URL 中的 ?success=1 参数
        // 这样用户按 F5 刷新页面时，提示框不会再次出现
        if (window.history.replaceState) {
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
        }
    }

    // 购物车跳转
    const cartIcon = document.getElementById('cartIcon');
    if (cartIcon) {
        cartIcon.addEventListener('click', function() {
            window.location.href = 'cart.php';
        });
    }
});