#!/usr/bin/env bash
# Pretty help for the Taskfile.
# Sections = include namespaces (docker:up -> "Docker"), root tasks -> "General".
# Text in parentheses at the end of a desc is shown as options (purple).
set -euo pipefail

PROJECT_NAME="${PROJECT_NAME:-Project}"
APP_ENV="${APP_ENV:-dev}"
TW="${TARGET_MAX_CHAR_NUM:-24}"
DW="${DESC_MAX_CHAR_NUM:-45}"

# Colors (BOLD, RESET, YELLOW...) come from the shared output library
# shellcheck source-path=SCRIPTDIR source=utils.sh
source "$(dirname "$0")/utils.sh"

printf '\n'
printf '  %s%s%s%s %s(%s%s%s)%s\n' "$BOLD" "$YELLOW" "$PROJECT_NAME" "$RESET" "$BLUE" "$GREEN" "$APP_ENV" "$BLUE" "$RESET"
printf '  %s───────────────────────────────────────────────────────────────────────────────%s\n\n' "$BLUE" "$RESET"
printf '  %sUsage:%s\n' "$BOLD" "$RESET"
printf '    %stask%s %s<task>%s %s[-- args]%s\n\n' "$YELLOW" "$RESET" "$GREEN" "$RESET" "$PURPLE" "$RESET"
printf '  %sAvailable tasks:%s\n' "$BOLD" "$RESET"

task --list --sort none 2>/dev/null | awk \
    -v yellow="$YELLOW" -v cyan="$CYAN" \
    -v purple="$PURPLE" -v reset="$RESET" \
    -v tw="$TW" -v dw="$DW" '
  /^\* / {
    line = substr($0, 3)
    if (!match(line, /^[^ ]+:[ \t]+/)) next
    head = substr(line, 1, RLENGTH); desc = substr(line, RLENGTH + 1)
    sub(/:[ \t]+$/, "", head); name = head
    sub(/[ \t]+\(aliases:.*\)$/, "", desc)
    gsub(/[ \t]+$/, "", desc)

    # Section from namespace
    ns = "General"
    if (index(name, ":") > 0) { ns = substr(name, 1, index(name, ":") - 1); ns = toupper(substr(ns,1,1)) substr(ns,2) }
    if (ns != current) { printf "\n%s  %s%s\n", cyan, ns, reset; current = ns }

    # Trailing "(...)" -> options
    options = ""
    if (match(desc, /\([^()]*\)$/)) {
      options = purple substr(desc, RSTART + 1, RLENGTH - 2) reset
      desc = substr(desc, 1, RSTART - 1); gsub(/[ \t]+$/, "", desc)
    }
    printf "    %s%-" tw "s%s  %-" dw "s  %s\n", yellow, name, reset, desc, options
  }'
printf '\n'
