/**
 * profile.js - 处理用户信息下拉菜单
 */
document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.querySelector('.user-icon');
    const dropdownMenu = document.getElementById('dropdownMenu');
    
    // 检查页面上是否存在用户菜单（即用户是否已登录）
    if (userIcon && dropdownMenu) {
        
        // 1. 点击图标：切换菜单显示或隐藏
        // 这里的 toggleDropdown 函数对应 HTML 中的 onclick="toggleDropdown()"
        window.toggleDropdown = function() {
            dropdownMenu.classList.toggle('show');
        };

        // 2. 点击页面空白处：自动关闭菜单
        window.addEventListener('click', function(event) {
            // 如果点击的目标【不包含】在用户图标内，且【不包含】在菜单内
            if (!userIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
                // 移除 show 类以隐藏菜单
                dropdownMenu.classList.remove('show');
            }
        });
    }
});