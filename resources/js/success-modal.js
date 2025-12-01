// Success Alert Auto-close
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('successAlert');
    if (alert) {
        // Auto-close after 3 seconds
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 3000);
    }
});
