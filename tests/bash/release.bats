#!/usr/bin/env bats

setup() {
    # Loads the functions of release.sh without running a command
    source "$(dirname "$BATS_TEST_FILENAME")/../../.tools/scripts/release.sh"
}

# --- next_version ---
@test "next_version starts at 0 when the month has no release" {
    run next_version 2026 11 <<<$'v2026.7.0\nv2026.7.1'
    [ "$output" = "v2026.11.0" ]
}

@test "next_version increments the highest patch of the month" {
    run next_version 2026 11 <<<$'v2026.11.0\nv2026.11.3\nv2026.11.10\nv2026.11.2'
    [ "$output" = "v2026.11.11" ]
}

@test "next_version ignores other months, other years and other tags" {
    run next_version 2026 1 <<<$'v2026.11.4\nv2025.1.7\nv2026.1\nfoo\nv2026.01.3'
    [ "$output" = "v2026.1.0" ]
}

@test "next_version works without any tag" {
    run next_version 2026 7 </dev/null
    [ "$output" = "v2026.7.0" ]
}

# --- is_calver ---
@test "is_calver accepts valid versions" {
    is_calver v2026.7.0
    is_calver v2026.11.12
}

@test "is_calver rejects invalid versions" {
    ! is_calver 2026.11.0
    ! is_calver v2026.07.0
    ! is_calver v2026.13.0
    ! is_calver v2026.11
    ! is_calver v2026.11.01
}
