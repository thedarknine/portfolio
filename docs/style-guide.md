# Style guide

Visual identity of the portfolio redesign (direction "C — System"). This document is the reference for the
`cno_redesign` branch: tokens, typography, components and voice. When the code and this guide disagree, fix one of
them in the same pull request.

- **Status:** draft, October 2026
- **Replaces:** the July 2026 look (tag `v2026.7`)
- **Mockups:** design canvas "Portfolio — Pistes visuelles", artboards `Accueil — clair / sombre` and
  `Mobile — clair / sombre`

## Principles

1. **Product UI, not decoration.** The site borrows the vocabulary of a product tool: panels, chips, version tags,
   a profile card written like a config file. No gradients, glassmorphism or scroll-triggered animations.
2. **Plain words in titles, technical winks in details.** Headings and body copy are simple, warm and written in the
   first person. The technical flavour lives in small monospace details (`profil.yaml`, `v2024 · actuel`).
3. **Two themes of equal quality.** Light is the default and follows the "C" palette. Dark keeps the night-blue
   atmosphere of the current site, with neutral surfaces.
4. **Accessible by default.** WCAG 2.2 AA: text contrast ≥ 4.5:1, touch targets ≥ 44 × 44 px, visible focus,
   `prefers-reduced-motion` honoured.

## Voice and tone

The UI is in French; code, comments and this guide stay in English.

- First person singular, active voice: "J'ai lancé…", "Je pilote…".
- Short sentences, no jargon in titles. Keep "changelog", "ticket" or "module" out of headings.
- Warm, with a light touch of humour where it fits. Never salesy.
- Figures are real and sourced from the experience pages (300 ideas, 100 releases in 8 months, 500k visits/month).

| Avoid | Prefer |
| --- | --- |
| Changelog | Mes expériences |
| Trois modules, un seul produit | Mon positionnement |
| Ouvrir un ticket | Me contacter |
| Side projects | Ce que je construis pour le plaisir |
| + backlog produit structuré | J'ai structuré le backlog produit. |

## Colour

### Semantic tokens

Components only use semantic tokens. Raw palette values appear once, in `assets/styles/base/theme.css`.
Token names below are the proposed Tailwind v4 `@theme` names (`--color-*`).

| Token | Role | Light | Dark |
| --- | --- | --- | --- |
| `surface` | Page background | `#eef1f5` | `#0f2238` |
| `surface-header` | Sticky header (with backdrop) | `rgb(238 241 245 / 92%)` | `rgb(15 34 56 / 94%)` |
| `panel` | Cards, profile card, secondary button | `#ffffff` | `#1c2a3f` |
| `border` | 1 px borders and dividers | `#d5dbe4` | `#2f3d52` |
| `on-surface` | Main text | `#172c49` | `#e6ecf4` |
| `on-surface-muted` | Secondary text, labels | `#4f5d70` | `#a6b5c8` |
| `accent-text` | Links, eyebrows, highlighted words | `#076f8c` | `#4cc3e6` |
| `primary` | Primary button background | `#087a9c` | `#4cc3e6` |
| `on-primary` | Primary button text | `#ffffff` | `#0b1726` |
| `strong` | Contact button | `#172c49` | `#ffb020` |
| `on-strong` | Text on `strong` | `#ffffff` | `#0f2238` |
| `chip` | Stack chips background | `#e6f3f7` | `#263447` |
| `on-chip` | Stack chips text | `#065f7a` | `#8fdcf2` |
| `band` | Contrasting section (experiences) | `#172c49` | `#152335` |
| `band-card` | Highlighted row inside the band | `#1f3757` | `#1c2a3f` |
| `band-border` | Borders inside the band | `#2d4566` | `#2f3d52` |
| `on-band` | Titles inside the band | `#ffffff` | `#ffffff` |
| `on-band-muted` | Body text inside the band | `#b6c2d1` | `#b4bfcc` |
| `band-accent` | Links inside the band | `#5cc8e8` | `#4cc3e6` |
| `tag` | Version tag background | `#2a4263` | `#2f3d52` |
| `highlight` | Current version tag (`v2024 · actuel`) | `#ffcf70` | `#ffcf70` |
| `on-highlight` | Text on `highlight` | `#172c49` | `#172c49` |
| `status-live` | "Currently at…" dot | `#1f9d6b` | `#3fcf8e` |
| `placeholder` | Image placeholders, skeletons | `#dfe5ec` | `#273548` |
| `timeline-year` | Start years in the experience timeline (large text) | `#2687ab` | `#3aa3c8` |
| `level-start` / `level-end` | Skill level bar gradient | `#ffaa00` / `#f59e0b` | same |
| `surface-footer` | Footer background | `#172c49` | `#0b1a2c` |
| `on-surface-footer` | Footer body text | `#b6c2d1` | `#b4bfcc` |

