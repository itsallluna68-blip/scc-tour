import { createIcons, icons } from 'lucide';
import Chart from 'chart.js/auto';
import Alpine from 'alpinejs';

createIcons({
    icons
});

window.Chart = Chart;

window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function() {
    const fadeEls = document.querySelectorAll('.fade-up, .fade-down, .fade-left, .fade-right');
    
    if (fadeEls.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
            });
        }, {
            threshold: 0.15 
        });
        
        fadeEls.forEach(el => observer.observe(el));
    }
});