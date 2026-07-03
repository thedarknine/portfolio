#!/bin/bash
# scripts/utils.sh - Fonctions communes

# =====================================================================
# COLORS
# =====================================================================

RESET='\033[0m'
BOLD='\033[1m'

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'

BG_RED='\033[41m'
BG_GREEN='\033[42m'
BG_YELLOW='\033[43m'
BG_BLUE='\033[44m'
BG_PURPLE='\033[45m'
BG_CYAN='\033[46m'

display_box() {
    local bg_color="$1"
    local fg_color="$2"
    local label="$3"

    shift 3

    {
        printf "\n"
        printf "%b┌────────────────────────────────────────────────────────────────────┐%b\n" "${RESET}${bg_color}  ${RESET}  ${fg_color}" "$RESET"
        printf "%b%s%b\n" "${RESET}${bg_color}  ${RESET}  ${BOLD}${fg_color}" "$label" "$RESET"

        if [ "$#" -gt 0 ]; then
            printf "%b \n" "${RESET}${bg_color}  ${RESET}"

            for line in "$@"; do
                printf "%b %b  %b•%b %s\n" "${RESET}${bg_color}  ${RESET}" "$RESET" "$fg_color" "$RESET" "$line"
            done
        fi

        printf "%b└────────────────────────────────────────────────────────────────────┘%b\n" "${RESET}${bg_color}  ${RESET}  ${fg_color}" "$RESET"
    }
}

display_title() {
    display_box "$BG_PURPLE" "$PURPLE" "$@"
    printf "\n"
}

display_subtitle() {
    printf "\n${BG_YELLOW}  ${RESET}${YELLOW}  → %s${RESET}\n" "$@"
}

display_success() {
    if [ "${MAKELEVEL:-0}" -le 1 ]; then
        display_box "$BG_GREEN" "$GREEN" "✅ SUCCESS" "$@"
    fi
}

display_error() {
    display_box "$BG_RED" "$RED" "❌ ERROR" "$@" >&2
}

display_warning() {
    display_box "$BG_YELLOW" "$YELLOW" "⚠️ WARNING" "$@" >&2
}

display_info() {
    display_box "$BG_BLUE" "$BLUE" "ℹ️ INFO" "$@"
}

display_elapsed() {
    local start_time=$1
    local elapsed=$(($(date +%s) - start_time))
    printf "\n${BG_CYAN}  ${RESET}${CYAN}  ⏱️  Duration: %dm %ds${RESET}\n" "$((elapsed / 60))" "$((elapsed % 60))"
}

color_green() {
    printf '%b' "${GREEN}$1${RESET}"
}

print_link() {
    local url="$1"
    local text="$2"
    printf "\e]8;;%s\e\\%s\e]8;;\e\\\\\n" "$url" "$text"
}

check_remote_vars() {
    [ -n "${REMOTE_USER:-}" ] || {
        display_error "REMOTE_USER not set"
        exit 1
    }
    [ -n "${REMOTE_HOST:-}" ] || {
        display_error "REMOTE_HOST not set"
        exit 1
    }
    [ -n "${REMOTE_SSH_KEY:-}" ] || {
        display_error "REMOTE_SSH_KEY not set"
        exit 1
    }
    [ -f "${REMOTE_SSH_KEY}" ] || {
        display_error "SSH key not found"
        exit 1
    }
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
