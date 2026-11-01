import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('DOMContentLoaded', () => {
    // Display animations from animate.css on element visible
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1,
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            const element = entry.target;

            observer.unobserve(element);

            if (element.dataset.delay) {
                element.style.animationDelay = element.dataset.delay;
            }

            element.classList.add('animate__animated', element.dataset.animate);

            element.removeAttribute('data-animate');
            element.removeAttribute('data-delay');
        });
    }, options);

    const elementsToAnimate = document.querySelectorAll('[data-animate]');

    elementsToAnimate.forEach((element) => {
        observer.observe(element);
    });
});
