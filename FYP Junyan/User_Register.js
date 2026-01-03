document.addEventListener('DOMContentLoaded', function () {
    // 1. 密码显示切换
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

    // 2. Email 实时检查逻辑 (非 alert)
    const emailInput = document.getElementById('emailInput');
    const emailError = document.getElementById('emailError');

    emailInput.addEventListener('blur', function() {
        const val = this.value.trim().toLowerCase();
        
        if (val === "") {
            clearError();
        } else if (!val.endsWith('@gmail.com')) {
            emailError.textContent = "* Please use a valid @gmail.com address.";
            this.classList.add('input-error');
        } else {
            clearError();
        }
    });

    emailInput.addEventListener('input', clearError);

    function clearError() {
        emailError.textContent = "";
        emailInput.classList.remove('input-error');
    }
});