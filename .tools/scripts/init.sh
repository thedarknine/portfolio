#!/usr/bin/env bash
# Interactive project setup, called by "task init".
# Writes the project name, slug and host ports in .env, and the host user ids in .env.local on Linux.
# Runs on the host (macOS ships bash 3.2): no bash 4 features.
set -euo pipefail

# shellcheck source-path=SCRIPTDIR source=utils.sh
source "$(dirname "${BASH_SOURCE[0]}")/utils.sh"

ENV_FILE=.env
ENV_LOCAL_FILE=.env.local

# Value of KEY in .env, without surrounding quotes
get_value() {
    grep -E "^$1=" "$ENV_FILE" | tail -n 1 | cut -d '=' -f 2- | sed -e 's/^"//' -e 's/"$//'
}

# Replace KEY=... in FILE, or append it (the file is rewritten in place to keep its permissions)
set_value() {
    local key="$1" value="$2" file="$3" tmp
    touch "$file"
    if grep -qE "^$key=" "$file"; then
        tmp=$(mktemp)
        awk -v key="$key" -v line="$key=$value" 'index($0, key "=") == 1 { print line; next } { print }' "$file" >"$tmp"
        cat "$tmp" >"$file"
        rm -f "$tmp"
    else
        printf '%s=%s\n' "$key" "$value" >>"$file"
    fi
}

# Prompt with a default value, the answer is printed on stdout
ask() {
    local answer
    read -r -p "  $1 [$2]: " answer
    printf '%s' "${answer:-$2}"
}

slugify() {
    printf '%s' "$1" | tr '[:upper:]' '[:lower:]' | sed -e 's/[^a-z0-9]/-/g' -e 's/--*/-/g' -e 's/^-//' -e 's/-$//'
}

is_port() {
    case "$1" in
    '' | *[!0-9]*) return 1 ;;
    esac
    [ "$1" -ge 1 ] && [ "$1" -le 65535 ]
}

display_title "🚀 Project setup"

name=$(get_value PROJECT_NAME)
slug=$(get_value PROJECT_SLUG)
app_port=$(get_value APP_PORT)
db_port=$(get_value DB_PORT)
adm_port=$(get_value ADM_PORT)
mail_port=$(get_value MAIL_PORT)

if [ -t 0 ]; then
    display_info "Press Enter to keep the value in brackets."
    printf '\n'

    # Double quotes would break the .env value
    name=$(ask "Project name" "$name" | tr -d '"')

    # The default slug follows the name, unless the slug was already customized
    default_slug="$slug"
    if [ "$slug" = "project" ]; then
        default_slug=$(slugify "$name")
    fi
    while :; do
        slug=$(ask "Project slug - lowercase letters, digits and dashes" "$default_slug")
        if printf '%s' "$slug" | grep -qE '^[a-z0-9]([a-z0-9-]*[a-z0-9])?$'; then
            break
        fi
        display_warning "Invalid slug: use lowercase letters, digits and dashes, e.g. $(slugify "$slug")"
    done

    for var in app_port db_port adm_port mail_port; do
        case "$var" in
        app_port) label="Application port" ;;
        db_port) label="MySQL port" ;;
        adm_port) label="AdminNeo port" ;;
        mail_port) label="Mailpit port" ;;
        esac
        while :; do
            value=$(ask "$label" "${!var}")
            if is_port "$value"; then
                printf -v "$var" '%s' "$value"
                break
            fi
            display_warning "Invalid port: use a number between 1 and 65535."
        done
    done
else
    display_info "No terminal: keeping the current values of $ENV_FILE."
fi

set_value PROJECT_NAME "\"$name\"" "$ENV_FILE"
set_value PROJECT_SLUG "$slug" "$ENV_FILE"
set_value APP_PORT "$app_port" "$ENV_FILE"
set_value DB_PORT "$db_port" "$ENV_FILE"
set_value ADM_PORT "$adm_port" "$ENV_FILE"
set_value MAIL_PORT "$mail_port" "$ENV_FILE"

summary=(
    "Project: $name ($slug)"
    "Ports: application $app_port, MySQL $db_port, AdminNeo $adm_port, Mailpit $mail_port"
)

# Docker Desktop maps the file ownership, Linux needs the host ids in the image (see php.Dockerfile)
if [ "$(uname -s)" = "Linux" ]; then
    set_value HOST_UID "$(id -u)" "$ENV_LOCAL_FILE"
    set_value HOST_GID "$(id -g)" "$ENV_LOCAL_FILE"
    summary+=("Host user ids saved in $ENV_LOCAL_FILE: $(id -u):$(id -g)")
fi

display_success "${summary[@]}"
