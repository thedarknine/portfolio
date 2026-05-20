import $ from 'jquery';

// STICKY HEADER 
const header = $('.sticky-bar');
const win = $(window);
let unstickTimer = null;

win.on('scroll', () => {
    const scroll = win.scrollTop();

    if (scroll < 40) {
        // Si le menu était collé, on lance la phase de décollage
        if (header.hasClass('nine-stick')) {
            header.removeClass('nine-stick').addClass('nine-unstick');

            clearTimeout(unstickTimer);

            // On attend exactement 300ms (le temps que fadeOutUp se joue)
            unstickTimer = setTimeout(() => {
                header.removeClass('nine-unstick');
            }, 300); 
        }
    } else {
        clearTimeout(unstickTimer);
        header.removeClass('nine-unstick').addClass('nine-stick');
    }
});

// TYPED.JS
const _typed = new Typed('#typedtext', {
    strings: ['Product Ownership', 'UX Design', 'Développement web'],
    startDelay: 500,
    typeSpeed: 50,
    backSpeed: 20,
    backDelay: 1000,
    loop: true,
});