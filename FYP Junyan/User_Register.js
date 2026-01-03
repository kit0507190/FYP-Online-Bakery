document.addEventListener('DOMContentLoaded', function () {
    // --- 1. 密码切换逻辑 ---
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

    // --- 2. 邮箱后缀实时检查 ---
    const emailInput = document.getElementById('emailInput');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailValue = this.value.trim().toLowerCase();
            if (emailValue !== "" && !emailValue.endsWith('@gmail.com')) {
                alert("Please use a valid @gmail.com address.");
                this.style.borderColor = "#f56565";
            } else {
                this.style.borderColor = "#f0efed";
            }
        });
    }

    // --- 3. 弹窗逻辑 ---
    const modal = document.getElementById('policyModal');
    const title = document.getElementById('policyTitle');
    const body = document.getElementById('policyBody');

    const contents = {
        terms: `<h4>1. Acceptance</h4><p>By creating an account, you agree to our community standards.</p>`,
        privacy: `<h4>1. Data Collection</h4><p>We only collect your email to manage your account.</p>`
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

    const close = () => modal.style.display = 'none';
    document.querySelector('.close-modal').onclick = close;
    document.getElementById('modalCloseBtn').onclick = close;
    window.onclick = (e) => { if (e.target === modal) close(); };
});