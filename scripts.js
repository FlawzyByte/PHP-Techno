
function createDynamicParticles() {
    const particlesContainer = document.querySelector('.floating-particles');
    
    if (!particlesContainer) return;
    

    for (let i = 0; i < 15; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 8 + 's';
        particle.style.animationDuration = (Math.random() * 4 + 6) + 's';
        particle.style.width = (Math.random() * 6 + 3) + 'px';
        particle.style.height = particle.style.width;
        

        const colors = ['rgba(255, 0, 110, 0.4)', 'rgba(131, 56, 236, 0.4)', 'rgba(58, 134, 255, 0.4)', 'rgba(6, 255, 165, 0.4)', 'rgba(255, 190, 11, 0.4)'];
        particle.style.background = colors[Math.floor(Math.random() * colors.length)];
        particle.style.boxShadow = `0 0 8px ${particle.style.background}`;
        
        particlesContainer.appendChild(particle);
    }
}


function addMouseInteraction() {
    document.addEventListener('mousemove', function(e) {
        const trippyBg = document.querySelector('.trippy-bg');
        if (!trippyBg) return;
        
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;

        trippyBg.style.transform = `translate(${x * 5}px, ${y * 5}px) scale(1.02)`;
    });
}


document.addEventListener('DOMContentLoaded', function() {
    //dynamic particles
    createDynamicParticles();
    

    addMouseInteraction();
}); 