### Rules

- The light theme's ink is night blue `#172c49` (the former `nine-blue-800`), used for text, the Contact button
  and the experiences band. It must read as blue, never as black: do not go darker.
- Cyan is the only accent colour. Amber is reserved for the current version tag in both themes and for the
  `strong` elements in dark mode.
- Dark surfaces stay neutral: cards are a light veil over night blue, never a saturated blue.
- Client brand colours (`brand-leviia`, `brand-perfect`, `brand-coffreo`) are kept for logos and company pages only.
- `accent-text` and `primary` differ on purpose in light mode: `#087a9c` reaches only 4.34:1 on `surface`, so small
  text uses the darker `#076f8c` (5.06:1).

### Checked contrast ratios

| Pair | Light | Dark |
| --- | --- | --- |
| `on-surface` / `surface` | 12.42 | 13.53 |
| `on-surface-muted` / `surface` | 5.92 | 7.71 |
| `on-surface-muted` / `panel` | 6.70 | 6.93 |
| `accent-text` / `surface` | 5.06 | 7.85 |
| `on-primary` / `primary` | 4.91 | 8.81 |
| `on-chip` / `chip` | 6.33 | 8.22 |
| `on-band-muted` / `band` | 7.79 | 8.51 |
| `on-highlight` / `highlight` | 9.65 | 9.65 |
| `on-strong` / `strong` | 14.07 | 8.79 |

## Typography

Two families, self-hosted as `woff2` in `assets/fonts/` (no Google Fonts request, CSP stays strict).

| Family | Weights | Use |
| --- | --- | --- |
| Space Grotesk | 400, 500, 700 | Headings, body, buttons, navigation |
| IBM Plex Mono | 400, 500 | Eyebrows, chips, tags, metadata, profile card |

Oxygen, Inter, Work Sans and League Spartan are removed.

| Style | Font | Size | Weight | Line height | Tracking |
| --- | --- | --- | --- | --- | --- |
| Display (h1) | Space Grotesk | `clamp(40px, 6vw, 68px)` | 700 | 1.04 | −1.5 px |
| Section title (h2) | Space Grotesk | `clamp(28px, 3.4vw, 36px)` | 700 | 1.15 | −0.5 px |
| CTA title (footer h2) | Space Grotesk | `clamp(32px, 4vw, 44px)` | 700 | 1.1 | −1 px |
| Card title (h3) | Space Grotesk | 20–22 px | 700 | 1.3 | 0 |
| Lead | Space Grotesk | 18 px | 400 | 1.6 | 0 |
| Body | Space Grotesk | 16 px | 400 | 1.6 | 0 |
| Navigation, buttons | Space Grotesk | 14–16 px | 500 | 1 | 0 |
| Eyebrow, label | IBM Plex Mono | 13 px | 400 | 1.4 | 0 |
| Chip, tag | IBM Plex Mono | 13 px | 400–500 | 1.4 | 0 |
| Fine print | IBM Plex Mono | 12 px | 400 | 1.4 | 0 |

Body text moves from 14 px to 16 px. Eyebrows are sentence case, not uppercase.

## Layout and spacing

