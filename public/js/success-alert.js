// Success alert auto-dismiss
document.addEventListener('DOMContentLoaded', function () {
    const alert = document.getElementById('successAlert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 3000);
    }
});
