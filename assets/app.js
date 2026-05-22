/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import $ from 'jquery';
import './styles/app.css';

// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

$(document).ready(() => {
    // Display animations from animate.css on element visible
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1,
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const animationClass = element.getAttribute('data-animate');

                // On récupère le délai (on met 0 par défaut si l'attribut n'existe pas)
                const delay = parseInt(element.getAttribute('data-delay')) || 0;

                // On arrête d'observer l'élément immédiatement pour éviter les doublons
                observer.unobserve(element);

                setTimeout(() => {
                    element.classList.add('animate__animated', animationClass);
                    element.removeAttribute('data-animate');
                }, delay);
            }
        });
    }, options);

    const elementsToAnimate = document.querySelectorAll('[data-animate]');
    elementsToAnimate.forEach((el) => observer.observe(el));

    // Menus Burger (Mobile)
    const burgers = document.querySelectorAll('.nine-navbar-burger');
    const menus = document.querySelectorAll('.nine-navbar-menu');
    const closeButtons = document.querySelectorAll('.nine-navbar-close');
    const backdrops = document.querySelectorAll('.nine-navbar-backdrop');

    const toggleMenu = () => {
        menus.forEach((menu) => menu.classList.toggle('hidden'));
    };
    if (burgers.length && menus.length) {
        // Applying the 'click' event on each burger button
        burgers.forEach((burger) => {
            burger.addEventListener('click', toggleMenu);
        });

        // Grouping all elements that trigger the closure (close buttons + dark backdrop)
        const closeElements = [...closeButtons, ...backdrops];

        closeElements.forEach((element) => {
            element.addEventListener('click', toggleMenu);
        });
    }
});
