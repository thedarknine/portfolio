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
