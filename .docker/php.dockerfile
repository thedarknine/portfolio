FROM php:8.4-fpm-alpine AS base

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
    nodejs \
    npm \
    bash \
    make \
    chromium \
    chromium-chromedriver

# Install php extensions using mlocati/php-extension-installer
# This is the recommended tool to manage dependencies and extensions
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
RUN curl -L "https://github.com/checkmake/checkmake/releases/download/v0.3.2/checkmake-v0.3.2.linux.arm64" -o /usr/local/bin/checkmake \
    && chmod +x /usr/local/bin/checkmake

# Install Symfony CLI and pnpm
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash \
    && apk add --no-cache symfony-cli \
    && npm install -g pnpm@latest

# Get Composer (official image frozen on a major version for stability)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Security: Never run container as root in production
USER www-data