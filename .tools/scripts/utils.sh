#!/bin/bash
# scripts/utils.sh - Fonctions communes

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
CYAN='\033[0;36m'
RESET='\033[0m'

display_title() {
    printf "\n${GREEN}=== %s ===${RESET}\n" "$1"
}

display_subtitle() {
    printf "\n${YELLOW}→ %s${RESET}\n" "$1"
}

display_success() {
    printf "${GREEN}✅ %s${RESET}\n" "$1"
}

display_error() {
    printf "${RED}❌ %s${RESET}\n" "$1" >&2
}

display_warning() {
    printf "${YELLOW}⚠️ %s${RESET}\n" "$1" >&2
}

display_info() {
    printf "${BLUE}ℹ️ %s${RESET}\n" "$1"
}

display_elapsed() {
    local start_time=$1
    local elapsed=$(($(date +%s) - start_time))
    printf "\n${CYAN}⏱️  Duration: %dm %ds${RESET}\n" "$((elapsed / 60))" "$((elapsed % 60))"
}

check_remote_vars() {
    [ -n "${REMOTE_USER:-}" ] || { display_error "REMOTE_USER not set"; exit 1; }
    [ -n "${REMOTE_HOST:-}" ] || { display_error "REMOTE_HOST not set"; exit 1; }
    [ -n "${REMOTE_SSH_KEY:-}" ] || { display_error "REMOTE_SSH_KEY not set"; exit 1; }
    [ -f "${REMOTE_SSH_KEY}" ] || { display_error "SSH key not found"; exit 1; }
}

check_on_main_branch() {
    local current_branch
    current_branch=$(git rev-parse --abbrev-ref HEAD)
    [ "$current_branch" = "main" ] || {
        display_error "You must be on 'main' branch (current: $current_branch)"
        exit 1
    }
}

check_production_branch_exists() {
    git show-ref --quiet refs/heads/production || {
        display_error "Production branch doest not exist"
        exit 1
    }
}

check_no_uncommitted_changes() {
    git diff-index --quiet HEAD -- || {
        display_error "You have uncommitted changes"
        exit 1
    }
}