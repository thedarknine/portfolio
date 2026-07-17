# Guide de Mise à Jour Symfony

Ce document fournit des instructions détaillées pour mettre à jour le projet Portfolio vers une
version plus récente de Symfony.

## Table des matières

- [Mise à jour vers Symfony 8.1](#mise-à-jour-vers-symfony-81)
- [Vérification des dépendances](#vérification-des-dépendances)
- [Résolution des problèmes courants](#résolution-des-problèmes-courants)
- [Points d'attention](#points-dattention)

---

## Mise à jour vers Symfony 8.1

### Prérequis

- PHP >= 8.4
- Accès à Internet pour télécharger les dépendances
- Git pour gérer les branches
- La suite de tests doit être en état de réussite avant de commencer

### 1. Préparation

```bash
# Créer une branche dédiée à la migration
git checkout -b upgrade/symfony-8.1

# S'assurer que tout fonctionne actuellement
docker compose exec app composer install
make cc
make test
```

### 2. Mise à jour des dépendances

Mettre à jour le fichier composer.json en modifiant tous les packages Symfony `8.0.*` vers `8.1.*` dans la section "require".

```json
"require": {
    "php": ">=8.4",
    "ext-ctype": "*",
    "ext-iconv": "*",
    "symfony/asset": "8.1.*",
    "symfony/asset-mapper": "8.1.*",
    "symfony/console": "8.1.*",
    "symfony/dotenv": "8.1.*",
    "symfony/flex": "^2.11",
    "symfony/form": "8.1.*",
    "symfony/framework-bundle": "8.1.*",
    "symfony/mailer": "8.1.*",
    "symfony/monolog-bundle": "^4.0.2",
    "symfony/rate-limiter": "8.1.*",
    "symfony/runtime": "8.1.*",
    "symfony/twig-bundle": "8.1.*",
    "symfony/ux-icons": "^3.3",
    "symfony/validator": "8.1.*",
    "symfony/yaml": "8.1.*",
    ...
}
```

Et dans la section "require-dev" :

```json
"require-dev": {
    "symfony/browser-kit": "8.1.*",
    "symfony/css-selector": "8.1.*",
    "symfony/maker-bundle": "^1.67",
    "symfony/panther": "^2.4",
    ...
}
```

### 3. Mise à jour des dépendances

- Vérifier les incompatibilités (sans installer)
`docker compose exec app composer update --dry-run`

- Si tout semble bon, mettre à jour les packages Symfony
`docker compose exec app composer update "symfony/*"`

- Mettre à jour les autres dépendances si nécessaire
`docker compose exec app composer update`

### 4. Nettoyage du cache

```bash
docker compose exec app rm -rf var/cache/*
docker compose exec app php bin/console cache:clear
docker compose exec app php bin/console cache:warmup
```

### 5. Validation finale

```bash
# Vérifier que l'application fonctionne
docker compose exec app php bin/console debug:router

# Lancer les tests
make test
make test-ui

# Linters et coding standard
make cs

# Outils de qualité de code
make qa
```

## Vérification des dépendances

### Bundles critiques à vérifier

| Bundle | Version minimale | Raison |
| ------ | ---------------- | ------ |
| easycorp/easyadmin-bundle | >= 5.2.0 | Compatibilité Symfony 8.1 |
| symfonycasts/tailwind-bundle | >= 0.12.0 | Intégration CSS |
| stof/doctrine-extensions-bundle | >= 1.15.3 | ORM |
| gedmo/doctrine-extensions | >= 3.22 | Slugs, timestamps |
| nelmio/security-bundle | >= 3.9 | Sécurité |

### Vérifier la compatibilité d'un bundle

```bash
# Afficher les informations d'un package
composer show vendor/package

# Chercher les versions compatibles
composer search vendor/package | head -20
```

### Bundles avec versions wildcards

⚠️ Attention spéciale au bundle tito10047/altcha-bundle qui utilise * comme version.

```bash
# Vérifier la version installée
composer show tito10047/altcha-bundle

# Si problématique, fixer une version
composer require tito10047/altcha-bundle:^1.0
```

## Résolution des problèmes courants

### Erreur : 'Class not found' après mise à jour

**Cause** : Cache corrompu hérité de l'ancienne version

**Solution** :

```bash
docker compose exec app rm -rf var/cache/*
docker compose exec app php bin/console cache:clear
docker compose exec app php bin/console cache:warmup
```

### Erreur : Incompatibilité de bundle

**Cause** : Un bundle tiers n'est pas compatible avec la nouvelle version Symfony

**Solution** :

```bash
# Identifier le bundle problématique
docker compose exec app composer show | grep -i "bundle"

# Chercher une version compatible
docker compose exec app composer require vendor/bundle:^X.Y

# Ou supprimer et remplacer
docker compose exec app composer remove vendor/bundle
docker compose exec app composer require alternative/bundle
```

### Tests qui échouent après mise à jour

**Cause** : APIs ou comportements Symfony ont changé

**Solution** :

1. Consulter le UPGRADE guide officiel
2. Mettre à jour les assertions des tests
3. Adapter le code si des breaking changes sont appliqués

Exemple pour VarExporter et cache :

```bash
docker compose exec app rm -rf var/cache/test/*
docker compose exec app php bin/console cache:clear --env=test
make test
```

### Erreur Twig : "Class not found: Hydrator"

**Cause** : Cache Twig incompatible avec la nouvelle version Symfony

**Solution** :

```bash
docker compose exec app rm -rf var/cache/*/twig/*
make cc
```

### Tests UI échouent après mise à jour

**Cause** : Structure HTML peut avoir changé, sélecteurs CSS obsolètes

**Solution** :

1. Inspecter le HTML généré via le navigateur
2. Mettre à jour les sélecteurs CSS dans les tests
3. Ajouter des waits explicites si timing issues

```php
$webDriver->wait(5)->until(
    WebDriverExpectedCondition::visibilityOfElementLocated(
        WebDriverBy::cssSelector('.my-selector')
    )
);
```

## Points d'attention

✅ À vérifier impérativement

- [ ] PHP >= 8.4 installé
- [ ] Extensions ext-ctype et ext-iconv activées
- [ ] Tous les tests passent avant la migration
- [ ] Pas de bundles abandonnés avec * comme version
- [ ] Logs Symfony vérifiés pour les dépréciations
- [ ] Tests UI passent avec les nouveaux sélecteurs

⚠️ Configurations à adapter

- [ ] Configuration Doctrine si breaking changes en 8.1
- [ ] Assets et Asset Mapper si modifiés
- [ ] Services custom exploitant des APIs Symfony
- [ ] Fixtures de test si changements en Doctrine

🚫 À éviter

- ❌ Ne pas pousser sans tests verts
- ❌ Ne pas ignorer les warnings de dépréciations
- ❌ Ne pas mettre à jour plusieurs majors à la fois
- ❌ Ne pas mettre à jour directement en production sans test en staging
- ❌ Ne pas modifier composer.lock manuellement
