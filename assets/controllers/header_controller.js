import { Controller } from '@hotwired/stimulus';

/*
 * Overlay header (home page).
 *
 * The header floats transparent over the photo hero and becomes opaque as soon as the page scrolls:
 * this controller only toggles the `is-scrolled` class, the styles live in header.css.
 */
export default class extends Controller {
    static values = { threshold: { type: Number, default: 24 } };

    connect() {
        this.onScroll = () => this.update();
        window.addEventListener('scroll', this.onScroll, { passive: true });
        this.update();
    }

    disconnect() {
        window.removeEventListener('scroll', this.onScroll);
    }

    update() {
        this.element.classList.toggle('is-scrolled', window.scrollY > this.thresholdValue);
    }
}
