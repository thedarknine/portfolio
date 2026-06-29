import 'photoswipe/dist/photoswipe.min.css';

import PhotoSwipe from 'photoswipe';
import PhotoSwipeLightbox from 'photoswipe/lightbox';

function initGallery() {
    const galleryElement = document.querySelector('.pswp9-lightbox, .pswp9-gallery');

    if (!galleryElement) {
        return;
    }

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

initGallery();
