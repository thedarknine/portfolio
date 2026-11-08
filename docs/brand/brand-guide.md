# Caroline Noyer — Brand guide

> Rigoureuse, créative, digne de confiance.

Ce guide décrit comment utiliser l'identité visuelle de Caroline Noyer : le logo, les couleurs, la typographie et les composants, sur le portfolio comme sur les autres supports.

**Planche visuelle** : [Planche identité et page d'accueil](https://claude.ai/artifact/6SJECkx8N9rmtxKjsJbiKj)
La planche est la référence visuelle de ce guide. Elle contient deux pages :
- **Planche identité** : logo, couleurs, typographie et composants, en clair et en sombre ;
- **Accueil** : la charte appliquée à la page d'accueil du portfolio.

En cas de doute entre ce document et la planche, les valeurs de ce document font foi.

---

## 1. Le logo

Le logo associe un **symbole** (le monogramme « CN » en forme de ruban) et un **logotype** (« Caroline » en Regular, « NOYER » en Bold).

### Fichiers

| Fichier | Usage |
|---|---|
| `logo-cnoyer.svg` | Version principale, sur fond clair |
| `logo-cnoyer-white.svg` | Version pour fond sombre ou fond photo foncé |
| `symbol-cnoyer.svg` | Symbole seul : avatar, pied de page, réseaux sociaux |
| `favicon.svg` | Favicon du site ; s'adapte automatiquement au mode sombre |

Le texte des SVG est vectorisé, donc la police n'a pas besoin d'être installée pour les afficher. Les fichiers n'ont pas de largeur fixe : on les dimensionne en CSS. Dans l'en-tête du site, le logo est affiché à **200 px** de large.

### Zone de protection

Autour du logo, laisser un espace libre au moins égal à **la moitié de la hauteur du symbole** sur chaque côté.

### Tailles minimales

- Logo complet : **120 px** de large à l'écran, **30 mm** à l'impression.
- En dessous, utiliser le **symbole seul**, jusqu'à **16 px** minimum.

### Ce qu'il ne faut pas faire

- Déformer le logo (étirer, écraser, incliner).
- Changer ses couleurs en dehors de celles prévues dans ce guide.
- Ajouter une ombre portée, un contour ou un effet.
- Réécrire le logotype avec une autre police, ou séparer « Caroline » de « NOYER ».
- Poser la version couleur sur un fond sombre ou chargé : sur fond sombre, **version blanche uniquement**.
- Faire pivoter le symbole ou modifier sa forme.

---

## 2. Les couleurs

### Palette principale

| Nom | Hex | Rôle |
|---|---|---|
| **Pétrole** | `#24506A` | « NOYER », titres, texte fort, liens de navigation, base du dégradé du symbole |
| **Cyan** | `#0B8C9E` | « Caroline », mots mis en valeur dans les grands titres, pastilles décoratives |
| **Cyan foncé** | `#097A8A` | Liens et petits libellés en cyan sur fond clair |
| **Cyan clair** | `#7FCFD6` | « Caroline », liens et libellés en mode sombre |

### Accent

| Nom | Hex | Rôle |
|---|---|---|
| **Safran** | `#F59E0B` | Fond des boutons d'action, bouton Contact, badge « actuel », bordure de l'élément mis en avant, soulignement de la page active |

Le safran **n'apparaît jamais dans le logo** et **ne sert jamais de couleur de texte sur fond clair** : sur du blanc, un orange lisible tire forcément vers le brun. On l'emploie comme fond, bordure ou soulignement. Il guide l'action : on l'utilise **avec parcimonie**, en un ou deux points forts par écran.

### Neutres et texte

| Nom | Hex | Rôle |
|---|---|---|
| Texte courant | `#2B3A47` | Paragraphes sur fond clair |
| Texte secondaire | `#44596A` | Descriptions, légendes, métadonnées |
| Bordure | `#E2E7EC` | Séparateurs, contours de cartes |
| Bordure appuyée | `#D5DEE5` | Contour de la fiche profil et des pastilles |
| Bordure de bouton | `#D5DBE4` | Contour des boutons secondaires |
| Icône discrète | `#9FB0BE` | Icônes décoratives en tête de carte, libellés de la fiche profil |
| Fond d'étiquette | `#E3F1F3` | Étiquettes de compétences (texte en Pétrole) |

### Fonds

| Mode | Fond principal | Fond secondaire |
|---|---|---|
| Clair | `#FFFFFF` | `#F4F6F8` |
| Sombre | `#0F2238` | `#16304D` |

En mode sombre, le texte courant est `#B8C6D1`, le texte secondaire `#9FB0BE`, les titres en blanc et les bordures `#24405F`.

Les bandeaux sombres (en-tête, footer, hero des pages internes) utilisent `#0F2238` en mode clair. En mode sombre, le footer et le hero passent en `#0A1829`, un ton plus foncé que le fond de page, pour rester distincts.

### Dégradé du symbole

Linéaire, du **Pétrole** `#24506A` (en bas à gauche) vers le **Cyan** `#0B8C9E` (en haut à droite). En mode sombre, le symbole passe en blanc uni.

### Accessibilité : contrastes vérifiés

Seuil WCAG AA : 4,5:1 pour le texte courant, 3:1 pour les grands textes et les éléments d'interface.

| Combinaison | Contraste | Verdict |
|---|---|---|
| Pétrole sur blanc | 8,6:1 | Tout usage |
| Texte courant `#2B3A47` sur blanc | 11,7:1 | Tout usage |
| Texte secondaire `#44596A` sur `#F4F6F8` | 6,7:1 | Tout usage |
| Cyan `#0B8C9E` sur blanc | 4,0:1 | Grands textes et logo uniquement |
| Cyan foncé `#097A8A` sur `#F4F6F8` | 4,7:1 | Tout usage, dont les liens |
| Pétrole sur fond d'étiquette | 7,5:1 | Tout usage |
| Texte `#111111` sur safran | 8,8:1 | Tout usage (boutons) |
| Texte blanc sur safran | 2,1:1 | **À éviter** |
| Cyan clair sur `#0F2238` | 9,0:1 | Tout usage |
| Cyan clair sur `#16304D` | 7,5:1 | Tout usage |
| Safran sur `#0F2238` | 7,5:1 | Tout usage |
| Texte `#B8C6D1` sur `#0F2238` | 9,2:1 | Tout usage |

Règle à retenir : **sur un fond safran, on écrit toujours en foncé** (`#111111`), jamais en blanc.

---

## 3. La typographie

### Figtree : la police principale

Sans-serif douce et lisible, en licence libre (SIL Open Font License), disponible sur Google Fonts. Ses formes rondes et ouvertes font écho au ruban du symbole.

| Usage | Graisse | Taille de référence |
|---|---|---|
| Titre principal (h1) | Bold 700, interlettrage −1,5 px | 40 à 68 px, fluide |
| Titre de section (h2) | Bold 700, interlettrage −0,5 px | 28 à 36 px, fluide |
| Titre de carte (h3) | SemiBold 600 | 20 à 22 px |
| Navigation, boutons, liens | Medium 500 | 15 à 17 px |
| Texte courant | Regular 400, interligne 1,6 | 17 à 18 px |

Éviter les graisses inférieures à 400 pour le texte.

### IBM Plex Mono : la police d'appoint

Police à chasse fixe, utilisée en petite taille (12 à 13 px) pour les surtitres de section, les numéros, les dates, les étiquettes et la fiche profil. Humaniste et soignée, elle apporte la touche « développeuse » sans paraître sèche à côté de Figtree. Elle n'est jamais utilisée pour les titres ni pour le texte courant.

### Intégration

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
```

Pour la confidentialité et la performance, il est aussi possible d'auto-héberger les fichiers des polices. C'est le cas sur le portfolio : Figtree (400 à 700) et IBM Plex Mono sont servies depuis `assets/fonts/`.

---

## 4. Les composants

| Composant | Règle |
|---|---|
| **Bouton principal** | Fond safran, texte `#111111` en Medium, hauteur min. 48 px, rayon 10 px. Un seul par zone. |
| **Bouton secondaire** | Contour 1,5 px `#D5DBE4` (blanc à 40 % en sombre), texte Pétrole (blanc en sombre), fond transparent. |
| **Icônes dans les boutons** | Hugeicons (stroke rounded), 18 px, placées **à gauche** du texte : loupe, enveloppe, maison, cookie, flèche… Même règle pour les liens d'action du footer. |
| **Lien texte** | Cyan foncé en clair, cyan clair en sombre, en Medium, suivi d'une flèche « → ». |
| **En-tête** | Fond `#0F2238` dans les deux thèmes, bordure basse `#24405F`, logo blanc à 200 px. Sur l'accueil, il reste transparent au-dessus de la photo tant que la page n'a pas défilé. |
| **Navigation** | Liens en blanc dans l'en-tête, page active soulignée de safran (2 px), bouton Contact en safran. Dans le menu mobile : liens en Pétrole (blanc en sombre), page active soulignée de safran avec la mention « page actuelle ». Zone cliquable min. 44 px. |
| **Étiquette** | IBM Plex Mono 13 px, fond `#E3F1F3`, texte Pétrole, rayon 6 px. |
| **Carte** | Contour `#E2E7EC`, rayon 14 px, titre en Pétrole SemiBold, lien en bas. En tête de carte, une **icône Hugeicons de 28 px en `#9FB0BE`**, plutôt qu'un numéro. |
| **Fiche / console** | En-tête de bloc un ton plus foncé que le corps : `#EEF2F5` sur blanc en clair, `#12283F` sur `#16304D` en sombre. La console des pages d'erreur suit la même logique : corps `#0F2238`, barre de titre `#0A1829`, invite ❯ en safran, liens et curseur en cyan clair. |
| **Élément mis en avant** | Sur fond clair : bordure safran 1,5 px. Sur bandeau sombre : fond `#16304D`, bordure cyan clair 1,5 px. Badge « actuel » safran avec texte `#111111`. |
| **Hero de l'accueil** | Photo plein cadre sous un dégradé bleu nuit (`#0F2238`), texte en blanc, mots clés en cyan clair. |
| **Hero des pages internes** | Photo de la page sous un **voile uniforme** : `#0F2238` à 85 % en clair, `#0A1829` à 88 % en sombre. Pas de dégradé ni d'icône en filigrane. Fil d'Ariane façon terminal (invite safran, curseur cyan clair), surtitre en cyan clair. |
| **Footer** | Fond `#0F2238` (`#0A1829` en sombre), texte `#B8C6D1`, titres de colonnes en IBM Plex Mono cyan clair, signature « © Nine ». |

Rayons : 6 px pour les étiquettes, 10 px pour les boutons, 12 à 16 px pour les cartes et les blocs.

---

## 5. Variables CSS

```css
:root {
  --cn-petrole: #24506A;
  --cn-cyan: #0B8C9E;
  --cn-link: #097A8A;
  --cn-accent: #F59E0B;
  --cn-on-accent: #111111;

  --cn-text: #2B3A47;
  --cn-text-muted: #44596A;
  --cn-border: #E2E7EC;
  --cn-border-strong: #D5DEE5;
  --cn-tag-bg: #E3F1F3;

  --cn-bg: #FFFFFF;
  --cn-bg-alt: #F4F6F8;

  --cn-font: "Figtree", system-ui, sans-serif;
  --cn-font-mono: "IBM Plex Mono", ui-monospace, monospace;
  --cn-radius-tag: 6px;
  --cn-radius-btn: 10px;
  --cn-radius-card: 14px;
}

@media (prefers-color-scheme: dark) {
  :root {
    --cn-petrole: #FFFFFF;
    --cn-link: #7FCFD6;
    --cn-text: #B8C6D1;
    --cn-text-muted: #9FB0BE;
    --cn-border: #24405F;
    --cn-bg: #0F2238;
    --cn-bg-alt: #16304D;
  }
}
```

---

## 6. Ton

Le style visuel suit les trois valeurs de la marque :

- **Rigoureuse** : des mises en page aérées et alignées, peu d'effets, une hiérarchie claire.
- **Créative** : la touche safran, la forme du symbole et les détails en police à chasse fixe apportent de la vie, sans en rajouter.
- **Digne de confiance** : le pétrole domine, les contrastes sont lisibles et le site reste accessible à tous.
