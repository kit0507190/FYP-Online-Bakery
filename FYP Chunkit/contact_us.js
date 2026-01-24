/**
 * contact_us.js
 */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Entry animations for Hero section
    const title = document.getElementById('heroTitle');
    const sub = document.getElementById('heroSubtitle');
    setTimeout(() => {
        if (title) title.classList.add('fade-in');
        if (sub) sub.classList.add('fade-in');
    }, 200);

    // 2. Form submission handling
    const form = document.getElementById('contactForm');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = form.querySelector('.submit-btn');
            
            // Show loading spinner and disable button to prevent double clicks
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Baking Message...';
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none'; 
            }, 10);
        });
    }
});

/**
 * Closes the success toast and resets form/button states
 */
function closeToast() {
    const toast = document.getElementById('toastOverlay');
    if (toast) {
        toast.style.transition = '0.3s';
        toast.style.opacity = '0';
        
        setTimeout(() => {
            toast.remove();
            
            // Reset form and restore button state for the next submission
            const form = document.getElementById('contactForm');
            if (form) {
                form.reset(); 
                const btn = form.querySelector('.submit-btn');
                if (btn) {
                    btn.innerHTML = 'Send Message';
                    btn.style.opacity = '1';
                    btn.style.pointerEvents = 'auto'; 
                }
            }
        }, 300);
    }
}