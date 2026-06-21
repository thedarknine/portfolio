import Typed from 'typed.js';

// STICKY HEADER
document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.nine-sticky-bar');

    window.addEventListener('scroll', () => {
        var scroll = window.scrollY;
        if (scroll < 40) {
            header.classList.remove('is-sticky', 'animate__animated', 'animate__fadeInDown');
        } else {
            header.classList.add('is-sticky', 'animate__animated', 'animate__fadeInDown');
        }
    });
});

// TYPED.JS
new Typed('#typedtext', {
    strings: ['Product Ownership', 'UX Design', 'Web development'],
    startDelay: 500,
    typeSpeed: 50,
    backSpeed: 20,
    backDelay: 1000,
    loop: true,
});
