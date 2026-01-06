document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const rememberMe = document.getElementById('rememberMe');

    // Load remembered email from cookie
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    const rememberedEmail = getCookie('user_email');
    if (rememberedEmail) {
        emailInput.value = rememberedEmail;
        rememberMe.checked = true;
    }

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePassword.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            togglePassword.textContent = 'Show';
        }
    });

    // Real-time validation
    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);

    function validateEmail() {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = email !== '' && emailRegex.test(email);
        
        toggleError(emailInput, !isValid);
        return isValid;
    }

    function validatePassword() {
        const isValid = passwordInput.value.trim() !== '';
        toggleError(passwordInput, !isValid);
        return isValid;
    }

    function toggleError(input, showError) {
        const formGroup = input.parentElement;
        const errorMessage = formGroup.querySelector('.error-message');
        
        if (showError) {
            formGroup.classList.add('error');
            errorMessage.style.display = 'block';
        } else {
            formGroup.classList.remove('error');
            errorMessage.style.display = 'none';
        }
    }

    // Form submission - only prevent if client-side validation fails
    form.addEventListener('submit', function(e) {
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();

        if (!isEmailValid || !isPasswordValid) {
            e.preventDefault();
            alert('Please fill in all required fields correctly');
        }
        // If validation passes, allow form to submit to PHP
    });

    // Clear errors when user starts typing
    emailInput.addEventListener('input', function() {
        toggleError(this, false);
    });

    passwordInput.addEventListener('input', function() {
        toggleError(this, false);
    });
});