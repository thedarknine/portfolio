#!/bin/bash
set -euo pipefail

# Chargement des utilities et config
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(pwd)"

# shellcheck source=.tools/scripts/utils.sh
source "$SCRIPT_DIR/utils.sh"

# Charger .env depuis la racine du projet
if [ -f "$PROJECT_ROOT/.env" ]; then
    set +u # Désactiver la vérification des variables non-définies temporairement
    source "$PROJECT_ROOT/.env"
    set -u
else
    display_error ".env file not found at $PROJECT_ROOT/.env"
    exit 1
fi

display_title "⚡️ Deploying to remote server..."
start_time=$(date +%s)

# =====================================================================
# VÉRIFICATIONS PRÉALABLES
# =====================================================================

display_subtitle "🔍 Checking remote configuration..."

# Vérifier les variables requises
if [ -z "${REMOTE_USER:-}" ] || [ -z "${REMOTE_HOST:-}" ] ||
    [ -z "${REMOTE_SSH_KEY:-}" ] || [ -z "${REMOTE_PORT:-}" ]; then
    display_error "Missing remote configuration"
    display_info "Required variables: REMOTE_USER, REMOTE_HOST, REMOTE_SSH_KEY, REMOTE_PORT"
    exit 1
fi

# Vérifier que la clé SSH existe
if [ ! -f "$REMOTE_SSH_KEY" ]; then
    display_error "SSH key not found: $REMOTE_SSH_KEY"
    exit 1
fi

# Vérifier les permissions de la clé SSH
if [ "$(stat -f%A "$REMOTE_SSH_KEY" 2>/dev/null || stat -c%a "$REMOTE_SSH_KEY")" != "600" ]; then
    display_warning "SSH key permissions not 600, attempting to fix..."
    chmod 600 "$REMOTE_SSH_KEY" || {
        display_error "Failed to set SSH key permissions"
        exit 1
    }
fi

display_success "Remote configuration verified"

# =====================================================================
# CONNEXION SSH ET DÉPLOIEMENT
# =====================================================================

display_subtitle "🚀 Connecting to remote server ($REMOTE_USER@$REMOTE_HOST:$REMOTE_PORT)..."

# Commande de déploiement sur le serveur distant
REMOTE_DEPLOY_CMD="${HOME}/deploy.sh"

if ! ssh -i "$REMOTE_SSH_KEY" -p "$REMOTE_PORT" "$REMOTE_USER@$REMOTE_HOST" "$REMOTE_DEPLOY_CMD"; then
    display_error "Remote deployment failed"
    display_info "Attempting automatic rollback..."

    # =====================================================================
    # ROLLBACK EN CAS D'ÉCHEC
    # =====================================================================

    display_subtitle "🔄 Rolling back changes..."

    if ! git checkout main 2>&1; then
        display_error "Failed to checkout main during rollback"
        exit 1
    fi

    if ! git reset --hard HEAD~1 2>&1; then
        display_error "Failed to reset HEAD during rollback"
        exit 1
    fi

    if ! git push origin production --force 2>&1; then
        display_error "Failed to force push during rollback"
        display_warning "Manual intervention may be required"
        exit 1
    fi

    display_error "Rollback completed. Remote deployment failed. Check the server logs."
    exit 1
fi

display_success "Remote deployment completed successfully!"

# =====================================================================
# FIN
# =====================================================================

display_elapsed "$start_time"
display_success "🎉 Production deployment to remote server completed!"
