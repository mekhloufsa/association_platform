/**
 * Aura - main.js
 * Gestion des interactions de base et des animations
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Aura: Platform initialized');

    // Burger Menu Logic
    const burger = document.querySelector('.burger-menu');
    const navLinks = document.querySelector('.nav-links');

    if (burger) {
        burger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            burger.classList.toggle('active');
        });
    }

    // Auto-dismiss Flash Messages
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            msg.style.transform = 'translateY(-10px)';
            msg.style.transition = 'all 0.5s ease';
            setTimeout(() => msg.remove(), 500);
        }, 5000);
    });

    // Add subtle hover effects to cards
    const glassPanels = document.querySelectorAll('.glass-panel');
    glassPanels.forEach(panel => {
        panel.addEventListener('mouseenter', () => {
            panel.style.boxShadow = '0 8px 32px 0 rgba(100, 255, 218, 0.1)';
        });
        panel.addEventListener('mouseleave', () => {
            panel.style.boxShadow = 'none';
        });
    });
});
