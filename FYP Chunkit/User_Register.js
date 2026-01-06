document.addEventListener('DOMContentLoaded', function () {
    // ==================== 1. 注册表单验证（你原来的全部保留） ====================
    const form = document.getElementById('registerForm');
    const fullName = document.getElementById('fullName');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    const agreeTerms = document.getElementById('agreeTerms');
    const togglePassword = document.getElementById('togglePassword');

    // Show/Hide 密码
    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    function validateField(field, validationFn) {
        const formGroup = field.parentElement;
        const isValid = validationFn(field.value);

        if (isValid) {
            formGroup.classList.remove('error');
            formGroup.classList.add('success');
        } else {
            formGroup.classList.remove('success');
            formGroup.classList.add('error');
        }
        return isValid;
    }

    const validateName = name => name.trim().length >= 2;
    const validateEmail = email => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    const validatePassword = pass => /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$/.test(pass);
    const validateConfirmPassword = confirmPass => confirmPass === password.value;

    fullName.addEventListener('blur', () => validateField(fullName, validateName));
    email.addEventListener('blur', () => validateField(email, validateEmail));
    password.addEventListener('blur', () => {
        validateField(password, validatePassword);
        if (confirmPassword.value) validateField(confirmPassword, validateConfirmPassword);
    });
    confirmPassword.addEventListener('blur', () => validateField(confirmPassword, validateConfirmPassword));

    form.addEventListener('submit', function (e) {
        const isNameValid = validateField(fullName, validateName);
        const isEmailValid = validateField(email, validateEmail);
        const isPasswordValid = validateField(password, validatePassword);
        const isConfirmPasswordValid = validateField(confirmPassword, validateConfirmPassword);
        const isTermsAccepted = agreeTerms.checked;

        if (!isTermsAccepted) {
            e.preventDefault();
            alert('Please agree to the Terms of Service and Privacy Policy');
            return;
        }

        if (!isNameValid || !isEmailValid || !isPasswordValid || !isConfirmPasswordValid) {
            e.preventDefault();
            alert('Please fill in all fields correctly');
        }
        // If all valid, form will submit normally to PHP
    });

    // ==================== 2. 可爱条款弹窗功能（已完美合并） ====================
    const modal = document.getElementById('termsModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');

    const termsContent = {
        terms: `
            <h4>Welcome to Bakery House ♡</h4>
            <p>By creating an account, you're joining our warm family!</p>
            <ul>
                <li>Be over 13 years old (or have parent permission)</li>
                <li>Be kind to others — we're all here for cake!</li>
                <li>Don't share your account</li>
                <li>Allow sweet emails from us ♡</li>
            </ul>
            <p style="margin-top:20px;font-style:italic;color:#b8864e;">Thank you for choosing Bakery House ♡</p>
        `,
        privacy: `
            <h4>Privacy Policy ♡</h4>
            <p>We only collect your name and email to serve you better.</p>
            <p>We will <strong>never</strong> sell or share your data.</p>
            <p>You can delete your account anytime.</p>
            <p style="margin-top:20px;font-style:italic;color:#b8864e;">Your trust is our most precious ingredient ♡</p>
        `
    };

    // 点击 Terms / Privacy 链接打开弹窗
    document.querySelectorAll('.terms-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const type = this.getAttribute('data-type');
            modalTitle.textContent = type === 'terms' ? 'Terms of Service' : 'Privacy Policy';
            modalBody.innerHTML = termsContent[type];
            modal.style.display = 'block';
        });
    });

    // 关闭弹窗
    document.querySelectorAll('.modal-close, #closeModal').forEach(btn => {
        btn.addEventListener('click', () => modal.style.display = 'none');
    });

    window.addEventListener('click', e => {
        if (e.target === modal) modal.style.display = 'none';
    });

    // ==================== 3. 服务器端错误处理 ====================
    const serverErrors = document.getElementById('serverErrors');
    if (serverErrors) {
        // 如果有服务器端错误，为相关字段添加错误样式
        const errors = serverErrors.textContent.toLowerCase();
        
        if (errors.includes('name') || errors.includes('2 characters')) {
            fullName.parentElement.classList.add('error');
        }
        
        if (errors.includes('email') || errors.includes('invalid')) {
            email.parentElement.classList.add('error');
        }
        
        if (errors.includes('password') || errors.includes('8+ chars')) {
            password.parentElement.classList.add('error');
        }
        
        if (errors.includes('passwords do not match')) {
            confirmPassword.parentElement.classList.add('error');
        }
        
        if (errors.includes('agree') || errors.includes('terms')) {
            // 可以添加terms的错误样式
        }
    }
});