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

            // 2. Validate Email (Updated to support multiple domains)
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const allowedDomains = ['gmail.com', 'student.mmu.edu.my', 'yahoo.com', 'hotmail.com'];
            const emailDomain = email.split('@')[1] ? email.split('@')[1].toLowerCase() : '';

            if (email === "") {
                clientErrors.push("Email address is required.");
            } else if (!emailPattern.test(email)) {
                clientErrors.push("Invalid email address format.");
            } else if (!allowedDomains.includes(emailDomain)) {
                // Check if the domain is in our allowed list
                clientErrors.push("Only @gmail.com, @student.mmu.edu.my, @yahoo.com, and @hotmail.com are allowed.");
            }

            // 3. Validate Phone (Malaysia format)
            if (phone.length > 0) {
                if (!phone.startsWith('01') || phone.length < 10 || phone.length > 11) {
                    clientErrors.push("Phone number must start with '01' and be 10-11 digits long.");
                }
            }

            // 4. Unified Error Handling: Build Red Banner HTML
            if (clientErrors.length > 0) {
                event.preventDefault(); 
                
                let errorHtml = '<div class="error-message"><ul style="margin: 0; padding-left: 20px; list-style: none;">';
                clientErrors.forEach(function(msg) {
                    errorHtml += `<li><i class="fas fa-exclamation-circle"></i> ${msg}</li>`;
                });
                errorHtml += '</ul></div>';
                

                errorContainer.innerHTML = errorHtml;
                

                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }


            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveButton.disabled = true;
        });
    }
});