document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    // 1. 自动填入记住的 Email (从 Cookie 获取)
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    const rememberedEmail = getCookie('user_email');
    if (rememberedEmail) {
        emailInput.value = decodeURIComponent(rememberedEmail);
        document.getElementById('rememberMe').checked = true;
    }

    // 2. 密码显示/隐藏切换
    togglePassword.addEventListener('click', function() {
        const isPass = passwordInput.type === 'password';
        passwordInput.type = isPass ? 'text' : 'password';
        this.textContent = isPass ? 'Hide' : 'Show';
    });

    // 3. 注册成功后的提示 (如果有)
    if (typeof isRegistered !== 'undefined' && isRegistered) {
        // 这里可以保留一个轻量级的成功提示，或者不写
        console.log("Registered successfully.");
    }

    // 4. 输入时自动清除红色错误状态
    [emailInput, passwordInput].forEach(input => {
        input.addEventListener('input', function() {
            this.style.borderColor = '#eee';
        });
    });
});