// Toggle password visibility between text and password types
function togglePasswordDisplay(id) {
    const input = document.getElementById(id);
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Initialize form validation on page load
document.addEventListener('DOMContentLoaded', function() {
    const passwordForm = document.getElementById('passwordForm');
    const saveButton = document.getElementById('saveButton');
    const errorContainer = document.getElementById('js-error-container');

    if (passwordForm && saveButton) {
        passwordForm.addEventListener('submit', function(event) {
            const currentPwd = document.getElementById('current_password').value.trim();
            const newPwd = document.getElementById('new_password').value.trim();
            const confirmPwd = document.getElementById('confirm_password').value.trim();
            
            let clientErrors = [];
            // Check if current password is empty
            if (currentPwd === "") {
                clientErrors.push("Current password is required.");
            }

            // Validate new password strength (8+ chars, letters & numbers)
            const hasLetter = /[A-Za-z]/.test(newPwd);
            const hasNumber = /[0-9]/.test(newPwd);

            if (newPwd.length < 8 || !hasLetter || !hasNumber) {
                clientErrors.push("Password must be 8+ chars with letters & numbers.");
            }

            // Verify if new passwords match
            if (newPwd !== confirmPwd) {
                clientErrors.push("New passwords do not match.");
            }
            
            // Display errors if validation fails
            if (clientErrors.length > 0) {
                event.preventDefault();
                
                let errorHtml = '<div class="error-message"><ul style="margin: 0; list-style: none; padding: 0;">';
                clientErrors.forEach(function(msg) {
                    errorHtml += `<li><i class="fas fa-exclamation-circle"></i> ${msg}</li>`;
                });
                errorHtml += '</ul></div>';
                
                errorContainer.innerHTML = errorHtml;
                
                window.scrollTo({ top: 150, behavior: 'smooth' });
                return;
            }

            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            saveButton.disabled = true;
        });
    }
});