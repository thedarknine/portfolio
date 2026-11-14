#!/usr/bin/env bash
# Production release to Hostinger, step by step. See docs/DEPLOY.md, "What happens at each step".
#
# Usage (through the Taskfile, which loads .env.local):
#   release.sh release [version]      check, build, backup, maintenance on, upload, finalize, maintenance off, smoke, tag
#   release.sh check [version]        checks, computes the version, asks for a confirmation
#   release.sh build [ref]            builds a release of ref (default: the checked commit, or HEAD)
#   release.sh backup                 database dump and .env.local, streamed to .tools/export/prod/
#   release.sh maintenance on|off
#   release.sh upload [dry]           rsync of the build to the server (dry: only list the changes)
#   release.sh finalize               migrations, cache, sitemap on the server
#   release.sh smoke [version]        public pages, hidden files, version online
#   release.sh tag                    git tag + GitHub release
#   release.sh rollback <version>     rebuilds and uploads a released version
#   release.sh status | shell | logs [lines]
#
# Works with the bash 3.2 shipped with macOS.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(pwd)"
# shellcheck source=.tools/scripts/utils.sh
source "$SCRIPT_DIR/utils.sh"

RELEASE_DIR="$PROJECT_ROOT/tmp/release"
STATE_FILE="$RELEASE_DIR/state.env"
BACKUP_ROOT="$PROJECT_ROOT/.tools/export/prod"
REMOTE_SCRIPT="$SCRIPT_DIR/release-remote.sh"
SITE_URL="${SITE_URL:-https://carolinenoyer.fr}"

# What the server receives: nothing else is ever sent
RELEASE_FILES=(bin/console config migrations public src templates translations vendor
    composer.json composer.lock importmap.php .env .htaccess)

# Never sent, never deleted on the server
PROTECTED_PATHS=(/.env.local /maintenance.flag /var/ /analytics/ /carolinenoyer_docs/ /carolinenoyer_medias/
    /.well-known/)

# Must answer 200, and must answer 403 or 404
PUBLIC_URLS=(/ /mentions-legales /sitemap.xml)
HIDDEN_URLS=(/.env /.env.local /composer.json /var/log/prod.log /vendor/autoload.php /REVISION)

# =====================================================================
# HELPERS
# =====================================================================

fail() {
    display_error "$@"
    exit 1
}

require_remote_config() {
    [ -n "${REMOTE_USER:-}" ] && [ -n "${REMOTE_HOST:-}" ] && [ -n "${REMOTE_PORT:-}" ] &&
        [ -n "${REMOTE_SSH_KEY:-}" ] ||
        fail "Missing remote configuration in .env.local" "Required: REMOTE_USER, REMOTE_HOST, REMOTE_PORT, REMOTE_SSH_KEY"
    # .env files are not expanded by a shell: turn a leading ~ into the home directory
    REMOTE_SSH_KEY="${REMOTE_SSH_KEY/#\~/$HOME}"
    [ -f "$REMOTE_SSH_KEY" ] || fail "SSH key not found: $REMOTE_SSH_KEY"
    REMOTE_PATH="${REMOTE_PATH:-/home/$REMOTE_USER/domains/carolinenoyer.fr/public_html}"
}

remote_ssh() {
    ssh -i "$REMOTE_SSH_KEY" -p "$REMOTE_PORT" "$REMOTE_USER@$REMOTE_HOST" "$@"
}

# Runs a command of release-remote.sh on the server
remote() {
    remote_ssh "bash -s -- '$REMOTE_PATH' $*" <"$REMOTE_SCRIPT"
}

state_get() {
    [ -f "$STATE_FILE" ] && grep -m1 "^$1=" "$STATE_FILE" | cut -d'=' -f2- || true
}

state_set() {
    mkdir -p "$RELEASE_DIR"
    touch "$STATE_FILE"
    grep -v "^$1=" "$STATE_FILE" >"$STATE_FILE.tmp" || true
    echo "$1=$2" >>"$STATE_FILE.tmp"
    mv "$STATE_FILE.tmp" "$STATE_FILE"
}

