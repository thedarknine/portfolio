#!/usr/bin/env bats

setup() {
    # Crée un dossier pour les mocks
    export BATS_MOCK_DIR="$BATS_TEST_DIRNAME/bin"
    mkdir -p "$BATS_MOCK_DIR"
    export PATH="$BATS_MOCK_DIR:$PATH"

    # Crée un dossier temporaire pour les tests
    export BATS_TEST_DIR="$BATS_TEST_DIRNAME/test_dir"
    mkdir -p "$BATS_TEST_DIR"

    # Mock docker par défaut (succès)
    cat > "$BATS_MOCK_DIR/docker" <<'EOF'
#!/bin/bash
if [[ "$*" == *"composer install"* ]]; then
    echo "Composer install succeeded"
    exit 0
elif [[ "$*" == *"cache:clear"* ]]; then
    echo "Cache cleared"
    exit 0
elif [[ "$*" == *"tailwind:build"* ]]; then
    echo "Assets regenerated"
    exit 0
else
    echo "Mocked docker command: $*"
    exit 0
fi
EOF
    chmod +x "$BATS_MOCK_DIR/docker"

    # Mock rm pour supprimer uniquement les fichiers/dossiers dans $BATS_TEST_DIR
    cat > "$BATS_MOCK_DIR/rm" <<EOF
#!/bin/bash
matched=false
for arg in "\$@"; do
    if [[ "\$arg" == "$BATS_TEST_DIR"* ]]; then
        /bin/rm -rf "\$arg"
        matched=true
    fi
done

if [[ "\$matched" == false ]]; then
    echo "Mocked rm command: \$*" >&2
fi
exit 0
EOF
    chmod +x "$BATS_MOCK_DIR/rm"

    # Mock .env
    echo "APP_ENV=dev" > "$BATS_TEST_DIR/.env"
    export PROJECT_ROOT="$BATS_TEST_DIR"

    # Charge les scripts
    source "$(dirname "$BATS_TEST_FILENAME")/../../.tools/scripts/utils.sh"
    source "$(dirname "$BATS_TEST_FILENAME")/../../.tools/scripts/dev.sh"
}

teardown() {
    rm -rf "$BATS_TEST_DIR"
}

# --- Tests pour dev.sh ---
@test "dev.sh restores dev dependencies successfully" {
    run bash .tools/scripts/dev.sh
    [[ "$output" == *"Dev dependencies restored."* ]]
    [[ "$output" == *"✅ Development environment restored!"* ]]
    echo "Status: $status"
    [ "$status" -eq 0 ]
}

@test "dev.sh fails if composer install fails" {
    # Remplace le mock docker pour simuler une erreur
    cat > "$BATS_MOCK_DIR/docker" <<'EOF'
#!/bin/bash
echo "Error: Docker command failed" >&2
exit 1
EOF
    chmod +x "$BATS_MOCK_DIR/docker"

    run bash .tools/scripts/dev.sh
    [[ "$output" == *"Failed to restore dev dependencies"* ]]
    [ "$status" -eq 1 ]
}

@test "dev.sh cleans dev cache successfully" {
    run bash .tools/scripts/dev.sh
    [[ "$output" == *"Dev cache cleaned."* ]]
    [ "$status" -eq 0 ]
}

@test "dev.sh regenerates assets successfully" {
    run bash .tools/scripts/dev.sh
    [[ "$output" == *"Assets regenerated for development."* ]]
    [ "$status" -eq 0 ]
}

@test "dev.sh cleans public assets successfully" {
    # Crée un dossier public/assets pour le test
    mkdir -p "$BATS_TEST_DIR/public/assets"
    touch "$BATS_TEST_DIR/public/assets/test.css"
    export PROJECT_ROOT="$BATS_TEST_DIR"

    run bash .tools/scripts/dev.sh

    [ "$status" -eq 0 ]
    [[ "$output" == *"Public assets cleaned."* ]]
    [ ! -e "$BATS_TEST_DIR/public/assets/test.css" ]
}

@test "dev.sh fails if public assets cleanup fails" {
    # Créer le dossier pour que rm soit vraiment appelé
    mkdir -p "$BATS_TEST_DIR/public/assets"
    touch "$BATS_TEST_DIR/public/assets/test.css"

    cat > "$BATS_MOCK_DIR/rm" <<EOF
#!/bin/bash
# Laisser passer les suppressions hors BATS_TEST_DIR (teardown, bats interne)
for arg in "\$@"; do
    if [[ "\$arg" == "$BATS_TEST_DIR/public"* ]] || [[ "\$arg" == "$BATS_TEST_DIR/var"* ]]; then
        echo "Error: Cannot remove assets" >&2
        exit 1
    fi
done
/bin/rm "\$@"
EOF
    chmod +x "$BATS_MOCK_DIR/rm"

    run env PATH="$BATS_MOCK_DIR:$PATH" bash .tools/scripts/dev.sh
    [[ "$output" == *"Failed to clean public assets"* ]]
    [ "$status" -eq 1 ]
}
