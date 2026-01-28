
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profileForm');
    const saveButton = document.getElementById('saveButton');
    const errorContainer = document.getElementById('js-error-container');

    if (profileForm && saveButton) {
        profileForm.addEventListener('submit', function(event) {
            // Get current values
            const name = profileForm.elements['name'].value.trim();
            const email = profileForm.elements['email'].value.trim();
            const phone = profileForm.elements['phone'].value.trim();
            
            let clientErrors = [];

            // 1. Validate Name
            const namePattern = /^[a-zA-Z\s]+$/;
            if (name === "") {
                clientErrors.push("Full name is required.");
            } else if (!namePattern.test(name)) {
                clientErrors.push("Full name can only contain letters and spaces.");
            }

            // 2. Validate Email (@gmail.com required)
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email === "") {
                clientErrors.push("Email address is required.");
            } else if (!emailPattern.test(email)) {
                clientErrors.push("Invalid email address format.");
            } else if (!email.toLowerCase().endsWith('@gmail.com')) {
                clientErrors.push("Invalid email address format, Only @gmail.com accounts are allowed.");
            }

            // 3. Validate Phone (Malaysia format)
            if (phone.length > 0) {
                if (!phone.startsWith('01') || phone.length < 10 || phone.length > 11) {
                    clientErrors.push("Phone number must start with '01' and be 10-11 digits long.");
                }
            }

            // 4. Unified Error Handling: Build Red Banner HTML
            if (clientErrors.length > 0) {
                event.preventDefault(); // Stop the form from submitting
                
                let errorHtml = '<div class="error-message"><ul style="margin: 0; padding-left: 20px; list-style: none;">';
                clientErrors.forEach(function(msg) {
                    errorHtml += `<li><i class="fas fa-exclamation-circle"></i> ${msg}</li>`;
                });
                errorHtml += '</ul></div>';
                
                // Inject into the page container
                errorContainer.innerHTML = errorHtml;
                
                // Scroll to top smoothly so the user sees the banner
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            // 5. Loading state if validation passes
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveButton.disabled = true;
        });
    }
});