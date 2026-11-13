#!/usr/bin/env bash
# Server side of the release, streamed over SSH by release.sh, so the server never holds a stale copy:
#   ssh <server> 'bash -s -- <web root> <command> [args]' < .tools/scripts/release-remote.sh
#
# Commands:
#   dump-db                 Writes a gzip dump of the production database to stdout
#   env                     Writes the production .env.local to stdout
#   maintenance on|off      Creates or deletes maintenance.flag
#   finalize                Runs the migrations, warms up the cache and dumps the sitemap
#   status                  Writes the REVISION file (version, commit, date)
#   logs [lines]            Writes the end of var/log/prod.log
#
# Messages go to stderr: stdout only carries the data of dump-db, env, status and logs.
set -euo pipefail

WEB_ROOT="${1:?}" && shift
COMMAND="${1:-}" && shift || true

cd "$WEB_ROOT"

log() { printf '   %s\n' "$*" >&2; }

# Reads a variable from .env.local, without quotes
env_value() {
    grep -m1 "^$1=" .env.local | cut -d'=' -f2- | tr -d "\"'"
}

case "$COMMAND" in
dump-db)
    DB_NAME="$(env_value DB_NAME)"
    DB_USER="$(env_value DB_USER)"
    DB_PASS="$(env_value DB_PASSWORD)"
    # Host and port only exist in DATABASE_URL
    DB_HOST="127.0.0.1"
    DB_PORT="3306"
    if [[ "$(env_value DATABASE_URL)" =~ @([^:/]+):([0-9]+)/ ]]; then
        DB_HOST="${BASH_REMATCH[1]}"
        DB_PORT="${BASH_REMATCH[2]}"
    fi
    [ -n "$DB_NAME" ] && [ -n "$DB_USER" ] || {
        log "DB_NAME or DB_USER missing from .env.local"
        exit 1
    }
    MYSQL_PWD="$DB_PASS" mysqldump -u "$DB_USER" -h "$DB_HOST" -P "$DB_PORT" \
        --single-transaction --quick --routines --triggers "$DB_NAME" | gzip
    ;;
env)
    cat .env.local
    ;;
maintenance)
    case "${1:-}" in
    on) touch maintenance.flag && log "maintenance.flag created" ;;
    off) rm -f maintenance.flag && log "maintenance.flag deleted" ;;
    *) log "Usage: maintenance on|off" && exit 1 ;;
    esac
    ;;
finalize)
    export APP_ENV=prod APP_DEBUG=0
    log "Running the migrations"
    php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration >&2
    log "Clearing and warming up the cache"
    php bin/console cache:clear >&2
    log "Dumping the sitemap"
    php bin/console presta:sitemaps:dump >&2
    ;;
status)
    if [ -f REVISION ]; then cat REVISION; else echo "version=unknown"; fi
    ;;
logs)
    tail -n "${1:-50}" var/log/prod.log
    ;;
*)
    log "Unknown command: ${COMMAND:-<none>}"
    exit 1
    ;;
esac
