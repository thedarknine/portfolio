FROM php:8.2-fpm

# Install system dependencies
RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libxslt-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    zip \
    unzip \
    nodejs npm

# Clear cache
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN set -ex; \
    docker-php-ext-install pdo; \
    docker-php-ext-install pdo_mysql; \
    docker-php-ext-install gd; \
    docker-php-ext-install opcache; \
    docker-php-ext-install intl; \
    docker-php-ext-install dom; \
    docker-php-ext-install mbstring; \
    docker-php-ext-install xsl; \
    docker-php-ext-install exif; \
    docker-php-ext-install pcntl; \
    docker-php-ext-install bcmath

# Install Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt update && apt install -y symfony-cli

RUN npm install -g pnpm

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html
