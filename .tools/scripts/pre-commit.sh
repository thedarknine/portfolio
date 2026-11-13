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

twig_files=$(git diff --cached --name-only --diff-filter=ACMR -- 'templates/*.twig')
if [ -n "$twig_files" ]; then
    echo "🌿 ESLint: checking the HTML of staged Twig templates"
    # shellcheck disable=SC2086 # word splitting is wanted: one argument per file
    pnpm exec eslint --config "$CONFIG_DIR/eslint.config.mjs" $twig_files
fi

md_files=$(git diff --cached --name-only --diff-filter=ACMR -- '*.md')
if [ -n "$md_files" ]; then
    echo "📝 markdownlint: checking staged Markdown files"
    # shellcheck disable=SC2086 # word splitting is wanted: one argument per file
    pnpm exec markdownlint-cli2 --config "$CONFIG_DIR/markdownlint-cli2.yaml" $md_files
fi
