document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('emailInput');
    const emailError = document.getElementById('emailError');

    // 1. 密码切换显示逻辑
    const setupToggle = (btnId, inputId) => {
        const btn = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        if (btn && input) {
            btn.addEventListener('click', () => {
                const isPass = input.type === 'password';
                input.type = isPass ? 'text' : 'password';
                btn.textContent = isPass ? 'Hide' : 'Show';
            });
        }
    };
    setupToggle('togglePassword', 'password');
    setupToggle('toggleConfirmPassword', 'confirmPassword');

    // 2. Email 实时验证逻辑 (离开焦点时触发)
    emailInput.addEventListener('blur', function() {
        const value = this.value.trim().toLowerCase();
        
        if (value === "") {
            clearEmailError();
        } else if (!value.endsWith('@gmail.com')) {
            // 使用红字提示代替 alert
            emailError.textContent = "* Please use a valid @gmail.com address.";
            this.classList.add('input-error');
        } else {
            clearEmailError();
        }
    });

    // 3. 用户重新输入时，自动移除红色警告
    emailInput.addEventListener('input', clearEmailError);

    function clearEmailError() {
        emailError.textContent = "";
        emailInput.classList.remove('input-error');
    }
});