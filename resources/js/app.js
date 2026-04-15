import './bootstrap';

// Auto-hide flash messages after 4 seconds
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.flash-message').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });

    // Star rating interactivity
    const stars = document.querySelectorAll('.star-input');
    stars.forEach((star, idx) => {
        star.addEventListener('change', () => {
            stars.forEach((s, i) => {
                s.closest('label').querySelector('i').classList.toggle('text-yellow-400', i <= idx);
                s.closest('label').querySelector('i').classList.toggle('text-gray-300', i > idx);
            });
        });
    });
});
