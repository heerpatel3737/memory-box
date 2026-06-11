document.addEventListener('DOMContentLoaded', () => {
    // Optional: Add some random rotation to elements with class 'random-rotate' (like polaroids)
    const rotatingElements = document.querySelectorAll('.random-rotate');
    rotatingElements.forEach(el => {
        const rotation = (Math.random() * 4) - 2; // Random between -2 and 2 degrees
        el.style.transform = `rotate(${rotation}deg)`;
    });

    // Optional: Add bouncy entrance to cards if IntersectionObserver is supported
    if ('IntersectionObserver' in window) {
        const cards = document.querySelectorAll('.card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        cards.forEach(card => {
            // Only apply if we haven't already hardcoded transformations on the element
            if (!card.style.transform) {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease-out, transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                observer.observe(card);
            }
        });
    }
});
