// ========== Menu Toggle ==========
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const toggleIcon = document.getElementById('toggleIcon');
    const toggleText = document.getElementById('toggleText');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            this.classList.toggle('is-active');
            toggleIcon.classList.toggle('is-active');
            toggleText.textContent = this.classList.contains('is-active') ? 'close' : 'menu';
        });
    }
});
