# portfolio
My Portfolio based on Symfony framework

## Before prod

```bash
    php bin/console asset-map:compile
```

## Update database

```bash
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
```

### Reset fixtures

```bash
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```

## Initialize

Into docker :

```bash
    git config --global user.email "you@example.com"
    git config --global user.name "Your Name"

    symfony check:requirements

    symfony new tmp --version="8.0.*"
    mv tmp/* . # Think about hidden files (possibly to be merged)

    php bin/console tailwind:init
    php bin/console importmap:require jquery
```

## Icons

- [IconStack](https://iconstack.io/)
- [HugeIcons](https://hugeicons.com/icons/stroke-rounded?search=inst)

## Packages used

### Standard

- twig
- symfony/twig-bundle
- symfony/asset
- symfony/asset-mapper
- symfony/twig-pack
- twig/intl-extra
- symfonycasts/tailwind-bundle
- symfony/orm-pack
- gedmo/doctrine-extensions
- nesbot/carbon
- doctrine/doctrine-migrations-bundle

### Dev

- symfony/maker-bundle
- phpunit/phpunit
- friendsofphp/php-cs-fixer
- phpstan/phpstan
- vincentlanglet/twig-cs-fixer
- kocal/biome-js-bundle
- doctrine/doctrine-fixtures-bundle
- phpstan/phpstan-doctrine

### Front-end librairies

```bash
    npm install animate.css --save
    php bin/console importmap:require animate.css

    npm install typed.js
    php bin/console importmap:require typed.js

    npm install photoswipe --save
    php bin/console importmap:require photoswipe
    php bin/console importmap:require photoswipe/lightbox
```

### Front-end linters

- ESLint → JS
- Stylelint → CSS/Tailwind
- Prettier → formatage global

```bash
    npm install -D eslint @eslint/js globals
    npm install -D eslint-config-prettier
    npm init @eslint/config
    npm install -D stylelint stylelint-config-standard stylelint-config-tailwindcss
    #npm install -D prettier prettier-plugin-tailwindcss prettier-plugin-twig-melody
    
```

### Admin

```bash
    composer require easycorp/easyadmin-bundle
    php bin/console make:admin:dashboard
    php bin/console make:admin:crud

    # Update security
    php bin/console make:user
    php bin/console make:security:form-login

    # Sortable fields
    composer require stof/doctrine-extensions-bundle
```
