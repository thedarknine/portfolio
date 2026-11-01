import { Controller } from '@hotwired/stimulus';

const COOKIE_NAME = 'consent_analytics';
// CNIL guidance: a consent choice is kept 13 months at most
const COOKIE_MAX_AGE = 60 * 60 * 24 * 395;

/*
 * Cookie consent banner and preferences dialog.
 *
 * The visitor's choice for audience measurement is stored in the `consent_analytics` cookie
 * ("granted" or "denied"). layout.html.twig reads it to render the banner hidden once a choice exists.
 * Matomo is only loaded after consent: no tracker runs before the visitor accepts, and none at all
 * outside production (empty matomoUrl value).
 *
 * Any element with [data-consent-open] (footer, legal notice) reopens the preferences dialog.
 */
export default class extends Controller {
    static targets = ['banner', 'dialog', 'analytics'];

    static values = {
        matomoUrl: String,
        matomoSiteId: { type: String, default: '1' },
    };

    connect() {
        this.onOpenRequest = (event) => {
            if (event.target.closest('[data-consent-open]')) {
                event.preventDefault();
                this.openPreferences();
            }
        };
        document.addEventListener('click', this.onOpenRequest);

        const choice = this.readChoice();

        if (null === choice) {
            this.bannerTarget.hidden = false;
        } else if ('granted' === choice) {
            this.loadMatomo();
        }
    }

    disconnect() {
        document.removeEventListener('click', this.onOpenRequest);
    }

    acceptAll() {
        this.save('granted');
    }

    refuseAll() {
        this.save('denied');
    }

    openPreferences() {
        this.analyticsTarget.checked = 'granted' === this.readChoice();
        this.dialogTarget.showModal();
    }

    closePreferences() {
        this.dialogTarget.close();
    }

    closeOnBackdrop(event) {
        if (event.target === this.dialogTarget) {
            this.closePreferences();
        }
    }

    savePreferences() {
        this.save(this.analyticsTarget.checked ? 'granted' : 'denied');
        this.closePreferences();
    }

    save(choice) {
        const previous = this.readChoice();

        this.writeChoice(choice);
        this.bannerTarget.hidden = true;

        if ('granted' === choice) {
            this.loadMatomo();
        } else if ('granted' === previous) {
            this.stopMatomo();
        }
    }

    loadMatomo() {
        if (!this.matomoUrlValue || window.matomoLoaded) {
            return;
        }

        window.matomoLoaded = true;

        const url = this.matomoUrlValue;
        window._paq = window._paq || [];
        const paq = window._paq;

        paq.push(['setDoNotTrack', true]);
        paq.push(['trackPageView']);
        paq.push(['enableLinkTracking']);
        paq.push(['setTrackerUrl', `${url}matomo.php`]);
        paq.push(['setSiteId', this.matomoSiteIdValue]);

        const script = document.createElement('script');
        script.async = true;
        script.src = `${url}matomo.js`;
        document.head.append(script);
    }

    stopMatomo() {
        if (window._paq) {
            window._paq.push(['optUserOut']);
        }

        // Remove the Matomo cookies already set on this domain
        for (const name of document.cookie.split(';').map((cookie) => cookie.split('=')[0].trim())) {
            if (name.startsWith('_pk_') || 'mtm_consent' === name) {
                this.deleteCookie(name);
            }
        }
    }

    readChoice() {
        const match = document.cookie.match(new RegExp(`(?:^|; )${COOKIE_NAME}=(granted|denied)`));

        return match ? match[1] : null;
    }

    writeChoice(choice) {
        const secure = 'https:' === window.location.protocol;

        // Written synchronously so readChoice() sees it right away
        // biome-ignore lint/suspicious/noDocumentCookie: the Cookie Store API is asynchronous
        document.cookie = `${COOKIE_NAME}=${choice}; Path=/; Max-Age=${COOKIE_MAX_AGE}; SameSite=Lax${secure ? '; Secure' : ''}`;
    }

    deleteCookie(name) {
        // Matomo may set its cookies on the host or on the parent domain: clear both
        for (const domain of ['', `; Domain=.${window.location.hostname.replace(/^www\./, '')}`]) {
            // biome-ignore lint/suspicious/noDocumentCookie: cookie removal
            document.cookie = `${name}=; Path=/; Max-Age=0${domain}`;
        }
    }
}
