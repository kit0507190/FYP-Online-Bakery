/**
 * User_Register.js - 整合实时验证逻辑
 */
document.addEventListener('DOMContentLoaded', function () {
    // 1. 密码显示切换 (保持原有逻辑不动)
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

    // 2. 元素定义
    const nameInput = document.getElementById('nameInput');
    const nameError = document.getElementById('nameError');
    const emailInput = document.getElementById('emailInput');
    const emailError = document.getElementById('emailError');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirmPassword');
    const confirmError = document.getElementById('confirmError');

    // 3. Full Name 实时检查逻辑 (允许输入但实时显示截图中的报错效果)
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            const val = this.value;
            // 匹配字母和空格的正则表达式
            const namePattern = /^[a-zA-Z\s]*$/;
            
            if (val === "") {
                clearNameError();
            } else if (!namePattern.test(val)) {
                // 对应截图：显示红字警告并将边框变红
                nameError.textContent = "* Name can only contain letters and spaces.";
                this.classList.add('input-error');
            } else if (val.trim().length < 2) {
                nameError.textContent = "* Name must be at least 2 characters.";
                this.classList.add('input-error');
            } else {
                clearNameError();
            }
        });
    }

    function clearNameError() {
        if (nameError) nameError.textContent = "";
        if (nameInput) nameInput.classList.remove('input-error');
    }

    // 4. Email 实时检查逻辑 (保持原有逻辑)
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            const val = this.value.trim().toLowerCase();
            if (val === "") {
                clearEmailError();
            } else if (!val.endsWith('@gmail.com')) {
                emailError.textContent = "* Please use a valid @gmail.com address.";
                this.classList.add('input-error');
            } else {
                clearEmailError();
            }
        });
    }

    function clearEmailError() {
        if (emailError) emailError.textContent = "";
        if (emailInput) emailInput.classList.remove('input-error');
    }

    // 5. Confirm Password 实时对比逻辑 (实时显示匹配状态)
    const validatePasswords = () => {
        const p1 = passwordInput.value;
        const p2 = confirmInput.value;

        if (p2.length > 0) {
            if (p1 !== p2) {
                confirmError.textContent = "* Passwords do not match.";
                confirmInput.classList.add('input-error');
            } else {
                confirmError.textContent = "";
                confirmInput.classList.remove('input-error');
            }
        } else {
            confirmError.textContent = "";
            confirmInput.classList.remove('input-error');
        }
    };

    if (passwordInput && confirmInput) {
        passwordInput.addEventListener('input', validatePasswords);
        confirmInput.addEventListener('input', validatePasswords);
    }

    // 6. Modal 弹窗逻辑 (保持原有代码不动)
    const modal = document.getElementById('policyModal');
    const title = document.getElementById('policyTitle');
    const body = document.getElementById('policyBody');

    const contents = {
        terms: `
            <h4>1. Acceptance</h4>
            <p>By creating an account at Bakery House, you agree to comply with our community standards and respect our staff.</p>
            <h4>2. Account</h4>
            <p>You are responsible for maintaining the confidentiality of your account password.</p>
            <h4>3. Orders</h4>
            <p>Orders made through this platform are subject to product availability.</p>
        `,
        privacy: `
            <h4>1. Data Collection</h4>
            <p>We only collect your name and email to manage your account and process your orders.</p>
            <h4>2. Security</h4>
            <p>We implement security measures to protect your personal data from unauthorized access.</p>
        `
    };

    if (document.getElementById('termsLink')) {
        document.getElementById('termsLink').onclick = (e) => {
            e.preventDefault();
            title.textContent = "Terms of Service";
            body.innerHTML = contents.terms;
            modal.style.display = 'block';
        };
    }

    if (document.getElementById('privacyLink')) {
        document.getElementById('privacyLink').onclick = (e) => {
            e.preventDefault();
            title.textContent = "Privacy Policy";
            body.innerHTML = contents.privacy;
            modal.style.display = 'block';
        };
    }

    const closeModal = () => { if (modal) modal.style.display = 'none'; };
    if (document.querySelector('.close-modal')) document.querySelector('.close-modal').onclick = closeModal;
    if (document.getElementById('modalCloseBtn')) document.getElementById('modalCloseBtn').onclick = closeModal;
    window.onclick = (e) => { if (e.target === modal) closeModal(); };
});