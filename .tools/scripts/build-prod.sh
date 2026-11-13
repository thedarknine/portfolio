#!/bin/sh
# Builds the project for production in a separate copy, so the working copy keeps its dev dependencies.
# Runs inside the app container.
#   - "task symfony:build-prod" (and the CI) checks the build of the working copy: build-prod.sh [build dir]
#   - "task prod:build" builds a release from a git export:              build-prod.sh <build dir> <source dir>
set -eu

BUILD_DIR="${1:-/tmp/prod-build}"
SOURCE_DIR="${2:-.}"

echo "📁 Copying $SOURCE_DIR to $BUILD_DIR"
rm -rf "$BUILD_DIR"
mkdir -p "$BUILD_DIR"
tar -c \
    --exclude=./.git \
    --exclude=./node_modules \
    --exclude=./public/assets \
    --exclude=./var/cache \
    --exclude=./var/log \
    --exclude=./vendor \
    -C "$SOURCE_DIR" . | tar -x -C "$BUILD_DIR"
cd "$BUILD_DIR"

export APP_ENV=prod
export APP_DEBUG=0
# Only used to build the cache: the real secret is set in the server environment
export APP_SECRET="${APP_SECRET:-prod-build-check}"

echo "📦 Installing the Composer dependencies without the dev ones"
composer install --no-dev --optimize-autoloader --classmap-authoritative --no-interaction --no-progress

echo "📥 Installing the importmap vendors"
php bin/console importmap:install

echo "🎨 Building the minified Tailwind CSS"
php bin/console tailwind:build --minify

echo "🗜️ Compiling the assets to public/assets"
php bin/console asset-map:compile

echo "🔥 Clearing and warming up the prod cache"
php bin/console cache:clear
