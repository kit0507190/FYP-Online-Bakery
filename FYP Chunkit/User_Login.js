document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    // 1. Retrieve Email from Cookie
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    const rememberedEmail = getCookie('user_email');
    

    if (rememberedEmail && emailInput.value === "") {
        emailInput.value = decodeURIComponent(rememberedEmail);
        const rememberMeCheckbox = document.getElementById('rememberMe');
        if (rememberMeCheckbox) rememberMeCheckbox.checked = true;
    }

    // 2. Password display toggle (remain unchanged)
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const isPass = passwordInput.type === 'password';
            passwordInput.type = isPass ? 'text' : 'password';
            this.textContent = isPass ? 'Hide' : 'Show';
        });
    }

    // 3. Automatically clear error patterns while typing (remain unchanged)
    [emailInput, passwordInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                this.style.borderColor = '#eee';
            });
        }
    });
});