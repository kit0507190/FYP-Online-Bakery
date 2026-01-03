document.addEventListener('DOMContentLoaded', function () {
    // --- 1. 密码显示/隐藏切换逻辑 ---
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

    // --- 2. 弹窗动态内容逻辑 ---
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

    // 关闭弹窗
    const close = () => modal.style.display = 'none';
    document.querySelector('.close-modal').onclick = close;
    document.getElementById('modalCloseBtn').onclick = close;
    
    // 点击遮罩层关闭
    window.onclick = (e) => { if (e.target === modal) close(); };
});