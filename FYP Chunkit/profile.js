// profile.js - Profile页面核心功能

document.addEventListener('DOMContentLoaded', function() {
    initUserMenu();
});

/**
 * 初始化用户菜单
 */
function initUserMenu() {
    const userIcon = document.querySelector('.user-icon');
    const dropdownMenu = document.getElementById('dropdownMenu');
    
    if (userIcon && dropdownMenu) {
        // 点击用户图标切换菜单
        userIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown(dropdownMenu);
        });
        
        // 点击页面其他地方关闭菜单
        document.addEventListener('click', function(e) {
            if (!userIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.style.display = 'none';
                dropdownMenu.classList.remove('active');
            }
        });
        
        // 按ESC键关闭菜单
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && dropdownMenu.style.display === 'block') {
                dropdownMenu.style.display = 'none';
                dropdownMenu.classList.remove('active');
            }
        });
    }
}

/**
 * 切换下拉菜单显示/隐藏
 */
function toggleDropdown(dropdown) {
    if (!dropdown) return;
    
    if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
        dropdown.classList.remove('active');
    } else {
        dropdown.style.display = 'block';
        dropdown.classList.add('active');
    }
}