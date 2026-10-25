import { Controller } from '@hotwired/stimulus';

/*
 * Mobile navigation menu.
 *
 * Opens a native <dialog> as a modal: the browser handles Escape, focus trapping and the backdrop.
 * The slide and fade animations live in assets/styles/components/header.css.
 */
export default class extends Controller {
    static targets = ['dialog', 'button'];

    connect() {
        // Close the menu when the viewport grows past the mobile breakpoint (Tailwind `lg`).
        this.desktopQuery = window.matchMedia('(min-width: 1024px)');
        this.onBreakpointChange = (event) => {
            if (event.matches) {
                this.close();
            }
        };
        this.desktopQuery.addEventListener('change', this.onBreakpointChange);
    }

    disconnect() {
        this.desktopQuery.removeEventListener('change', this.onBreakpointChange);
    }

    open() {
        if (!this.dialogTarget.open) {
            this.dialogTarget.showModal();
        }
    }

    close() {
        if (this.dialogTarget.open) {
            this.dialogTarget.close();
        }

        if (this.hasButtonTarget) {
            this.buttonTarget.focus();
        }
    }

    closeOnBackdrop(event) {
        // A click on the ::backdrop is reported on the <dialog> element itself.
        if (event.target === this.dialogTarget) {
            this.close();
        }
    }
}
