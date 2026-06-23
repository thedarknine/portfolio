#!/bin/bash
set -euo pipefail

# Chargement des utilities et config
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(pwd)"

source "$SCRIPT_DIR/utils.sh"

# Charger .env depuis la racine du projet
if [ -f "$PROJECT_ROOT/.env" ]; then
    set +u  # Désactiver la vérification des variables non-définies temporairement
    source "$PROJECT_ROOT/.env"
    set -u
else
    display_error ".env file not found at $PROJECT_ROOT/.env"
    exit 1
fi

display_title "🌱 Switching to development environment..."
start_time=$(date +%s)
success_msgs=()

# =====================================================================
# SWITCH TO DEVELOPMENT
# =====================================================================

display_subtitle "[1/5] 🔄 Restoring dev dependencies..."
docker compose exec -T -e APP_ENV=dev app composer install || {
    display_error "Failed to restore dev dependencies"
    exit 1
}
success_msgs+=("Dev dependencies restored.")

display_subtitle "[2/5] 🧹 Cleaning dev cache..."
docker compose exec -T -e APP_ENV=dev app php bin/console cache:clear || {
    display_error "Failed to clean dev cache"
    exit 1
}
success_msgs+=("Dev cache cleaned.")

display_subtitle "[3/5] 🎨 Regenerating assets for development..."
docker compose exec -T -e APP_ENV=dev app php bin/console tailwind:build || {
    display_error "Failed to regenerate assets for development"
    exit 1
}
success_msgs+=("Assets regenerated for development.")

display_subtitle "[4/5] 🧹 Cleaning public assets..."
rm -rf public/assets/* || {
    display_error "Failed to clean public assets"
    exit 1
}
success_msgs+=("Public assets cleaned.")

display_subtitle "[5/5] 🧹 Cleaning archive..."
rm -rf var/cache/dev/* || {
    display_error "Failed to clean archive"
    exit 1
}
success_msgs+=("Archive cleaned.")

summary="$(color_green "✅ Development environment restored!")"
success_msgs+=("$summary")

display_elapsed "$start_time"
display_success "${success_msgs[@]}"