confirm() {
    local answer
    read -r -p "$1 [y/N] " answer </dev/tty
    [[ "$answer" == [yY] ]]
}

# Next CalVer version for a year and a month, from the existing tags read on stdin
# Usage: git tag --list | next_version 2026 11   ->   v2026.11.0, or v2026.11.<highest + 1>
next_version() {
    local year=$1 month=$2 patch=-1 tag
    while read -r tag; do
        if [[ "$tag" =~ ^v${year}\.${month}\.(0|[1-9][0-9]*)$ ]] && [ "${BASH_REMATCH[1]}" -gt "$patch" ]; then
            patch="${BASH_REMATCH[1]}"
        fi
    done
    echo "v${year}.${month}.$((patch + 1))"
}

is_calver() {
    [[ "$1" =~ ^v20[0-9]{2}\.([1-9]|1[0-2])\.(0|[1-9][0-9]*)$ ]]
}

# =====================================================================
# STEPS
# =====================================================================

cmd_check() {
    local requested="${1:-}" branch sha version
    display_subtitle "🔍 Checking the release"

    branch="$(git rev-parse --abbrev-ref HEAD)"
    [ "$branch" = "main" ] || fail "Releases start from main (current branch: $branch)" \
        "git checkout main && git pull origin main"
    [ -z "$(git status --porcelain)" ] || fail "The working copy has uncommitted or untracked changes"

    git fetch --quiet --tags origin main
    [ "$(git rev-parse HEAD)" = "$(git rev-parse origin/main)" ] ||
        fail "main differs from origin/main" "Pull or push first: git pull origin main"

    require_remote_config
    remote_ssh true || fail "The server does not answer over SSH"
    docker compose exec -T app true || fail "The app container is not running" "Start it with: task up"
    gh auth status >/dev/null 2>&1 || fail "The GitHub CLI is not logged in" "Run: gh auth login"

    version="$(git tag --list 'v*' | next_version "$(date +%Y)" "$((10#$(date +%m)))")"
    if [ -n "$requested" ]; then
        is_calver "$requested" || fail "$requested is not a CalVer version (vYYYY.M.PATCH, e.g. $version)"
        [ "$requested" = "$version" ] || fail "Expected version $version, not $requested"
    fi

    sha="$(git rev-parse HEAD)"
    display_info "Version: $version" "Commit:  $(git log -1 --format='%h %s' "$sha")" \
        "Online:  $(remote status | grep -m1 '^version=' | cut -d'=' -f2)"
    confirm "Release $version to production?" || fail "Release cancelled."

    rm -f "$STATE_FILE"
    state_set VERSION "$version"
    state_set SHA "$sha"
}

cmd_build() {
    local ref="${1:-}" sha dir version
    ref="${ref:-$(state_get SHA)}"
    sha="$(git rev-parse "${ref:-HEAD}^{commit}")"
    version="$(state_get VERSION)"
    dir="$RELEASE_DIR/$sha"
    display_subtitle "🏗️  Building ${version:-$sha}"

    rm -rf "$dir"
    mkdir -p "$dir/src" "$dir/dist"
    # Only committed files: nothing untracked or ignored can reach the server
    git archive "$sha" | tar -x -C "$dir/src"
    docker compose exec -T app sh .tools/scripts/build-prod.sh "tmp/release/$sha/build" "tmp/release/$sha/src" ||
        fail "The production build failed"

    local file
    for file in "${RELEASE_FILES[@]}"; do
        mkdir -p "$dir/dist/$(dirname "$file")"
        cp -Rp "$dir/build/$file" "$dir/dist/$file"
    done
    printf 'version=%s\ncommit=%s\nbuilt=%s\n' "${version:-unreleased}" "$sha" "$(date '+%Y-%m-%d %H:%M:%S')" \
        >"$dir/dist/REVISION"
    # Version shown in the footer (app.version): the server's .env.local does not set it
    printf '\nAPP_VERSION=%s\n' "${version:-unreleased}" >>"$dir/dist/.env"
    rm -rf "$dir/src" "$dir/build"

    state_set SHA "$sha"
    state_set DIST "$dir/dist"
    display_success "Release built in tmp/release/$sha/dist"
}

cmd_backup() {
    local dir
    require_remote_config
    dir="$BACKUP_ROOT/$(date +%Y%m%d-%H%M%S)-$(state_get VERSION)"
    display_subtitle "💾 Backing up the production database and .env.local"

    mkdir -p "$dir"
    chmod 700 "$dir"
    remote dump-db >"$dir/db.sql.gz" || fail "The database dump failed"
    gzip -t "$dir/db.sql.gz" 2>/dev/null && [ "$(gzip -dc "$dir/db.sql.gz" | head -c 100 | wc -c)" -gt 0 ] ||
        fail "The database dump is empty or corrupted: $dir/db.sql.gz"
    remote env >"$dir/env.local" || fail "Could not copy .env.local"
    chmod 600 "$dir/db.sql.gz" "$dir/env.local"

    state_set BACKUP "$dir"
    display_success "Backup saved to ${dir#"$PROJECT_ROOT"/}"
}

# Pauses or starts the UptimeRobot monitor (API v3), so the maintenance page does not raise a DOWN alert.
# Optional: needs UPTIME_API_KEY (main API key) and UPTIME_MONITOR_ID in .env.local. A failure only warns.
uptime_monitor() {
    local action=$1
    if [ -z "${UPTIME_API_KEY:-}" ] || [ -z "${UPTIME_MONITOR_ID:-}" ]; then
        display_warning "UptimeRobot monitor not ${action}ed: UPTIME_API_KEY or UPTIME_MONITOR_ID missing from .env.local"
        return 0
    fi
    if curl -fsS -o /dev/null -X POST -H "Authorization: Bearer $UPTIME_API_KEY" -H 'Accept: application/json' \
        -H 'Content-Type: application/json' -d '{}' \
        "https://api.uptimerobot.com/v3/monitors/$UPTIME_MONITOR_ID/$action"; then
        echo "   UptimeRobot monitor: $action"
    else
        display_warning "Could not $action the UptimeRobot monitor $UPTIME_MONITOR_ID" "Do it by hand on uptimerobot.com"
    fi
}

cmd_maintenance() {
    require_remote_config
    case "${1:-}" in
    on)
        display_subtitle "🚧 Maintenance on"
        uptime_monitor pause
        remote maintenance on
        ;;
    off)
        display_subtitle "✅ Maintenance off"
        remote maintenance off
        uptime_monitor start
        ;;
    *) fail "Usage: maintenance on|off" ;;
    esac
}

