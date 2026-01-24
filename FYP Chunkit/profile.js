/**
 * profile.js - 处理页面交互
 */

function closeToast() {
    const overlay = document.getElementById('toastOverlay');
    
    if (overlay) {
        // 1. 立即禁用点击拦截，让底下的按钮可以被点击
        overlay.style.pointerEvents = 'none';
        
        // 2. 执行动画
        overlay.style.transition = '0.3s';
        overlay.style.opacity = '0';
        
        // 3. 动画结束后移除并清理 URL
        setTimeout(() => {
            overlay.remove();
            
            if (window.history.replaceState) {
                const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
            }
        }, 300);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('toastOverlay');
    
    // 如果页面加载时有 success 参数，我们先在后台清理掉它
    // 这样即便用户不点 Done 直接刷新，弹窗也不会再出现
    if (window.location.search.includes('success=1')) {
        if (window.history.replaceState) {
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
        }
    } else if (overlay) {
        // 如果 URL 没有参数但遮罩还在（缓存原因），强制移除
        overlay.remove();
    }
});