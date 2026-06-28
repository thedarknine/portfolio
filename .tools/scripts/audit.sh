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
display_subtitle "📊 [1/2] Lighthouse (page d'accueil)..."
docker compose exec app npx lighthouse http://engine:80 \
  --chrome-path=/usr/bin/chromium \
  --chrome-flags="--headless --no-sandbox --disable-dev-shm-usage --disable-gpu --no-zygote" \
  --output=html \
  --output-path="$REPORTS_DIR/lighthouse/index.html" \
  --no-enable-error-reporting

summary="$(print_link "file://$PROJECT_ROOT/$REPORTS_DIR/lighthouse/index.html" "Lighthouse Report")"
success_msgs+=("📄 Audit: $summary <- Open in browser")

# 2. Unlighthouse (perf + SEO + a11y sur tout le site)
display_subtitle "🔍 [2/2] Unlighthouse (tout le site)..."
UNLIGHTHOUSE_OUT=".tools/reports/unlighthouse"
link="$(print_link "http://localhost:5678" "http://localhost:5678")"
display_info "   Dashboard disponible sur $link pendant le scan"
docker compose exec app pnpm unlighthouse --config-file .tools/unlighthouse.config.ts --build-static --output-path "$UNLIGHTHOUSE_OUT"

success_msgs+=("📄 Generated Unlighthouse reports:")
while read -r f; do
    file="$(print_link "file://$PROJECT_ROOT/$f" "$f")"
    success_msgs+=("  → $file")
done < <(find .tools/reports/unlighthouse -name "lighthouse.html")

# summary="$(print_link "file://$PROJECT_ROOT/$UNLIGHTHOUSE_REPORT" "Unlighthouse Report")"
# success_msgs+=("Audit: $summary - Open in browser")

# Clean up sitemaps
rm -f public/sitemap*.xml

summary="$(color_green "✅ Audits completed!")"
success_msgs+=("$summary")

display_elapsed "$start_time"
display_success "${success_msgs[@]}"