cmd_upload() {
    local dry="${1:-}" dist args=(-rlptz --delete) path
    require_remote_config
    dist="$(state_get DIST)"
    [ -n "$dist" ] && [ -d "$dist" ] || fail "No release built" "Run: task prod:build"
    for path in "${PROTECTED_PATHS[@]}"; do args+=(--exclude="$path"); done
    if [ -n "$dry" ]; then
        display_subtitle "👀 Upload dry run: nothing is changed on the server"
        args+=(--dry-run --verbose)
    else
        display_subtitle "📤 Uploading the release"
    fi

    rsync "${args[@]}" -e "ssh -i $REMOTE_SSH_KEY -p $REMOTE_PORT" \
        "$dist/" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/" || fail "The upload failed"
}

cmd_finalize() {
    require_remote_config
    display_subtitle "⚙️  Migrations, cache and sitemap"
    remote finalize || fail "The finalisation failed on the server"
}

cmd_smoke() {
    local expected="${1:-$(state_get VERSION)}" url code online errors=0
    require_remote_config
    display_subtitle "🔥 Smoke test"

    for url in "${PUBLIC_URLS[@]}"; do
        code="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL$url")"
        if [ "$code" = "200" ]; then echo "   $code  $url"; else echo "   $code  $url  ← expected 200" && errors=$((errors + 1)); fi
    done
    for url in "${HIDDEN_URLS[@]}"; do
        code="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL$url")"
        if [ "$code" = "403" ] || [ "$code" = "404" ]; then echo "   $code  $url"; else
            echo "   $code  $url  ← expected 403 or 404" && errors=$((errors + 1))
        fi
    done

    online="$(remote status | grep -m1 '^version=' | cut -d'=' -f2)"
    if [ -n "$expected" ] && [ "$online" != "$expected" ]; then
        echo "   Version online: $online  ← expected $expected" && errors=$((errors + 1))
    fi

    [ "$errors" -eq 0 ] || fail "Smoke test failed ($errors errors)"
    display_success "Smoke test passed" "Version online: $online"
}

