function toggleMenu() {
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('active');
}

function reorderElements() {
    const menuIcon = document.querySelector('.menu-icon');

    if (window.innerWidth <= 768) {
        menuIcon.removeAttribute('hidden');
    } else {
        menuIcon.setAttribute('hidden', true);
    }
}

window.addEventListener('DOMContentLoaded', reorderElements);
window.addEventListener('resize', reorderElements);

// Função para inicializar o carrossel
function initializeCarousel() {
    const carousels = document.querySelectorAll('.carousel');

    carousels.forEach(carousel => {
        let imgs = carousel.querySelectorAll('img');
        let index = 0;

 
        function startCarousel() {
            imgs.forEach(img => img.style.display = 'none');
            imgs[index].style.display = 'block'; 
        }

        function showNextImage() {
            imgs[index].style.display = 'none'; 
            index = (index + 1) % imgs.length; 
            imgs[index].style.display = 'block';
        }

        startCarousel();
        carousel.interval = setInterval(showNextImage, 3000); 
    });
}

window.addEventListener('DOMContentLoaded', initializeCarousel);

function setActiveLink() {
    const linkId = 'projeto-link'; 
    const linkElement = document.getElementById(linkId);

    if (linkElement) {
        linkElement.classList.add('active');
    }
}


document.addEventListener('DOMContentLoaded', setActiveLink);
