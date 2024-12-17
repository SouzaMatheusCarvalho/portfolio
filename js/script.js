class TypingAnimation {
    constructor(element, roles) {
        this.element = element;
        this.roles = roles;
        this.roleIndex = 0;
        this.text = '';
        this.isDeleting = false;
        this.typingSpeed = 100;
        this.deletingSpeed = 50;
        this.pauseBetweenRoles = 2000;

        this.type();
    }
    
    type() {
        const currentRole = this.roles[this.roleIndex];
        
        if (!this.isDeleting) {
            if (this.text.length < currentRole.length) {
                this.text = currentRole.slice(0, this.text.length + 1);
                this.element.textContent = this.text;
                setTimeout(() => this.type(), this.typingSpeed);
            } else {
                setTimeout(() => {
                    this.isDeleting = true;
                    this.type();
                }, this.pauseBetweenRoles);
            }
        } else {
            if (this.text.length > 0) {
                this.text = currentRole.slice(0, this.text.length - 1);
                this.element.textContent = this.text;
                setTimeout(() => this.type(), this.deletingSpeed);
            } else {
                this.isDeleting = false;
                this.roleIndex = (this.roleIndex + 1) % this.roles.length;
                setTimeout(() => this.type(), this.typingSpeed);
            }
        }
    }
}

function toggleMenu() {
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('active');
}

function reorderElements() {
    const menuIcon = document.querySelector('.menu-icon');
    const homeSection = document.querySelector('.home');
    const textAnimation = document.querySelector('.text-animation'); 
    if (window.innerWidth <= 936) {
        homeSection.classList.add('mobile-view');
        menuIcon.removeAttribute('hidden');

       
        if (!textAnimation.querySelector('br')) { 
            const br = document.createElement('br');
            textAnimation.insertBefore(br, textAnimation.querySelector('#typing-text'));
        }
    } else {
        homeSection.classList.remove('mobile-view');
        menuIcon.setAttribute('hidden', true);

        const br = textAnimation.querySelector('br');
        if (br) {
            br.remove();
        }
    }
}

function setActiveLink() {
    const linkId = 'home-link'; 
    const linkElement = document.getElementById(linkId);

    if (linkElement) {
        linkElement.classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const roles = [
        "Desenvolvedor Web", 
        "Desenvolvedor Full Stack", 
        "Desenvolvedor .NET", 
        "Desenvolvedor Mobile", 
        "Desenvolvedor Python"
    ];
    
    const typingText = document.getElementById('typing-text');
    new TypingAnimation(typingText, roles);

    setActiveLink();
    reorderElements();
});

window.addEventListener('resize', reorderElements);
