/* ==========================================================================
   YOUTH FOR JUST FOOD SYSTEMS - GLOBAL INTERACTIVE ENGINE
   ========================================================================== */

// 1. SLEEK TOAST NOTIFICATION UTILITY
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerText = message;
    document.body.appendChild(toast);

    // Slide it in
    setTimeout(() => toast.classList.add('show'), 100);

    // Slide out and remove it
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

// 2. ASYNCHRONOUS FORM PROCESSING (AJAX)
document.addEventListener('DOMContentLoaded', () => {
    const globalForm = document.querySelector('form');
    
    // Only execute if a form actually exists on the current page
    if (globalForm) {
        globalForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Stop page reload

            const formData = new FormData(this);
            // Dynamic path matching: checks form action attribute, defaults to process-engagement.php
            const formAction = this.getAttribute('action') || 'process-engagement.php'; 

            fetch(formAction, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                showToast('Action processed securely and successfully!');
                this.reset(); // Clear form fields
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.');
            });
        });
    }
});