- **Container:** `max-width: 1240px`, horizontal padding 24 px.
- **Section rhythm:** 80–96 px vertical padding; 32 px between a section title and its content.
- **Grids:** `repeat(auto-fit, minmax(260px, 1fr))` with a 20 px gap, so cards reflow without breakpoints.
- **Hero:** text column (`flex: 1 1 520px`) and profile card (`flex: 1 1 340px`) wrap into one column on narrow screens.
- **Spacing scale:** 4, 8, 12, 16, 20, 24, 32, 40, 48, 56, 80, 96 px (Tailwind defaults cover all of them).

### Radii and elevation

| Element | Radius |
| --- | --- |
| Nav items | 8 px |
| Buttons, theme toggle, image placeholders | 10 px |
| Experience rows | 12 px |
| Cards, side-project tiles | 14 px |
| Profile card | 16 px |
| Status pill | 999 px |
| Chips, version tags | 6 px |

Borders are 1 px `border`. Only the profile card has a shadow: `0 24px 48px -24px` at 25 % (light) or 60 % (dark)
black-navy.

## Components

### Header

Sticky, `surface-header` background, 1 px bottom border. The full logo, 40 px high: dark version on the light
theme, white version on the dark theme (`components/logo.html.twig`). Nav links are 44 px high; Contact uses
`strong`. Below 1024 px the nav collapses behind a 44 × 44 px menu button.

On the home page the header is `fixed` and floats transparent over the photo hero: white text, white logo,
amber Contact button, 14 % white bottom border. It turns into the regular opaque header once the page scrolls by
24 px (`header_controller.js` toggles `is-scrolled`). The bar redefines the colour tokens locally, so the markup
is the same on every page.

### Theme toggle

The sun / moon switch of the previous site is kept: a 68 × 32 px track whose thumb slides from the sun (light) to
the moon (dark). It sits in a `<button role="switch">` with `aria-label="Mode sombre"` and `aria-checked`, which
gives a 44 px touch target. The switch keeps its own colours: amber `#f59e0b` for the sun, indigo `#818cf8` for the
moon. In the mobile menu it is a full-width row with the visible label "Mode sombre".

### Mobile menu

- A native `<dialog>` opened with `showModal()`: Escape, focus trapping and the backdrop come from the browser.
- Panel slides in from the right: `width: min(360px, 86vw)`, full height, `panel` background, 1 px `border` on its
  inner edge. The `::backdrop` is `surface` night blue at 60 % opacity.
- Content, top to bottom: a monospace "Menu" label and a 44 × 44 px close button (no logo: the header logo stays
  visible behind the backdrop), nav links (48 px high, 20 px, weight 500, current page
  in `accent-text`), the Contact button (`strong`, full width), the theme toggle, then social links.
- Behaviour lives in a Stimulus controller (`assets/controllers/menu_controller.js`) wired in Twig with
  `stimulus_controller()`, `stimulus_action()` and `stimulus_target()`. It opens, closes, closes on backdrop click
  and returns focus to the menu button.
- The theme toggle moves to `theme_controller.js` too, so `app.js` only imports the styles.

### Buttons

| Variant | Background | Text | Border |
| --- | --- | --- | --- |
| Primary | `primary` | `on-primary` | none |
| Secondary | `panel` | `on-surface` | 1 px `border` |
| Strong (Contact) | `strong` | `on-strong` | none |

Height 48 px (52 px for the footer CTA), padding 0 22–28 px, radius 10 px, weight 500. Icons 18 px, 10 px gap.
One component replaces `.nine-btn-primary`, `.nine-btn-social` and the inline gradient CTAs.

### Home hero

- Full-bleed photo of the Puy de Dôme range at night (`images/volcans-cnoyer.webp`, preloaded), under the floating
  header.
- Night-blue gradient over the photo: `#172c49` from 94 % on the left to 10 % on the right from 768 px up, a flat
  82 % veil below, so the text always reads on the photo.
