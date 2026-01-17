/* resetpassword.js */

// 切换密码可见性
function togglePass(inputId, toggleText) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        toggleText.textContent = "Hide";
    } else {
        input.type = "password";
        toggleText.textContent = "Show";
    }
}

// 确保 DOM 加载完成后再绑定事件
document.addEventListener('DOMContentLoaded', function() {
    const resetForm = document.getElementById('resetForm');
    
    if (resetForm) {
        resetForm.onsubmit = function(e) {
            const p = document.getElementById('password').value;
            const cp = document.getElementById('confirm_password').value;
            const errorLabel = document.getElementById('password-js-error');
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('confirm_password');
            
            // 重置状态
            errorLabel.style.display = 'none';
            passwordInput.style.borderColor = '#e1e1e1';
            confirmInput.style.borderColor = '#e1e1e1';

            // 正则验证：必须含字母和数字，且至少8位
            const hasLetter = /[A-Za-z]/.test(p);
            const hasNumber = /[0-9]/.test(p);

            if (p.length < 8 || !hasLetter || !hasNumber) {
                errorLabel.textContent = "Password must be 8+ chars with letters & numbers.";
                errorLabel.style.display = 'block';
                passwordInput.style.borderColor = '#e74c3c';
                e.preventDefault(); // 拦截提交
                return false;
            } 
            
            if (p !== cp) {
                errorLabel.textContent = "Passwords do not match.";
                errorLabel.style.display = 'block';
                confirmInput.style.borderColor = '#e74c3c';
                e.preventDefault(); // 拦截提交
                return false;
            }
        };

        // 输入时自动清除错误样式
        document.getElementById('password').oninput = function() {
            document.getElementById('password-js-error').style.display = 'none';
            this.style.borderColor = '#e1e1e1';
        };
    }
});