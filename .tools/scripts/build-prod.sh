#!/bin/sh
# Checks that the project builds for production, in a temporary copy so the working copy keeps
# its dev dependencies. Called by "task symfony:build-prod", inside the app container.
# Deployment itself goes through "task prod:release" (prod-build.sh), which runs the same build steps.
set -eu

BUILD_DIR="${1:-/tmp/prod-build}"

echo "📁 Copying the project to $BUILD_DIR"
rm -rf "$BUILD_DIR"
mkdir -p "$BUILD_DIR"
tar -c \
    --exclude=./.git \
    --exclude=./node_modules \
    --exclude=./public/assets \
    --exclude=./var/cache \
    --exclude=./var/log \
    --exclude=./vendor \
    . | tar -x -C "$BUILD_DIR"
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
