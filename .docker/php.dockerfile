# hadolint global ignore=DL3018
FROM php:8.4-fpm-alpine AS base

SHELL ["/bin/ash", "-o", "pipefail", "-c"]

RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    libxslt-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    libzip-dev \
    zip \
    unzip \
    jq \
    nodejs \
    npm \
    bash \
    make \
    shellcheck \
    shfmt \
    yamllint \
    chromium \
    chromium-chromedriver

# Install php extensions using mlocati/php-extension-installer
# This is the recommended tool to manage dependencies and extensions
# hadolint ignore=DL3022
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    pcov \
    pdo \
    pdo_mysql \
    gd \
    opcache \
    intl \
    dom \
    mbstring \
    xsl \
    exif \
    pcntl \
    bcmath \
    zip

# Install checkmake for Makefile QA
RUN curl -fL --progress-bar \
    "https://github.com/checkmake/checkmake/releases/download/v0.3.2/checkmake-v0.3.2.linux.arm64" \
    -o /usr/local/bin/checkmake \
    && chmod +x /usr/local/bin/checkmake

RUN curl -fL --progress-bar \
    "https://github.com/hadolint/hadolint/releases/download/v2.14.0/hadolint-linux-arm64" \
    -o /usr/local/bin/hadolint \
    && chmod +x /usr/local/bin/hadolint

RUN curl -sSL https://github.com/gitleaks/gitleaks/releases/download/v8.30.1/gitleaks_8.30.1_linux_arm64.tar.gz \
    | tar -xz -C /usr/local/bin gitleaks \
    && chmod +x /usr/local/bin/gitleaks

RUN mkdir -p /opt/siteone-crawler \
    && curl -fL "https://github.com/janreges/siteone-crawler/releases/download/v2.5.1/siteone-crawler-v2.5.1-linux-musl-arm64.tar.gz" \
       | tar -xz -C /opt \
    && chmod +x /opt/siteone-crawler/siteone-crawler \
    && ln -s /opt/siteone-crawler/siteone-crawler /usr/local/bin/siteone-crawler

# Install Symfony CLI and pnpm
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash \
    && apk add --no-cache symfony-cli

ARG PNPM_VERSION=11.17.0
RUN npm install -g pnpm@${PNPM_VERSION}

# Get Composer (official image frozen on a major version for stability)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Security: Never run container as root in production
USER www-data