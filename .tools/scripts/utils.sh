#!/usr/bin/env bash
# Standardized console output, shared by Taskfile tasks and scripts.
#
# As a command (from Taskfile, via the UI var):
#   bash .tools/scripts/utils.sh title "🚀 Installing"
#   bash .tools/scripts/utils.sh success "Done." "optional detail"
#   Commands: title, subtitle, success, error, warning, info, elapsed, link
#
# As a library (from another script):
#   source .tools/scripts/utils.sh && display_info "Hello"
#
# UI_NESTED=1 hides success boxes, so a task called by another one
# (lint:all -> lint:yaml) doesn't print a box at each step.
# Same for a nested make call (MAKELEVEL > 1), until the Makefile is gone.

# =====================================================================
# COLORS
# =====================================================================
# ANSI-C quoting ($'...') stores the real escape characters.

RESET=$'\033[0m'
BOLD=$'\033[1m'

RED=$'\033[0;31m'
GREEN=$'\033[0;32m'
YELLOW=$'\033[0;33m'
BLUE=$'\033[0;34m'
PURPLE=$'\033[0;35m'
CYAN=$'\033[0;36m'

BG_RED=$'\033[41m'
BG_GREEN=$'\033[42m'
BG_YELLOW=$'\033[43m'
BG_BLUE=$'\033[44m'
BG_PURPLE=$'\033[45m'
BG_CYAN=$'\033[46m'

# =====================================================================
# DISPLAY
# =====================================================================

display_box() {
    local bg_color="$1"
    local fg_color="$2"
    local label="$3"
    local bar="${RESET}${bg_color}  ${RESET}"

    shift 3

    printf '\n'
    printf '%s  %s┌────────────────────────────────────────────────────────────────────┐%s\n' "$bar" "$fg_color" "$RESET"
    printf '%s  %s%s%s%s\n' "$bar" "$BOLD" "$fg_color" "$label" "$RESET"

    if [ "$#" -gt 0 ]; then
        printf '%s \n' "$bar"
        for line in "$@"; do
            printf '%s   %s•%s %s\n' "$bar" "$fg_color" "$RESET" "$line"
        done
    fi

    printf '%s  %s└────────────────────────────────────────────────────────────────────┘%s\n' "$bar" "$fg_color" "$RESET"
}

display_title() {
    display_box "$BG_PURPLE" "$PURPLE" "$@"
    printf '\n'
}

display_subtitle() {
    printf '\n%s  %s%s  → %s%s\n' "$BG_YELLOW" "$RESET" "$YELLOW" "$*" "$RESET"
}

display_success() {
    if [ -z "${UI_NESTED:-}" ] && [ "${MAKELEVEL:-0}" -le 1 ]; then
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

# Usage: display_elapsed <start timestamp>
display_elapsed() {
    local start_time=$1
    local elapsed=$(($(date +%s) - start_time))
    printf '\n%s  %s%s  ⏱️  Duration: %dm %ds%s\n' "$BG_CYAN" "$RESET" "$CYAN" "$((elapsed / 60))" "$((elapsed % 60))" "$RESET"
}

color_green() {
    printf '%s%s%s' "$GREEN" "$1" "$RESET"
}

# Clickable link in compatible terminals (OSC 8)
print_link() {
    local url="$1"
    local text="${2:-$1}"
    printf '\e]8;;%s\e\\%s\e]8;;\e\\\n' "$url" "$text"
}

# =====================================================================
# CHECKS (production deployment)
# =====================================================================

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
        display_error "Production branch does not exist"
        exit 1
    }
}

check_no_uncommitted_changes() {
    git diff-index --quiet HEAD -- || {
        display_error "You have uncommitted changes"
        exit 1
    }
}

# =====================================================================
# COMMAND MODE (only when executed, not when sourced)
# =====================================================================

if [ "${BASH_SOURCE[0]}" = "$0" ]; then
    command="${1:-}"
    shift || true

    case "$command" in
    title | subtitle | success | error | warning | info | elapsed)
        "display_$command" "$@"
        ;;
    link)
        print_link "$@"
        ;;
    *)
        display_error "Unknown UI command: ${command:-<none>}" \
            "Available: title, subtitle, success, error, warning, info, elapsed, link"
        exit 1
        ;;
    esac
fi
