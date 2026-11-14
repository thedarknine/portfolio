#!/bin/bash
# Generate the Open Graph images (1200x630 layout rendered at 2x, 2400x1260 PNG, sharp on high-density screens) of the pages from the SVG template.
#
# Usage: bash .tools/scripts/generate-og-images.sh [technical_name]
#   Without argument, generates an image for every page of the "page" table.
#
# Data comes from the dev database (db container): tagline -> EYEBROW, title -> TITLE, subtitle -> SUBTITLE.
# Case studies (a template in templates/pages/experience/) get the "ÉTUDE DE CAS" eyebrow instead of the tagline.
# A title longer than MAX_TITLE_ONE_LINE characters is wrapped on two lines in a smaller size.
# Rendering runs in a throwaway Alpine container (rsvg-convert + the Figtree fonts of assets/fonts, converted
# from WOFF2 to TTF since the Alpine FreeType cannot read WOFF2), so nothing has to be installed on the host.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="${PROJECT_ROOT:-$(pwd)}"

# shellcheck source=.tools/scripts/utils.sh
source "$SCRIPT_DIR/utils.sh"

TEMPLATE=".tools/resources/og-template.svg"
WORK_DIR="tmp/og"
OUTPUT_DIR="public/images/og"
FONTS_DIR="assets/fonts/figtree"
RENDER_IMAGE="alpine:3"

CASE_STUDY_DIR="templates/pages/experience"
CASE_STUDY_EYEBROW="ÉTUDE DE CAS"

MAX_TITLE_ONE_LINE=18
MAX_TITLE_LINE=20
MAX_SUBTITLE=55

ONLY="${1:-}"

# In bash >= 5.2, "&" in a ${var//pattern/replacement} replacement means the matched text
shopt -u patsub_replacement 2>/dev/null || true

# Escape the XML special characters of a value before injecting it into the SVG
xml_escape() {
    local value="$1"
    value="${value//&/&amp;}"
    value="${value//</&lt;}"
    value="${value//>/&gt;}"
    printf '%s' "$value"
}

# Split a title on two lines, at the space closest to its middle: prints "line 1<TAB>line 2"
split_title() {
    local title="$1" best="" best_gap=9999 prefix="" word gap
    local -a words
    read -r -a words <<<"$title"
    for ((i = 0; i < ${#words[@]} - 1; i++)); do
        word="${words[$i]}"
        prefix="${prefix:+$prefix }$word"
        gap=$((${#title} - 2 * ${#prefix}))
        gap=${gap#-}
        if [ "$gap" -lt "$best_gap" ]; then
            best_gap=$gap
            best="$prefix"
        fi
    done
    if [ -z "$best" ]; then
        printf '%s\t' "$title"
    else
        printf '%s\t%s' "$best" "${title:$((${#best} + 1))}"
    fi
}

[ -f "$TEMPLATE" ] || {
    display_error "Template not found: $TEMPLATE"
    exit 1
}

# Variables of the .env (DB_USER, DB_PASS, DB_NAME), unless already exported by Task
if [ -z "${DB_NAME:-}" ]; then
    set -a
    # shellcheck source=/dev/null
    source .env
    # shellcheck source=/dev/null
    [ -f .env.local ] && source .env.local
    set +a
fi

display_subtitle "📥 Reading the pages from the database..."
query="SELECT technical_name, tagline, title, subtitle FROM page"
[ -n "$ONLY" ] && query="$query WHERE technical_name = '${ONLY//\'/}'"
query="$query ORDER BY technical_name"

pages="$(docker compose exec -T db mariadb -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" -N -B -e "$query")" ||
    {
        display_error "Cannot read the pages: is the db container running? (task up)"
        exit 1
    }

[ -n "$pages" ] || {
    display_error "No page found${ONLY:+ for \"$ONLY\"}."
    exit 1
}

rm -rf "$WORK_DIR"
mkdir -p "$WORK_DIR" "$OUTPUT_DIR"

template="$(cat "$TEMPLATE")"
count=0
warnings=()

display_subtitle "✏️  Filling the template..."
while IFS=$'\t' read -r name tagline title subtitle; do
    # "None" marks the pages without hero (legal notices)
    [ "$tagline" = "None" ] && tagline=""
    [ "$subtitle" = "None" ] && subtitle=""

    [ -f "$CASE_STUDY_DIR/$name.html.twig" ] && tagline="$CASE_STUDY_EYEBROW"

    if [ "${#title}" -gt "$MAX_TITLE_ONE_LINE" ]; then
        IFS=$'\t' read -r line1 line2 <<<"$(split_title "$title")"
        title_size=64 title_y=380 line2_y=452 subtitle_y=512
    else
        line1="$title" line2=""
        title_size=76 title_y=400 line2_y=400 subtitle_y=462
    fi

    for line in "$line1" "$line2"; do
        [ "${#line}" -gt "$MAX_TITLE_LINE" ] && warnings+=("$name: title line \"$line\" longer than $MAX_TITLE_LINE characters")
    done
    [ "${#subtitle}" -gt "$MAX_SUBTITLE" ] && warnings+=("$name: subtitle longer than $MAX_SUBTITLE characters (${#subtitle})")

    eyebrow="$(printf '%s' "$tagline" | tr '[:lower:]' '[:upper:]')"
    svg="$template"
    svg="${svg//\{\{EYEBROW\}\}/$(xml_escape "$eyebrow")}"
    svg="${svg//\{\{TITLE_LINE_1\}\}/$(xml_escape "$line1")}"
    svg="${svg//\{\{TITLE_LINE_2\}\}/$(xml_escape "$line2")}"
    svg="${svg//\{\{SUBTITLE\}\}/$(xml_escape "$subtitle")}"
    svg="${svg//\{\{TITLE_SIZE\}\}/$title_size}"
    svg="${svg//\{\{TITLE_Y\}\}/$title_y}"
    svg="${svg//\{\{TITLE_LINE_2_Y\}\}/$line2_y}"
    svg="${svg//\{\{SUBTITLE_Y\}\}/$subtitle_y}"
    printf '%s' "$svg" >"$WORK_DIR/og-$name.svg"
    count=$((count + 1))
done <<<"$pages"

display_subtitle "🖼️  Rendering $count image(s)..."
docker run --rm \
    -v "$PROJECT_ROOT":/project -w /project \
    "$RENDER_IMAGE" sh -c "
        apk add --no-cache --quiet rsvg-convert fontconfig py3-fonttools py3-brotli >/dev/null \
        && mkdir -p /usr/share/fonts/figtree \
        && for font in $FONTS_DIR/*.woff2; do
            python3 -c \"import sys; from fontTools.ttLib import TTFont; f = TTFont(sys.argv[1]); f.flavor = None; f.save(sys.argv[2])\" \
                \"\$font\" /usr/share/fonts/figtree/\$(basename \"\$font\" .woff2).ttf || exit 1
        done \
        && fc-cache -f >/dev/null \
        && fc-list | grep -qi 'figtree.*\.ttf' || { echo 'Figtree font not loaded' >&2; exit 1; }
        for svg in $WORK_DIR/*.svg; do
            name=\$(basename \"\$svg\" .svg)
            rsvg-convert -w 2400 -h 1260 \"\$svg\" -o $OUTPUT_DIR/\$name.png || exit 1
        done
    " || {
    display_error "Rendering failed."
    exit 1
}

for warning in "${warnings[@]+"${warnings[@]}"}"; do
    display_warning "$warning"
done

display_success "$count Open Graph image(s) generated in $OUTPUT_DIR"
