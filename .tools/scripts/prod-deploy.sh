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
    # shellcheck source=/dev/null
    [ -f "$PROJECT_ROOT/.env.local" ] && source "$PROJECT_ROOT/.env.local"
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

# Download the latest database dump made by deploy.sh on the server to .tools/export/prod/
LOCAL_BACKUP_DIR="$PROJECT_ROOT/.tools/export/prod"

fetch_db_backup() {
    local remote_dump
    display_subtitle "💾 Downloading the production database dump..."
    remote_dump="$(ssh -i "$REMOTE_SSH_KEY" -p "$REMOTE_PORT" "$REMOTE_USER@$REMOTE_HOST" \
        'ls -1t "$HOME"/backups/*/db_*.sql.gz 2>/dev/null | head -n 1')" || remote_dump=""
    if [ -z "$remote_dump" ]; then
        display_warning "No database dump found on the server, nothing downloaded."
        return 0
    fi
    mkdir -p "$LOCAL_BACKUP_DIR"
    if scp -q -i "$REMOTE_SSH_KEY" -P "$REMOTE_PORT" \
        "$REMOTE_USER@$REMOTE_HOST:$remote_dump" "$LOCAL_BACKUP_DIR/"; then
        display_info "Saved to .tools/export/prod/$(basename "$remote_dump")"
    else
        display_warning "Could not download $remote_dump"
    fi
}

# Commande de déploiement sur le serveur distant
REMOTE_DEPLOY_CMD="/home/${REMOTE_USER}/deploy.sh"

deploy_status=0
ssh -i "$REMOTE_SSH_KEY" -p "$REMOTE_PORT" "$REMOTE_USER@$REMOTE_HOST" "$REMOTE_DEPLOY_CMD" || deploy_status=$?

# =====================================================================
# COPIE LOCALE DU DUMP DE PROD
# =====================================================================

# deploy.sh dumps the database before anything else: keep a copy on this machine,
# even when the deployment fails afterwards
fetch_db_backup

if [ "$deploy_status" -ne 0 ]; then
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
