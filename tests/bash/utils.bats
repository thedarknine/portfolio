#!/usr/bin/env bats

setup() {
    # Charge les fonctions de utils.sh
    source "$(dirname "$BATS_TEST_FILENAME")/../../.tools/scripts/utils.sh"
}

# --- Tests pour display_box ---
@test "display_box outputs a box with label and lines" {
    run display_box "$BG_PURPLE" "$PURPLE" "Test Label" "Line 1" "Line 2"
    [[ "$output" == *"Test Label"* ]]
    [[ "$output" == *"Line 1"* ]]
    [[ "$output" == *"Line 2"* ]]
    [[ "$output" == *"┌"* ]]
    [[ "$output" == *"└"* ]]
}

@test "display_title outputs a purple box with label" {
    run display_title "Test Title"
    [[ "$output" == *"Test Title"* ]]
    [[ "$output" == *$'\033[45m'* ]]   # BG_PURPLE
    [[ "$output" == *$'\033[0;35m'* ]] # PURPLE
}

# --- Tests pour display_subtitle ---
@test "display_subtitle outputs a yellow subtitle" {
    run display_subtitle "Test Subtitle"
    [[ "$output" == *"→ Test Subtitle"* ]]
    [[ "$output" == *$'\033[43m'* ]]   # BG_YELLOW
    [[ "$output" == *$'\033[0;33m'* ]] # YELLOW
}

# --- Tests pour display_success ---
@test "display_success outputs a green success box" {
    run display_success "Task completed"
    [[ "$output" == *"✅ SUCCESS"* ]]
    [[ "$output" == *"Task completed"* ]]
    [[ "$output" == *$'\033[42m'* ]]   # BG_GREEN
}

# --- Tests pour display_error ---
@test "display_error outputs a red error box to stderr" {
    run display_error "Something went wrong"
    [[ "$output" == *"❌ ERROR"* ]]
    [[ "$output" == *"Something went wrong"* ]]
    [[ "$output" == *$'\033[41m'* ]]   # BG_RED
    [ "$status" -eq 0 ]
}

# --- Tests pour display_warning ---
@test "display_warning outputs a yellow warning box" {
    run display_warning "Be careful"
    [[ "$output" == *"⚠️ WARNING"* ]]
    [[ "$output" == *"Be careful"* ]]
    [[ "$output" == *$'\033[43m'* ]]   # BG_YELLOW
}

# --- Tests pour display_info ---
@test "display_info outputs a blue info box" {
    run display_info "Here is some info"
    [[ "$output" == *"ℹ️ INFO"* ]]
    [[ "$output" == *"Here is some info"* ]]
    [[ "$output" == *$'\033[44m'* ]]   # BG_BLUE
}

# --- Tests pour display_elapsed ---
@test "display_elapsed outputs the duration in minutes and seconds" {
    start_time=$(date +%s)
    sleep 2
    run display_elapsed "$start_time"
    [[ "$output" == *"Duration: 0m 2s"* ]]
    [[ "$output" == *$'\033[46m'* ]]   # BG_CYAN
}