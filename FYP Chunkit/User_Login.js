/**
 * User_Login.js - 修复数据刷新与缓存问题
 */
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    // 1. 获取 Cookie 中的 Email
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    const rememberedEmail = getCookie('user_email');
    
    // --- 核心修复逻辑 ---
    // 只有当输入框本身是空的（说明不是提交失败返回的），才去读取 Cookie
    if (rememberedEmail && emailInput.value === "") {
        emailInput.value = decodeURIComponent(rememberedEmail);
        const rememberMeCheckbox = document.getElementById('rememberMe');
        if (rememberMeCheckbox) rememberMeCheckbox.checked = true;
    }

    // 2. 密码显示切换 (保持不变)
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const isPass = passwordInput.type === 'password';
            passwordInput.type = isPass ? 'text' : 'password';
            this.textContent = isPass ? 'Hide' : 'Show';
        });
    }

    // 3. 输入时自动清除错误样式 (保持不变)
    [emailInput, passwordInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                this.style.borderColor = '#eee';
            });
        }
    });
});