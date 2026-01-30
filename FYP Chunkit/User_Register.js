document.addEventListener('DOMContentLoaded', function () {
    // 1. Password display toggle(Show/Hide Function)
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

    // 2. Element Definition
    const nameInput = document.getElementById('nameInput');
    const nameError = document.getElementById('nameError');
    const emailInput = document.getElementById('emailInput');
    const emailError = document.getElementById('emailError');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirmPassword');
    const confirmError = document.getElementById('confirmError');

    // 3. Real-time Full Name check logic 
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            const val = this.value;
            const namePattern = /^[a-zA-Z\s]*$/;
            
            if (val === "") {
                clearNameError();
            } else if (!namePattern.test(val)) {
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

    // 4. Real-time Email Check Logic (support multiple domains)
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            const val = this.value.trim().toLowerCase();
            const allowedDomains = ['gmail.com', 'student.mmu.edu.my', 'yahoo.com', 'hotmail.com'];
            if (val === "") {
                clearEmailError();
            } else {
                const domain = val.split('@')[1];
                
                if (!domain || !allowedDomains.includes(domain)) {
                    emailError.textContent = "* Please use @gmail.com, @student.mmu.edu.my, @yahoo.com or @hotmail.com";
                    this.classList.add('input-error');
                } else {
                    clearEmailError();
                }
            }
        });
    }

    function clearEmailError() {
        if (emailError) emailError.textContent = "";
        if (emailInput) emailInput.classList.remove('input-error');
    }

    // 5. Confirm Password Real-time Comparison Logic 
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

    // 6. Modal pop-up logic 
    // 6. 模态框弹出逻辑
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