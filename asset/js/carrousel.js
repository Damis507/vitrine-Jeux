const slider = document.querySelector('.slider');
const dots = document.querySelectorAll('.dot');
let currentSlide = 0;
let pauseTimeout;

function goToSlide(index) {
    currentSlide = index;
    
    // Mettre à jour les points
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });

    // Arrêter temporairement l'animation et forcer la position
    slider.style.transform = `translateX(-${index * 100}%)`;
    slider.classList.add('paused');
    
    // Réinitialiser l'animation après un court délai
    clearTimeout(pauseTimeout);
    pauseTimeout = setTimeout(() => {
        slider.style.transform = '';
        slider.classList.remove('paused');
    }, 5000); // Reprend l'animation après 5 secondes
}

// Mettre à jour les points en fonction de l'animation
function updateDots() {
    const transform = getComputedStyle(slider).transform;
    const matrix = new DOMMatrix(transform);
    const translateX = matrix.m41;
    const index = Math.round(Math.abs(translateX) / slider.offsetWidth);
    
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
}

// Surveiller l'animation pour mettre à jour les points
slider.addEventListener('animationiteration', () => {
    updateDots();
});

// Mettre à jour les points pendant l'animation
setInterval(updateDots, 100);