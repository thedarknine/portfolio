#!/bin/sh
# Install the system packages, PHP extensions and QA tools of the dev image.
# Shared by .docker/php.Dockerfile and the GitLab CI job so both stay identical.
# Requirements: Alpine, root, install-php-extensions in PATH.
set -eu

HADOLINT_VERSION="${HADOLINT_VERSION:-2.15.1}"
GITLEAKS_VERSION="${GITLEAKS_VERSION:-8.30.1}"
PNPM_VERSION="${PNPM_VERSION:-12.16.1}"
CHECKMAKE_VERSION="${CHECKMAKE_VERSION:-0.3.2}"
SITEONE_VERSION="${SITEONE_VERSION:-2.5.1}"

# Portfolio: chromium + chromedriver for pa11y, unlighthouse and Panther UI tests.
# make (and checkmake below) only while the Makefile exists (removed at migration step 4).
PACKAGES="
    git
    curl
    libpng-dev
    oniguruma-dev
    libxml2-dev
    libxslt-dev
    libjpeg-turbo-dev
    freetype-dev
    icu-dev
    libzip-dev
    zip
    unzip
    jq
    nodejs
    npm
    bash
    shellcheck
    shfmt
    yamllint
    shadow
    chromium
    chromium-chromedriver
    make
"

# Not listed: pdo, opcache, dom, mbstring are already built into the official php image
PHP_EXTENSIONS="
    pcov
    pdo_mysql
    gd
    intl
    xsl
    exif
    pcntl
    bcmath
    zip
"

# shellcheck disable=SC2086 # word splitting is intended for the lists
apk add --no-cache $PACKAGES
# shellcheck disable=SC2086
install-php-extensions $PHP_EXTENSIONS

# TARGETARCH is set by BuildKit; outside a Docker build, derive it from the machine.
ARCH="${TARGETARCH:-}"
if [ -z "$ARCH" ]; then
    case "$(uname -m)" in
    x86_64) ARCH=amd64 ;;
    aarch64 | arm64) ARCH=arm64 ;;
    *) ARCH="$(uname -m)" ;;
    esac
fi

case "$ARCH" in
amd64)
    HADOLINT_ARCH=x86_64
    GITLEAKS_ARCH=x64
    SITEONE_ARCH=x64
    ;;
arm64)
    HADOLINT_ARCH=arm64
    GITLEAKS_ARCH=arm64
    SITEONE_ARCH=arm64
    ;;
*)
    echo "Unsupported architecture: $ARCH" >&2
    exit 1
    ;;
esac

GITHUB=https://github.com
curl -fsSL -o /usr/local/bin/hadolint "$GITHUB/hadolint/hadolint/releases/download/v$HADOLINT_VERSION/hadolint-linux-$HADOLINT_ARCH"
curl -fsSL "$GITHUB/gitleaks/gitleaks/releases/download/v$GITLEAKS_VERSION/gitleaks_${GITLEAKS_VERSION}_linux_$GITLEAKS_ARCH.tar.gz" |
    tar -xz -C /usr/local/bin gitleaks
chmod +x /usr/local/bin/hadolint /usr/local/bin/gitleaks

# Portfolio-only tools
curl -fsSL -o /usr/local/bin/checkmake "$GITHUB/checkmake/checkmake/releases/download/v$CHECKMAKE_VERSION/checkmake-v$CHECKMAKE_VERSION.linux.$ARCH"
chmod +x /usr/local/bin/checkmake
curl -fsSL "$GITHUB/janreges/siteone-crawler/releases/download/v$SITEONE_VERSION/siteone-crawler-v$SITEONE_VERSION-linux-musl-$SITEONE_ARCH.tar.gz" |
    tar -xz -C /opt
chmod +x /opt/siteone-crawler/siteone-crawler
ln -sf /opt/siteone-crawler/siteone-crawler /usr/local/bin/siteone-crawler

npm install -g "pnpm@$PNPM_VERSION"