- The text column always uses the on-photo palette, whatever the theme: white text, `#d3dbe6` body, `#6cc6e2`
  highlighted words, primary button `#4cc3e6` on `#172c49`, glassy secondary button.
- The profile card sits top right on desktop and below the text on mobile. It keeps the theme tokens, so it is white
  in light mode and `panel` blue in dark mode, and never covers the silhouette in the photo.

### Page hero

Every inner page except the legal notice (`components/page_hero.html.twig`):

- The page image (`images/pages/hero-<technicalName>.webp`) full width, under a night-blue gradient: 100 % on the
  left edge, 93 % at mid-width, 50 % on the right from 768 px up; a flat 92 % veil below.
- Breadcrumb styled as a shell path, `❯ ~/experience/coffreo/refonte-app-mobile ▌`, in a dark box
  (`rgb(11 26 44 / 60%)`, 12 % white border, 10 px radius), monospace 13 px (14 px from 640 px): green prompt
  `#3fcf8e`, segments `#b9c6d6`, separators `#8fa1b9`, current page in white, a static `#3aa3c8` cursor.
  `~` links to the home page; the optional `breadcrumb.level1` shows its URL path, `level2` its label as a slug, then
  the current page slug. Below 640 px the middle levels collapse to `/…` and a long slug is truncated.
- Slugs are for the eye only: every link carries its real label in `aria-label` and `title`, and the current page
  adds its title in `sr-only` text, so assistive technologies read "Expérience", "Coffreo", not slugs.
- Tagline in monospace 13 px, uppercase, `#3aa3c8` (4.9:1 on the hero), then the subtitle as `h1`,
  `clamp(34px, 4.6vw, 52px)`.
- The header stays opaque above it; only the home page has the floating header.

### Page quote

Closing quote of inner pages (`components/page_quote.html.twig`), only when the page has one: `panel` band with
`border` top and bottom, a 72 px opening quote mark in `accent-text` at 20 %, the quote centred in
`clamp(24px, 3vw, 32px)`, weight 500.

### Experience timeline

- One card per experience on `panel`. From 768 px, a 112 px rail on the left holds the start year (30 px bold,
  `timeline-year`) and the company logo (64 px, neutral grey backdrop), joined by a dashed vertical line.
  Below 768 px the year and logo sit in a row above each card.
- Card header: an `actuel` `highlight` chip for the current role, the role in `accent-text` monospace, the mission as
  `h2`, then dates, duration and place in monospace. External and PDF links are secondary buttons on the right.
- Achievements in a grid of `minmax(320px, 1fr)` columns: bold title, muted text. No emoji.
- Card footer: the stack as 32 px logos (six shown, a `+n` chip reveals the rest) and the detail pages as `chip`
  links.

### Skills

- One section per skill type, separated by a `border` rule. From 1024 px the type sits in a sticky 300 px column on
  the left (monospace index `01`, `h2`, description), the skills fill a grid of `minmax(240px, 1fr)` cards.
- Skill card on `panel`: 44 px logo on a `surface` tile, name in bold, level label in `accent-text` monospace
  (Expert from 8, Avancé from 6, Maîtrisé below), a 6 px level bar with the amber gradient (`level-start` at 40 %
  to `level-end`) on `surface` (decorative, the label carries the meaning), and a monospace footer
  "pratique · duration", with "en cours" when the skill has no end year.
- Same hover lift as the other cards; no entrance animation.

### Education

- Same section layout as Skills: professional then university training, each with a sticky 300 px column (index,
  `h2`, the illustration from 1024 px only) and a list of cards up to 760 px wide.
- Education card on `panel`: the year (26 px bold, `timeline-year`), an optional `highlight` chip "Mention …", the
  school logo on the shared `.logo-backdrop` tile, the diploma as `h3`, school and place in monospace, then a
  bordered footer with the certification (`primary` dot) and the option (`level-end` dot).
- Same hover lift as the other cards.

### Projects

