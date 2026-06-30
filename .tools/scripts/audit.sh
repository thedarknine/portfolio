#!/bin/bash
set -euo pipefail

# Chargement des utilities et config
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="${PROJECT_ROOT:-$(pwd)}"

source "$SCRIPT_DIR/utils.sh"

REPORTS_DIR=".tools/reports"

start_time=$(date +%s)
success_msgs=()

# Clean up previous reports
rm -rf "$REPORTS_DIR/unlighthouse"
rm -rf "$REPORTS_DIR/lighthouse"

mkdir -p "$REPORTS_DIR/unlighthouse"
mkdir -p "$REPORTS_DIR/lighthouse"

# Force to regenerate sitemaps
display_subtitle "🔄 Regenerating sitemaps..."
docker compose exec app php bin/console presta:sitemaps:dump --base-url=http://engine

# 1. Lighthouse (audit détaillé page d'accueil)
display_subtitle "📊 [1/3] Lighthouse (page d'accueil)..."
docker compose exec app npx lighthouse http://engine:80 \
  --chrome-path=/usr/bin/chromium \
  --chrome-flags="--headless --no-sandbox --disable-dev-shm-usage --disable-gpu --no-zygote" \
  --output=html \
  --output-path="$REPORTS_DIR/lighthouse/index.html" \
  --no-enable-error-reporting

summary="$(print_link "file://$PROJECT_ROOT/$REPORTS_DIR/lighthouse/index.html" "Lighthouse Report")"
success_msgs+=("📄 Audit: $summary <- Open in browser")

# 2. Unlighthouse (perf + SEO + a11y sur tout le site)
display_subtitle "🔍 [2/3] Unlighthouse (tout le site)..."
UNLIGHTHOUSE_OUT="$REPORTS_DIR/unlighthouse"
link="$(print_link "http://localhost:5678" "http://localhost:5678")"
display_info "   Dashboard disponible sur $link pendant le scan"
docker compose exec app pnpm unlighthouse --config-file .tools/unlighthouse.config.ts --build-static --output-path "$UNLIGHTHOUSE_OUT"

success_msgs+=("📄 Generated Unlighthouse reports:")
while read -r f; do
    file="$(print_link "file://$PROJECT_ROOT/$f" "$f")"
    success_msgs+=("  → $file")
done < <(find "$UNLIGHTHOUSE_OUT" -name "lighthouse.html")

display_subtitle "🔍 [3/3] Pa11y parsing sitemap..."
if ! docker compose exec app npx pa11y-ci --config .tools/pa11y.json --sitemap http://engine/sitemap.default.xml; then
  warning_msgs=("Pa11y failed")
  summary="$(print_link "file://$PROJECT_ROOT/$REPORTS_DIR/pa11y/index.html" "Pa11y Report")"
  warning_msgs+=("Check: $summary - Open in browser")
  display_warning "${warning_msgs[@]}"
  exit 1
fi

summary="$(print_link "file://$PROJECT_ROOT/$REPORTS_DIR/pa11y/index.html" "Pa11y Report")"
success_msgs+=("Pa11y report: $summary - Open in browser")

# Clean up sitemaps
rm -f public/sitemap*.xml

summary="$(color_green "✅ Audits completed!")"
success_msgs+=("$summary")

display_elapsed "$start_time"
display_success "${success_msgs[@]}"
