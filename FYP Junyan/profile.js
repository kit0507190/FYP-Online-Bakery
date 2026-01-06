/**
 * profile.js - 处理页面交互
 */

/**
 * 功能：关闭成功提示弹窗
 */
function closeToast() {
    // 1. 找到弹窗遮罩层元素
    const overlay = document.getElementById('toastOverlay');
    
    if (overlay) {
        // 2. 添加淡出动画效果
        overlay.style.transition = '0.3s';
        overlay.style.opacity = '0';
        
        // 3. 等动画结束后从页面上彻底移除
        setTimeout(() => {
            overlay.remove();
            
            // 4. 重要：清理地址栏的 ?success=1 参数
            // 这样用户按 F5 刷新页面时，弹窗不会再次蹦出来
            if (window.history.replaceState) {
                // 获取当前纯净的 URL（不带参数）
                const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                // 在不刷新页面的情况下修改地址栏
                window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
            }
        }, 300);
    }
}

// 页面加载后的其他逻辑可以写在这里
document.addEventListener('DOMContentLoaded', function() {
    console.log("Profile page loaded.");
});