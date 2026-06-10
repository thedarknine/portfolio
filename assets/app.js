/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import 'animate.css';
import 'photoswipe/dist/photoswipe.min.css';
import PhotoSwipe from 'photoswipe';
import PhotoSwipeLightbox from 'photoswipe/lightbox';

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
            if (entry.isIntersecting) {
                const element = entry.target;
                const animationClass = element.getAttribute('data-animate');

                // On récupère le délai (on met 0 par défaut si l'attribut n'existe pas)
                const delay = parseInt(element.getAttribute('data-delay'), 10) || 0;

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
    elementsToAnimate.forEach((el) => {
        observer.observe(el);
    });

    // Menus Burger (Mobile)
    const burgers = document.querySelectorAll('.nine-navbar-burger');
    const menus = document.querySelectorAll('.nine-navbar-menu');
    const closeButtons = document.querySelectorAll('.nine-navbar-close');
    const backdrops = document.querySelectorAll('.nine-navbar-backdrop');

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

    // Lightbox
    const myPhotoSwipe = () => {
        const galleryElement = document.querySelector('.pswp9-lightbox, .pswp9-gallery');

        if (galleryElement != null) {
            const lightbox = new PhotoSwipeLightbox({
                gallery: '.pswp9-gallery, .pswp9-lightbox',
                children: '.pswp9-link',
                pswpModule: PhotoSwipe,
            });
            lightbox.addFilter('domItemData', (itemData, element) => {
                const img = element.querySelector('img');

                if (img?.naturalWidth) {
                    itemData.width = img.naturalWidth;
                    itemData.height = img.naturalHeight;
                } else {
                    itemData.width = 1200;
                    itemData.height = 800;
                }
                return itemData;
            });
            lightbox.on('pointerdown', (e) => {
                const linkEl = e.originalEvent.target.closest('.pswp9-link');
                if (linkEl) {
                    const img = new Image();
                    img.src = linkEl.href;
                    img.onload = () => {
                        // L'objet Image est simplement mis en cache par le navigateur pour le clic
                    };
                }
            });

            lightbox.init();
        }
    };
    myPhotoSwipe();

    // Toogle theme
    const toggle = document.getElementById('themeToggle');
    const root = document.documentElement;

    toggle.addEventListener('click', () => {
        const next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        root.setAttribute('data-theme', next);
        toggle.setAttribute('data-theme', next);
        cookieStore.set({
            name: 'theme',
            value: next,
            path: '/',
            maxAge: 60 * 60 * 24 * 365,
        });
    });
});
