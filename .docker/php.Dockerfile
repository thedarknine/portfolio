ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-fpm-alpine AS base

SHELL ["/bin/ash", "-o", "pipefail", "-c"]

# Install php extensions using mlocati/php-extension-installer
# This is the recommended tool to manage dependencies and extensions
COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

# Get Composer (official image frozen on a major version for stability)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# System packages, PHP extensions and QA tools (hadolint, gitleaks, pnpm).
# The same script is used by the GitLab CI job, so CI and local stay identical.
# TARGETARCH is set automatically by BuildKit (amd64 or arm64).
ARG TARGETARCH
COPY install-tools.sh /tmp/install-tools.sh
RUN sh /tmp/install-tools.sh && rm /tmp/install-tools.sh

# Install Symfony CLI
# Alpine repositories only keep the latest package version, so pinning would break builds
# hadolint ignore=DL3018
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash \
    && apk add --no-cache symfony-cli

# Custom PHP configuration
COPY php/php.ini "$PHP_INI_DIR/conf.d/zz-app.ini"

# Set working directory
WORKDIR /var/www/html

# Match the host user ids so the bind-mounted project is writable (Linux, CI).
# Defaults keep the official www-data ids (82), fine with Docker Desktop and in production.
# -o allows ids already used in the image (e.g. gid 20, the macOS staff group, is "dialout" in Alpine).
# usermod/groupmod come from the shadow package, installed by install-tools.sh.
ARG UID=82
ARG GID=82
RUN groupmod -o -g "$GID" www-data \
    && usermod -o -u "$UID" -g "$GID" www-data

# Security: Never run container as root in production
USER www-data
