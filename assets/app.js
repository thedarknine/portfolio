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

    // Menus Burger (Mobile)
    const burgers = document.querySelectorAll('.nine-menu__burger');
    const menus = document.querySelectorAll('.nine-menu');
    const closeButtons = document.querySelectorAll('.nine-menu__close');
    const backdrops = document.querySelectorAll('.nine-menu__backdrop');

    const toggleMenu = () => {
        menus.forEach((menu) => {
            menu.classList.toggle('hidden');
        });
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

    // Toogle theme
    const toggle = document.getElementById('themeToggle');
    const root = document.documentElement;

    toggle.addEventListener('click', () => {
        const next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        root.setAttribute('data-theme', next);
        toggle.setAttribute('data-theme', next);

        const maxAge = 60 * 60 * 24 * 365;
        const secure = location.protocol === 'https:' ? '; Secure' : '';
        if ('cookieStore' in window) {
            window.cookieStore.set({
                name: 'theme',
                value: next,
                path: '/',
                maxAge: maxAge,
                sameSite: 'Lax',
                secure: location.protocol === 'https:',
            });
        } else {
            // Fallback for older browsers that don't support Cookie Store API
            // biome-ignore lint/suspicious/noDocumentCookie: just a fallback to support old browsers
            document.cookie = `theme=${next}; Path=/; Max-Age=${maxAge}; SameSite=Lax${secure}`;
        }
    });
});
