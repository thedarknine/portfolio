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

    # Ignore commits that only apply coding standards for example
    git config blame.ignoreRevsFile .git-blame-ignore-revs

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

- logger
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
- phpro/grumphp
- doctrine/doctrine-fixtures-bundle
- phpstan/phpstan-doctrine
# - phpstan/phpstan-strict-rules
- symfony/test-pack
- dama/doctrine-test-bundle
- infection/infection (mutation testing)
- phparkitect/phparkitect (architecture validation)
- symfony/panther (ui testing)
- dbrekelmans/bdi (chrome driver)

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

## Tests

### Prepare database for tests

Add and adapt something like `DATABASE_URL="mysql://test:test@127.0.0.1:3306/portfolio_test?serverVersion=8.1.0&charset=utf8mb4"` into `.env.test` file.

```bash
    ## Access to container
    docker compose exec db mysql -p

    ## Create user and db for tests
    CREATE USER 'test'@'%' IDENTIFIED BY 'test';
    CREATE DATABASE portfolio_test;
    GRANT ALL PRIVILEGES ON portfolio_test.* TO 'test'@'%';
    FLUSH PRIVILEGES;

    ## Create schema
    php bin/console doctrine:schema:update --force --env=test

    ## Load data fixtures
    php bin/console doctrine:fixtures:load --env=test
```

### Build new test

```bash
    php bin/console make:test WebTestCase PageControllerTest    
```

### Run tests

```bash
    php bin/phpunit
```

### Run tests with coverage

```bash
    php bin/phpunit --coverage-html coverage
```

## Quality Assurance

### Run all QA checks

```bash
    make qa
```

### Deptrac

```bash
    composer require --dev qossmic/deptrac-shim
    vendor/bin/deptrac init
    vendor/bin/deptrac analyse --config-file=deptrac.yaml
    vendor/bin/deptrac debug:layer --config-file=.tools-config/deptrac.yaml
```

### Rector

```bash
    composer require --dev rector/rector
    vendor/bin/rector process --dry-run --config .tools-config/rector.php

```
