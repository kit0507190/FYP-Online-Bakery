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

    // 2. Name 实时检查逻辑 (新增加)
    const nameInput = document.getElementById('nameInput');
    const nameError = document.getElementById('nameError');

    nameInput.addEventListener('blur', function() {
        const val = this.value.trim();
        const namePattern = /^[a-zA-Z\s]+$/;
        
        if (val === "") {
            clearNameError();
        } else if (!namePattern.test(val)) {
            nameError.textContent = "* Name can only contain letters and spaces.";
            this.classList.add('input-error');
        } else if (val.length < 2) {
            nameError.textContent = "* Name must be at least 2 characters.";
            this.classList.add('input-error');
        } else {
            clearNameError();
        }
    });

    nameInput.addEventListener('input', clearNameError);
    function clearNameError() {
        nameError.textContent = "";
        nameInput.classList.remove('input-error');
    }

    // 3. Email 实时检查逻辑
    const emailInput = document.getElementById('emailInput');
    const emailError = document.getElementById('emailError');

    emailInput.addEventListener('blur', function() {
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

    emailInput.addEventListener('input', clearEmailError);
    function clearEmailError() {
        emailError.textContent = "";
        emailInput.classList.remove('input-error');
    }

    // 4. Modal 逻辑 (保持不变)
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

    document.getElementById('termsLink').onclick = (e) => {
        e.preventDefault();
        title.textContent = "Terms of Service";
        body.innerHTML = contents.terms;
        modal.style.display = 'block';
    };

    document.getElementById('privacyLink').onclick = (e) => {
        e.preventDefault();
        title.textContent = "Privacy Policy";
        body.innerHTML = contents.privacy;
        modal.style.display = 'block';
    };

    const closeModal = () => modal.style.display = 'none';
    document.querySelector('.close-modal').onclick = closeModal;
    document.getElementById('modalCloseBtn').onclick = closeModal;
    window.onclick = (e) => { if (e.target === modal) closeModal(); };
});