cmd_tag() {
    local version sha
    version="$(state_get VERSION)"
    sha="$(state_get SHA)"
    [ -n "$version" ] && [ -n "$sha" ] || fail "No checked release" "Run: task prod:check"
    display_subtitle "🏷️  Tagging $version"

    git tag -a "$version" "$sha" -m "Release $version"
    git push origin "$version"
    gh release create "$version" --verify-tag --title "$version" --generate-notes
    display_success "$version tagged and released on GitHub"
}

# =====================================================================
# WORKFLOWS
# =====================================================================

# Set while the site is in maintenance. On an error the site is left in maintenance on purpose:
# a half-deployed site is worse than a maintenance page.
IN_MAINTENANCE=0

on_exit() {
    local status=$?
    if [ "$status" -ne 0 ] && [ "$IN_MAINTENANCE" -eq 1 ]; then
        display_error "The release stopped: the site is still in maintenance." \
            "Fix the problem, then run the failed step again, or roll back:" \
            "  task prod:rollback version=<previous version>" \
            "To serve the site again as it is: task prod:maintenance state=off"
    fi
}
trap on_exit EXIT

cmd_release() {
    local start_time
    start_time=$(date +%s)
    display_title "🚀 Production release"

    cmd_check "${1:-}"
    cmd_build
    cmd_backup
    IN_MAINTENANCE=1
    cmd_maintenance on
    cmd_upload
    cmd_finalize
    cmd_maintenance off
    IN_MAINTENANCE=0
    cmd_smoke
    cmd_tag

    display_elapsed "$start_time"
    display_success "🎉 $(state_get VERSION) is online"
}

cmd_rollback() {
    local version="${1:-}"
    [ -n "$version" ] || fail "Usage: task prod:rollback version=<version>" \
        "Releases: $(git tag --list 'v*' --sort=-creatordate | head -5 | tr '\n' ' ')"
    require_remote_config
    git fetch --quiet --tags origin
    git rev-parse --quiet --verify "refs/tags/$version" >/dev/null || fail "Unknown version: $version"

    display_title "⏪ Rolling back to $version"
    display_info "Online: $(remote status | grep -m1 '^version=' | cut -d'=' -f2)" \
        "Migrations are not reverted: restore a dump from .tools/export/prod/ if the schema changed."
    confirm "Roll back production to $version?" || fail "Rollback cancelled."

    rm -f "$STATE_FILE"
    state_set VERSION "$version"
    cmd_build "$version"
    IN_MAINTENANCE=1
    cmd_maintenance on
    cmd_upload
    cmd_finalize
    cmd_maintenance off
    IN_MAINTENANCE=0
    cmd_smoke "$version"
    display_success "Production is back to $version"
}

# =====================================================================
# MAIN (only when executed, not when sourced by the tests)
# =====================================================================

if [ "${BASH_SOURCE[0]}" = "$0" ]; then
    command="${1:-}"
    shift || true
    case "$command" in
    release | check | build | backup | maintenance | upload | finalize | smoke | tag | rollback)
        "cmd_$command" "$@"
        ;;
    status)
        require_remote_config
        remote status
        ;;
    shell)
        require_remote_config
        remote_ssh -t "cd '$REMOTE_PATH' && exec bash -l"
        ;;
    logs)
        require_remote_config
        remote logs "${1:-50}"
        ;;
    *)
        fail "Unknown release command: ${command:-<none>}"
        ;;
    esac
fi
