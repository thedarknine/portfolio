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

display_title "🚀 Deploying to production branch..."
start_time=$(date +%s)

# =====================================================================
# CONFIRMATIONS ET VÉRIFICATIONS
# =====================================================================

read -r -p "⚠️  Are you sure you want to deploy to production? [y/N] " answer
if [[ "$answer" != [yY] ]]; then
    display_warning "Deployment cancelled."
    exit 0
fi

display_subtitle "🔍 Running pre-deployment checks..."
check_on_main_branch
check_no_uncommitted_changes
check_production_branch_exists
display_success "All checks passed!"

# =====================================================================
# BUILD POUR PRODUCTION
# =====================================================================

display_subtitle "🏗️ Building assets for production..."

docker compose exec -T app php bin/console tailwind:build --minify --env=prod || {
    display_error "Failed to build Tailwind"
    exit 1
}

docker compose exec -T app php bin/console importmap:install --env=prod || {
    display_error "Failed to install importmap"
    exit 1
}

docker compose exec -T app php bin/console assets:install --env=prod || {
    display_error "Failed to install assets"
    exit 1
}

docker compose exec -T app php bin/console asset-map:compile --env=prod || {
    display_error "Failed to compile asset-map"
    exit 1
}

docker compose exec -T app php bin/console cache:clear --env=prod || {
    display_error "Failed to clear production cache"
    exit 1
}

display_success "Assets built successfully!"

# =====================================================================
# PRÉPARATION : Sauvegarder les assets dans un fichier temporaire
# =====================================================================

display_subtitle "💾 Saving compiled assets to temporary location..."

TEMP_ASSETS_DIR=$(mktemp -d)
trap 'rm -rf $TEMP_ASSETS_DIR' EXIT  # Nettoyer à la fin

# Copier les assets compilés
if [ -d "public/assets" ]; then
    cp -r public/assets "$TEMP_ASSETS_DIR/" || {
        display_error "Failed to backup assets"
        exit 1
    }
    display_success "Assets backed up to: $TEMP_ASSETS_DIR"
else
    display_warning "No public/assets directory found"
fi

# =====================================================================
# GIT OPS : Passage à production
# =====================================================================

display_subtitle "🔄 Switching to production branch..."

if ! git checkout production 2>&1; then
    display_error "Failed to checkout production branch"
    exit 1
fi

display_success "Switched to production branch"

# =====================================================================
# FUSION AVEC MAIN
# =====================================================================

display_subtitle "🔀 Merging main into production..."

if ! git merge main --no-edit 2>&1; then
    display_error "Merge conflict or merge failed"
    display_warning "Aborting merge and returning to main..."
    git merge --abort || true
    git checkout main || true
    exit 1
fi

display_success "Main merged into production"

# =====================================================================
# RESTAURER LES ASSETS COMPILÉS
# =====================================================================

display_subtitle "📦 Restoring compiled assets..."

if [ -d "$TEMP_ASSETS_DIR/assets" ]; then
    rm -rf public/assets
    cp -r "$TEMP_ASSETS_DIR/assets" public/ || {
        display_error "Failed to restore assets"
        git checkout main
        exit 1
    }
    display_success "Assets restored"
else
    display_warning "No assets to restore"
fi

# =====================================================================
# COMMIT DES ASSETS
# =====================================================================

display_subtitle "📝 Committing compiled assets..."

git add public/assets/ || true

# Vérifier s'il y a des changements à commiter
if git diff --cached --quiet; then
    display_info "No changes to commit"
else
    COMMIT_DATE=$(date +'%Y-%m-%d %H:%M:%S')
    if ! git commit --no-verify -m "chore: assets compiled for production ($COMMIT_DATE)"; then
        display_error "Failed to commit assets"
        git checkout main
        exit 1
    fi
    display_success "Assets committed"
fi

# =====================================================================
# PUSH À PRODUCTION
# =====================================================================

display_subtitle "⬆️ Pushing to production remote..."

if ! git push origin production 2>&1; then
    display_error "Failed to push to production"
    display_warning "Rolling back commit..."
    git reset --hard HEAD~1
    git checkout main
    exit 1
fi

display_success "Pushed to production successfully!"

# =====================================================================
# RETOUR À MAIN
# =====================================================================

display_subtitle "🔁 Returning to main branch..."

if ! git checkout main 2>&1; then
    display_error "Failed to return to main branch"
    exit 1
fi

display_success "Back on main branch"

# =====================================================================
# FIN
# =====================================================================

display_elapsed "$start_time"
display_success "🎉 Production deployment completed successfully!"
