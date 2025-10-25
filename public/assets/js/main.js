// Fonctionnalités JavaScript communes
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', function() {
            const text = this.getAttribute('data-tooltip');
            const tooltipElement = document.createElement('div');
            tooltipElement.className = 'absolute bg-gray-800 text-white text-xs rounded py-1 px-2 z-10';
            tooltipElement.textContent = text;
            document.body.appendChild(tooltipElement);
            
            const rect = this.getBoundingClientRect();
            tooltipElement.style.top = (rect.top - tooltipElement.offsetHeight - 5) + 'px';
            tooltipElement.style.left = (rect.left + (rect.width / 2) - (tooltipElement.offsetWidth / 2)) + 'px';
            
            this.addEventListener('mouseleave', function() {
                document.body.removeChild(tooltipElement);
            }, { once: true });
        });
    });
    
    // Gestion des animations au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
});

console.log('WinPass Advanced is ready!');