- A short muted introduction, then a grid of project cards (`minmax(460px, 1fr)`, one column on phones).
- Project card on `panel`: 48 px logo, name as `h2`, period in monospace, category as a `chip` (top right on desktop,
  above the title on phones), the description, then screenshot thumbnails (96 × 64 px, `border`, `accent-text` border
  and a zoom icon on hover) that open in the PhotoSwipe lightbox (`.pswp9-gallery` / `.pswp9-link`).
- Bordered footer in monospace: tags as `#tag` on `surface`, and the number of screenshots.
- Same hover lift as the other cards.

### Galleries

Créations and Photos share `components/gallery_section.html.twig`:

- One section per type, separated by a `border` rule: monospace index, `h2`, and the image count on the right.
- A grid of square thumbnails, 2 columns on phones, 3 from 640 px, 4 from 1024 px; images are cropped with
  `object-cover` in a 14 px radius frame on `placeholder`, and shown whole in the PhotoSwipe lightbox, one gallery per
  section.
- Hover: a slight zoom of the image (`motion-safe`), and for photos the caption fades in on a night-blue gradient.
- Every link has an `aria-label` ("Agrandir : …"), every image an `alt` built from the caption or the type name.

### Arcade

The arcade page is a build log, not a plain gallery:

- Introduction in 18 px muted text, with the mascot illustration on the right (above the text on phones).
- "This is it!": the finished bartop on a `band` section, square thumbnails in the shared gallery grid.
- "Les étapes de construction": an ordered timeline reusing the experience rail (`.step-timeline`): the rail shows
  "étape" in monospace and the step number (40 px bold, `timeline-year`), the content holds the `h3`, the original
  text and the step photos (`components/arcade_gallery.html.twig`). Sub-parts of a step get a monospace label in
  brackets, e.g. `[Side Panels]`.
- Every grid opens in the PhotoSwipe lightbox, one gallery per grid.

### Legal notice

The legal notice has no photo hero and no quote, it stays sober:

- Title block on the page background: `legal.md` monospace label in `accent-text`, then the `h1`, closed by a
  bottom border.
- Two columns from 1024 px: a sticky summary on the left (300 px, `panel` card, numbered anchor links at least
  44 px tall), the text on the right (760 px max).
- Each section opens with a numbered `h2` (`01`, `02`… in monospace `accent-text`) and `scroll-mt-28` so the
  sticky header does not hide it when following an anchor.
- Key/value blocks (publisher, host) use `.legal-card`: monospace muted terms, rows split by a border.
- Credited libraries are small `panel` tiles with the role in monospace on the right, two per row from 640 px.
- Links in the text are underlined in `accent-text`; the Matomo opt-out widget sits in a `panel` card and inherits
  the theme colours. Styles live in `assets/styles/pages/legal.css`.

### Status pill

