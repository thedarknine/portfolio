# Update guide

How to keep the dependencies up to date and upgrade Symfony to a new minor or major version.

- [Routine updates](#routine-updates)
- [Upgrading Symfony](#upgrading-symfony)
- [Checking the dependencies](#checking-the-dependencies)
- [Common problems](#common-problems)
- [Checklist](#checklist)

## Routine updates

Dependabot opens weekly pull requests on `develop` for the GitHub Actions, Composer and pnpm
dependencies. To update everything locally at once:

```bash
git switch develop && git pull && git switch -c cno_update
task update   # pulls the Docker images, rebuilds the containers, composer update, pnpm update
task qa deep=1
```

To upgrade MariaDB, change `MARIADB_VERSION` in `.env` (and the `serverVersion` of `DATABASE_URL`)
only when Hostinger upgrades the production server: dev and prod must run the same version.

## Upgrading Symfony

The example below upgrades from 8.0 to 8.1.

### Requirements

- PHP version required by the new Symfony version (`PHP_VERSION` in `.env`, then `task docker:build`)
- The whole test suite passes before starting

### 1. Prepare

```bash
git switch develop && git pull && git switch -c cno_symfony-8.1
task symfony:install
task cc
task qa deep=1
```

### 2. Update the constraints

In `composer.json`, change every Symfony package from `8.0.*` to `8.1.*` in `require` and
`require-dev`, and `extra.symfony.require` to `8.1.*`.

### 3. Update the dependencies

```bash
# Check the incompatibilities without installing anything
task symfony:composer -- update --dry-run

# Update the Symfony packages, then the others if needed
task symfony:composer -- update "symfony/*" --with-all-dependencies
task symfony:composer -- update

# Apply the recipe updates, one by one, and review each diff
task symfony:composer -- recipes:update
```

### 4. Clear the cache

```bash
task clean:cache
task cc
```

### 5. Check everything

```bash
task symfony:console -- debug:router
task qa deep=1
task symfony:build-prod
```

Read `var/log/dev.log` and the deprecations in the web profiler, then apply the Rector upgrade
sets (`task quality:rector`, then `fix=1` if the changes are right).

## Checking the dependencies

### Bundles to watch

| Bundle                          | Why                                  |
| ------------------------------- | ------------------------------------ |
| easycorp/easyadmin-bundle       | Back office, follows Symfony closely |
| symfonycasts/tailwind-bundle    | CSS build                            |
| stof/doctrine-extensions-bundle | Sluggable, timestampable, sortable   |
| gedmo/doctrine-extensions       | Same                                 |
| nelmio/security-bundle          | Security headers and CSP             |
| tito10047/altcha-bundle         | Contact form anti-spam               |
| symfony/panther                 | UI tests                             |

### Check a bundle

```bash
# Installed version and requirements
task symfony:composer -- show vendor/package

# Why a package blocks the update
task symfony:composer -- why-not symfony/framework-bundle 8.1.*
```

## Common problems

### "Class not found" after the update

The cache was built by the previous version:

```bash
task clean:cache
task cc
```

### Incompatible bundle

A third-party bundle does not support the new Symfony version yet: find the blocking package
with `why-not`, require a compatible version, or wait and keep the previous Symfony version.

```bash
task symfony:composer -- require vendor/bundle:^X.Y
```

### Failing tests

An API or a behavior changed. Read the `UPGRADE-8.x.md` of the Symfony repository, adapt the code
or the assertions, then regenerate the test database:

```bash
task test:prepare
task test
```

### Failing UI tests

The HTML may have changed: inspect the page, update the CSS selectors, and wait explicitly for
the elements that appear late:

```php
$client->waitForVisibility('.my-selector', 5);
```

## Checklist

Before merging:

- [ ] `task qa deep=1` passes
- [ ] No new deprecation in the logs
- [ ] `task symfony:build-prod` passes
- [ ] The CI is green on the pull request

To avoid:

- Pushing without green tests
- Ignoring the deprecations
- Upgrading several major versions at once
- Editing `composer.lock` by hand
