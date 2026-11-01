#!/bin/sh
# Pre-commit checks that GrumPHP has no native task for, run on the staged files only.
# Called by the "shell" task of .tools/config/grumphp.yml, inside the app container.
set -e

CONFIG_DIR=.tools/config

echo "🔐 gitleaks: scanning staged changes for secrets"
gitleaks git --pre-commit --staged --redact --no-banner -c "$CONFIG_DIR/gitleaks.toml" .

echo "🎨 Biome: checking staged JS and CSS assets"
pnpm exec biome check --config-path="$CONFIG_DIR/biome.json" --staged --no-errors-on-unmatched

yaml_files=$(git diff --cached --name-only --diff-filter=ACMR -- '*.yml' '*.yaml')
if [ -n "$yaml_files" ]; then
    echo "📄 yamllint: checking staged YAML files"
    # shellcheck disable=SC2086 # word splitting is wanted: one argument per file
    yamllint -c "$CONFIG_DIR/yamllint.yaml" $yaml_files
fi