Pill on `panel` with a `border`, an 8 px `status-live` dot and a monospace label ("Actuellement Technical Product
Owner chez Leviia").

### Profile card

`panel` card, 320 px max, with a monospace title bar (`profil.yaml` / current year and month) and a `<dl>` of
key/value pairs: base, since (from `getStartCareerYear()`), stack (chips). The title bar shows `site_version`.

### Cards

`panel`, 1 px `border`, 14 px radius, 28 px padding. Monospace index (`01`, `02`, `03`) in `on-surface-muted`,
h3 title, body in `on-surface-muted`, then a text link in `accent-text`. No emoji. On hover the card lifts by 4 px
with a soft shadow (see Motion).

### Experience rows

Inside a `band` section. Left column (170 px): version tag (`v2024`, `highlight` for the current role) and company
name in monospace. Right column: role title (h3) and one or two first-person sentences. The current role sits on
`band-card`; the others only have a `band-border`.

### Chips and tags

Monospace, 13 px, 2 × 8–10 px padding, 6 px radius. Chips (`chip` / `on-chip`) list technologies. Tags (`tag` or
`highlight`) carry versions or dates.

### Side-project tiles

Grid with 220 px rows. The arcade tile spans two rows and holds a photo; the last tile uses `primary` to point to
"Ce site, sous le capot". Each tile has a monospace category label and a 22–24 px title.

### Footer

A tall footer on `surface-footer`, 96 px top padding, no logo:

- Left: a two-sentence signature in 18 px (opening phrase in white, weight 500), then the resource links as 44 px
  square icon buttons with a `band-border` outline. It stays short: the footer is on every page and must not repeat
  the home page pitch. No hardcoded year counts: use `getNbYearsExperience()` or `getStartCareerYear()`.
- Right: three link columns driven by the page categories (`career`, `interest`, `about`), with monospace headings in
  `band-accent` and 40 px high white links.
- Bottom row, above a `band-border` rule: `© Nine · <start year>–<current year>` followed by the site version as a
  `tag` chip (`app.version`, Twig global `site_version`), and a "Retour en haut ↑" link.
- No entrance animation.

The home page ends with the "Envie d'en parler ?" call to action just above the footer: CTA title and a short
paragraph on the left, Strong button on the right.

## Iconography

- Stroke icons only, 2 px stroke, round caps, 18–20 px, `currentColor`. Use the Hugeicons stroke set already
  available through `ux_icon()`.
- No emoji in the UI. The emoji currently stored with experience items (`item.picto`) are replaced by icons.
- Decorative SVG gets `aria-hidden="true"`; icon-only buttons get an `aria-label`.

## Imagery

- Photos keep their natural colours; no grayscale or hover zoom effects.
- Images sit in 10–14 px radius frames on `placeholder` while loading.
- The home hero is the only full-bleed photo. Inner pages keep their own hero images.

## Motion

Motion answers what the user does; nothing moves on its own.

- **Allowed:** interaction feedback — hover and focus states, the theme switch, the mobile menu. 150–300 ms,
  `ease-out` on enter, `ease-in` on exit.
- **Hover movement:** cards and tiles lift by 4 px (`-translate-y-1`) with a soft shadow; list rows (experiences,
  about pillars) shift right by 6 px (`translate-x-1.5`). 300 ms `ease-out`, always behind the `motion-safe:`
  variant, and only `transform`, so the layout never moves.
- **Mobile menu:** the panel slides from `translateX(100%)` to `0` and the backdrop fades in, 250 ms on open and
  200 ms on close. Done in CSS with `transition`, `@starting-style` and `transition-behavior: allow-discrete` on the
  `<dialog>` and its `::backdrop`; Stimulus only opens and closes.
- **Theme switch:** colours fade over 200 ms; the toggle thumb slides with a slight overshoot (400 ms).
- **Not allowed:** scroll-triggered entrance animations. `animations.css` (`animate__*`, `data-animate`), the
  `IntersectionObserver` in `app.js` and the typed hero text are removed.
- Under `prefers-reduced-motion: reduce` every transition is instant.

## Dark mode

- Driven by `data-theme="dark"` on `<html>` and the `theme` cookie, as today.
- Every component is checked in both themes before merge; mockups exist for both at desktop and mobile widths.

## Clean-up list

Items in the current code that this guide retires:

- Hardcoded colours: `text-[#0C91B9]`, `slate-*`, `bg-white`, `dark:bg-gray-800` (the theme toggle keeps its
  own sun and moon colours).
- Classes that do not exist in Tailwind v4 or come from Bootstrap: `text-turquoise`, `text-blue-gray-400`,
  `text-blueGray-500`, `font-heading`, `d-inline`, `list-unstyled`.
- Brand gradients: `.nine-bg-gradient`, `.nine-border-gradient`, `.card:hover` border image.
- `nine-*` palette tokens that no semantic token references once the migration is done.
- The legacy `--font-normal` and `--font-accent` theme variables: until they go, the Tailwind `font-normal` class
  sets the Oxygen font family instead of the font weight, so do not use it in new templates.
- The burger and theme scripts in `app.js`, replaced by Stimulus controllers, and the `.nine-menu` rule whose
  `slide` animation is not defined anywhere.
