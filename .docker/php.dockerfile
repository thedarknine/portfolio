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
    jq \
    nodejs \
    npm \
    bash \
    make \
    shellcheck \
    shfmt \
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

# Install SiteOne Crawler (musl variant for Alpine)
RUN mkdir -p /opt/siteone-crawler \
    && wget -qO- https://github.com/janreges/siteone-crawler/releases/download/v2.5.1/siteone-crawler-v2.5.1-linux-musl-arm64.tar.gz \
       | tar -xz -C /opt \
    && chmod +x /opt/siteone-crawler/siteone-crawler \
    && ln -s /opt/siteone-crawler/siteone-crawler /usr/local/bin/siteone-crawler

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