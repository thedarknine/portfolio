import $ from 'jquery';
import Typed from 'typed.js';

// STICKY HEADER
const header = $('.nine-sticky-bar');
const win = $(window);

win.on('scroll', () => {
    var scroll = win.scrollTop();
    if (scroll < 40) {
        if (header.hasClass('nine-stick')) {
            header.removeClass('nine-stick');
        }
    } else {
        header.addClass('nine-stick');
    }
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
