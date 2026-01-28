// Function to hide and remove the toast notification
function closeToast() {
    const overlay = document.getElementById('toastOverlay');
    
    if (overlay) {
        // Disable interaction and execute fade-out animation
        overlay.style.pointerEvents = 'none';
        overlay.style.transition = '0.3s';
        overlay.style.opacity = '0';
        
        // Remove element and clean URL after animation
        setTimeout(() => {
            overlay.remove();
            
            if (window.history.replaceState) {
                const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
            }
        }, 300);
    }
}

// Initial state check on page load
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('toastOverlay');
    
    
    if (window.location.search.includes('success=1')) {
        if (window.history.replaceState) {
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
        }
    } else if (overlay) {
        
        overlay.remove();
    }
});