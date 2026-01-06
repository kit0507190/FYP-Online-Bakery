// 用户状态管理
let currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;

// 更新用户状态
function updateUserStatus(user = null) {
    currentUser = user;
    if (user) {
        localStorage.setItem('currentUser', JSON.stringify(user));
    } else {
        localStorage.removeItem('currentUser');
    }
    updateHeader();
}

// 检查登录状态
function checkLoginStatus() {
    return currentUser !== null;
}

// 更新header显示
function updateHeader() {
    const headerElement = document.getElementById('mainHeader');
    if (!headerElement) return;
    
    const isLoggedIn = checkLoginStatus();
    
    const headerHTML = `
        <header>
            <div class="container">
                <nav class="navbar">
                    <a href="index.html" class="logo">
                        <img src="Bakery House Logo.png" alt="BakeryHouse">
                    </a>
                    <ul class="nav-links">
                        <li><a href="index.html" ${window.location.pathname.includes('index') ? 'class="active"' : ''}>Home</a></li>
                        <li><a href="menu.html" ${window.location.pathname.includes('menu') ? 'class="active"' : ''}>Menu</a></li>
                        <li><a href="about_us.html" ${window.location.pathname.includes('about') ? 'class="active"' : ''}>About</a></li>
                        <li><a href="contact.html" ${window.location.pathname.includes('contact') ? 'class="active"' : ''}>Contact</a></li>
                        <li class="cart-icon" id="cartIcon">
                            <span>🛒 Cart</span>
                            <span class="cart-count">0</span>
                        </li>
                        ${isLoggedIn ? 
                            `<li class="user-profile">
                                <div class="user-avatar">
                                    <img src="${currentUser.avatar || 'default-avatar.png'}" alt="${currentUser.name}">
                                    <span>${currentUser.name}</span>
                                </div>
                                <div class="dropdown-menu">
                                    <a href="profile.html">My Profile</a>
                                    <a href="order-history.html">Order History</a>
                                    <a href="#" id="logoutBtn">Logout</a>
                                </div>
                            </li>` :
                            `<li>
                                <a href="User_Login.php" class="signup-btn">Sign Up</a>
                            </li>`
                        }
                    </ul>
                </nav>
            </div>
        </header>
    `;
    
    headerElement.innerHTML = headerHTML;
    
    // 添加事件监听器
    setupHeaderEvents();
}

// 设置header事件
function setupHeaderEvents() {
    // 购物车点击
    const cartIcon = document.getElementById('cartIcon');
    if (cartIcon) {
        cartIcon.addEventListener('click', () => {
            window.location.href = 'cart.html';
        });
    }
    
    // 登出按钮
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            updateUserStatus(null);
            window.location.href = 'index.html';
        });
    }
    
    // 用户头像下拉菜单
    const userProfile = document.querySelector('.user-profile');
    if (userProfile) {
        userProfile.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown-menu')) {
                userProfile.classList.toggle('active');
            }
        });
        
        // 点击外部关闭下拉菜单
        document.addEventListener('click', (e) => {
            if (!userProfile.contains(e.target)) {
                userProfile.classList.remove('active');
            }
        });
    }
    
    // 更新购物车数量
    updateCartCount();
}

// 更新购物车数量
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('bakeryCart')) || [];
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    }
}

// 初始化
document.addEventListener('DOMContentLoaded', () => {
    // 如果页面有header容器，更新header
    if (document.getElementById('mainHeader')) {
        updateHeader();
    }
    
    // 监听购物车变化
    window.addEventListener('storage', (e) => {
        if (e.key === 'bakeryCart') {
            updateCartCount();
        }
    });
});

// 导出函数供其他页面使用
window.headerManager = {
    updateUserStatus,
    checkLoginStatus,
    updateCartCount
};