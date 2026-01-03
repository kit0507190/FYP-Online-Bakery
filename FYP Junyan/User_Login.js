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
    if (rememberedEmail) {
        emailInput.value = decodeURIComponent(rememberedEmail);
        document.getElementById('rememberMe').checked = true;
    }

    // 2. 密码显示切换
    togglePassword.addEventListener('click', function() {
        const isPass = passwordInput.type === 'password';
        passwordInput.type = isPass ? 'text' : 'password';
        this.textContent = isPass ? 'Hide' : 'Show';
    });

    // 3. 输入时自动清除错误样式
    [emailInput, passwordInput].forEach(input => {
        input.addEventListener('input', function() {
            this.style.borderColor = '#eee';
        });
    });
});