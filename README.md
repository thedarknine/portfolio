# portfolio
My Portfolio based on Symfony framework

## Before prod

```bash
    php bin/console asset-map:compile
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
- symfonycasts/tailwind-bundle

### Dev

- symfony/maker-bundle
- phpunit/phpunit
- friendsofphp/php-cs-fixer
- phpstan/phpstan
- vincentlanglet/twig-cs-fixer
- kocal/biome-js-bundle
