import { Controller } from '@hotwired/stimulus';

const COOKIE_MAX_AGE = 60 * 60 * 24 * 365;

/*
 * Light / dark theme switch.
 *
 * The theme is stored on <html data-theme> and in the `theme` cookie, which layout.html.twig reads on the
 * server side. The toggle is a role="switch" button: aria-checked is true in dark mode. Every toggle on
 * the page (header and mobile menu) listens to `theme:changed` to stay in sync.
 */
export default class extends Controller {
    connect() {
        this.onThemeChanged = () => this.render();
        window.addEventListener('theme:changed', this.onThemeChanged);
        this.render();
    }

    disconnect() {
        window.removeEventListener('theme:changed', this.onThemeChanged);
    }

    toggle() {
        const root = document.documentElement;
        const next = root.dataset.theme === 'dark' ? 'light' : 'dark';

        root.dataset.theme = next;
        this.persist(next);
        window.dispatchEvent(new CustomEvent('theme:changed', { detail: { theme: next } }));
    }

    render() {
        const isDark = document.documentElement.dataset.theme === 'dark';

        this.element.setAttribute('aria-checked', String(isDark));
    }

    persist(theme) {
        const secure = 'https:' === window.location.protocol;

        if ('cookieStore' in window) {
            window.cookieStore.set({
                name: 'theme',
                value: theme,
                path: '/',
                expires: Date.now() + COOKIE_MAX_AGE * 1000,
                sameSite: 'lax',
                secure: secure,
            });

            return;
        }

        // Fallback for browsers without the Cookie Store API.
        // biome-ignore lint/suspicious/noDocumentCookie: fallback for older browsers
        document.cookie = `theme=${theme}; Path=/; Max-Age=${COOKIE_MAX_AGE}; SameSite=Lax${secure ? '; Secure' : ''}`;
    }
}
