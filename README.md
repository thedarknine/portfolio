# Caroline Noyer - Portfolio & Lab

Source code of my personal website and portfolio, [carolinenoyer.fr](https://carolinenoyer.fr).

More than a showcase, it is my technical lab: the place where I apply and test full-stack practices,
from the backend architecture to the design and the code quality automation. It is built on my
[symfony-docker-template](https://github.com/thedarknine/symfony-docker-template) (Docker, Taskfile,
QA tools, git hooks, CI), extended with the tools and the deployment specific to this site.

## Stack

- **Symfony 8.1** on **PHP 8.4** (FPM, Alpine)
- **Twig**, **Symfony UX** Icons, **AssetMapper** and **Tailwind CSS v4** (standalone binary)
- **Doctrine ORM 3** and Migrations on **MariaDB 11.8**, the production engine and version
- **EasyAdmin 5** back office, secured with a login form, Google Authenticator 2FA and a rate limiter
- **Altcha** anti-spam on the contact form, **Symfony Mailer** caught by **Mailpit** in development
- **Gedmo** extensions (sluggable, timestampable, sortable), **Carbon**, **Presta Sitemap**
- **NelmioSecurityBundle** (CSP and security headers)
- Front libraries through the importmap: animate.css, typed.js, PhotoSwipe

| Service   | Container           | Image                           | Host port     | Environment |
| --------- | ------------------- | ------------------------------- | ------------- | ----------- |
| `app`     | `portfolio-app`     | PHP 8.4 FPM Alpine (custom)     | -             | all         |
| `engine`  | `portfolio-server`  | `nginx:1.30-alpine`             | `APP_PORT`    | all         |
| `db`      | `portfolio-db`      | `mariadb:11.8`                  | `DB_PORT`     | dev         |
| `adm`     | `portfolio-adm`     | `adminneoorg/adminneo`          | `ADM_PORT`    | dev         |
| `mailpit` | `portfolio-mailpit` | `axllent/mailpit`               | `MAIL_PORT`   | dev         |
| `chrome`  | `portfolio-chrome`  | `seleniarm/standalone-chromium` | -             | dev         |

The MariaDB data is stored in `.docker/mysql/data` and its logs in `.docker/volumes/logs/mysql`
(both git-ignored).

## Requirements

- Docker with the Compose plugin (`docker compose`)
- [Task](https://taskfile.dev/installation/) (`brew install go-task` on macOS)
- `jq` and `lsof`, used by the health checks (`brew install jq lsof` on macOS)
- Optional: [Bats](https://github.com/bats-core/bats-core) for `task test:bash`, Node.js for `task utils:seo-audit`

## Getting started

```bash
git clone git@github.com:thedarknine/portfolio.git
cd portfolio
task init
```

`task init` asks for the project name, slug and host ports (Enter keeps the defaults), then
builds and starts the containers, installs the dependencies, creates the database and checks
the project health. It can be run again at any time.

To start from real content, import a dump of the production database (see `task db:dump` and
the AdminNeo import), or load the fixtures with `task db:fixtures`.

To log in to the back office, create an administrator (it asks for the email and the password):

```bash
task symfony:console -- app:create-user
```

- Website: <http://localhost:8000>, back office: <http://localhost:8000/backstage>
- AdminNeo (database UI): <http://localhost:8089> (server `db`, credentials from `.env`)
- Mailpit (emails sent by the site): <http://localhost:8025>

Run `task` to list all the available tasks.

## Configuration

`.env` holds the defaults and is committed. Local values and **every secret** go in `.env.local`,
which is git-ignored and must never be committed:

| Variable                                      | Used for                                                     |
| --------------------------------------------- | ------------------------------------------------------------ |
| `APP_SECRET`                                  | Symfony secret (`task utils:secret` generates one)           |
| `EASYADMIN_USER` / `EASYADMIN_PASSWORD`       | Back office account                                          |
| `ALTCHA_SECRET`                               | Altcha anti-spam challenge                                   |
| `MAILER_DSN`                                  | Production mailer                                            |
| `DATABASE_URL`                                | Production database (Hostinger MariaDB)                      |
| `REMOTE_HOST` / `REMOTE_USER` / `REMOTE_PORT` | Production server, used by `task prod:deploy`                |
| `REMOTE_SSH_KEY`                              | Path of the SSH key for the production server                |
| `HOST_UID` / `HOST_GID`                       | Linux only: ids of the `app` user (then `task docker:build`) |

The other variables of `.env` (`PROJECT_SLUG`, ports, image versions, `DB_*`) are documented
in the [template README](https://github.com/thedarknine/symfony-docker-template#configuration).

## Project structure

```bash
.docker/
├── mysql/               # MariaDB config, data (git-ignored)
├── nginx/default.conf   # Nginx virtual host
├── php/php.ini          # PHP settings
├── install-tools.sh     # System packages, PHP extensions and QA tools of the image
└── php.Dockerfile       # PHP-FPM image
.github/                 # CI workflow and Dependabot
.tools/
├── cache/               # Caches of the QA tools (git-ignored)
├── config/              # Configuration of every QA tool
├── export/              # Database dumps (git-ignored)
├── reports/             # SEO and accessibility audit reports (git-ignored)
├── scripts/             # Init, help, pre-commit, build, deployment and audit scripts
├── tasks/               # Task domains included by Taskfile.yml
└── deploy.sh-prod       # Deployment script run on the production server
assets/                  # JS, CSS (Tailwind) and locked icons, served by AssetMapper
docs/                    # Style guide, deployment guide and migration notes
src/                     # Application code (DataFixtures/Testing is generated)
templates/               # Twig templates
tests/                   # Unit/, Integration/, UI/ (PHPUnit) and bash/ (Bats)
AGENTS.md                # Conventions for AI coding agents
UPDATE.md                # How to update the dependencies and Symfony
```

## Tasks

`Taskfile.yml` wraps every command, run in the `app` container. Arguments after `--` go to the
underlying command (`task symfony:console -- make:entity`). Tasks marked `fix=1` only check by
default and apply the fixes with `fix=1`.

| Domain     | Tasks                                                                                                |
| ---------- | ---------------------------------------------------------------------------------------------------- |
| Shortcuts  | `init`, `up`, `down`, `restart`, `cc`, `test`, `health`, `qa`, `update`                              |
| `docker:`  | `build`, `up`, `down`, `restart`, `reset`, `ps`, `logs`, `shell`                                     |
| `symfony:` | `install`, `build-prod`, `composer`, `pnpm`, `console`, `cc`, `tailwind`, `watch`, `icons`           |
| `db:`      | `create`, `diff`, `migrate`, `reset`, `fixtures`, `dump` (`clean=1`)                                 |
| `test:`    | `prepare`, `all`, `unit`, `integration`, `ui`, `coverage`, `mutation`, `arch`, `bash`                |
| `cs:`      | `all`, `php`, `twig`, `front` (`fix=1`)                                                              |
| `lint:`    | `all`, `yaml`, `markdown` (`fix=1`), `shell`, `docker`, `secrets`                                    |
| `quality:` | `all`, `phpstan`, `deptrac`, `rector` (`fix=1`), `audit`, `grumphp`, `hooks`                         |
| `check:`   | `health`, `tools`, `docker`, `env`, `directories`, `ports`, `containers`, `dependencies`, `database` |
| `clean:`   | `all`, `build`, `cache`, `test`, `composer`, `pnpm`                                                  |
| `utils:`   | `secret`, `seo-audit` (`target=dev\|prod`), `prune`                                                  |
| `prod:`    | `release`, `build`, `deploy`, `dev`                                                                  |

## Code quality and tests

| Tool                                                | Task                            | Checks                                                             |
| --------------------------------------------------- | ------------------------------- | ------------------------------------------------------------------ |
| PHP CS Fixer, Twig CS Fixer                         | `cs:php`, `cs:twig`             | PHP and Twig coding standards                                      |
| Biome                                               | `cs:front`                      | JS and CSS                                                         |
| yamllint, markdownlint, shellcheck, shfmt, hadolint | `lint:*`                        | YAML, Markdown, shell scripts, Dockerfile                          |
| gitleaks                                            | `lint:secrets`                  | Secrets in the git history                                         |
| PHPStan (level 6)                                   | `quality:phpstan`               | Static analysis, with the Symfony, Doctrine and PHPUnit extensions |
| Deptrac                                             | `quality:deptrac`               | Dependencies between the layers                                    |
| Rector                                              | `quality:rector`                | PHP, Symfony, Doctrine and PHPUnit upgrades                        |
| `composer audit`, `pnpm audit`                      | `quality:audit`                 | Known vulnerabilities in the dependencies                          |
| PHPUnit                                             | `test:unit`, `test:integration` | Unit tests, kernel and database tests                              |
| PHPUnit + Panther                                   | `test:ui`                       | Navigation in a real browser (`UI` suite, needs Chromium)          |
| Infection                                           | `test:mutation`                 | Mutation testing                                                   |
| PHPArkitect                                         | `test:arch`                     | Architecture rules                                                 |
| Bats                                                | `test:bash`                     | Shell scripts, run on the host                                     |

`task qa` runs cs, lint, quality, the tests and the architecture rules before committing.
`task qa deep=1` adds the mutation and UI tests, which are slow. `task qa fix=1` applies the
coding standards fixes, never the Rector refactorings (`task quality:rector fix=1`).

The integration tests read a test database loaded with generated fixtures: `task test:prepare`
(run by the test tasks) generates `src/DataFixtures/Testing` from the dev database, then
recreates and loads the test database.

### Git hooks

GrumPHP installs the git hooks on `composer install`. They run in the `app` container, so
**the containers must be running to commit**: `pre-commit` checks the staged files, and
`commit-msg` enforces [Conventional Commits](https://www.conventionalcommits.org/).

## Continuous integration

`.github/workflows/ci.yml` runs on pull requests and on pushes to `main` and `develop`:

- **QA**: starts the stack, installs the dependencies, creates the dev database from the schema
  and its fixtures, then runs `task qa` (without the mutation and UI tests). On pull requests to
  `main`, it also checks the production build (`task symfony:build-prod`).
- **Conventional commits** (pull requests): commitlint checks every commit.

Dependabot opens weekly pull requests on `develop` for the GitHub Actions, Composer and pnpm
dependencies.

## Deployment

The site is hosted on **Hostinger** (Premium Web Hosting, MariaDB 11.8). Two subdomains serve the
static files, outside this repository:

- `docs.carolinenoyer.fr`: documents (resume, reports, articles)
- `medias.carolinenoyer.fr`: images, allowed in the Content Security Policy

Branches: features on `cno_*` branches, merged into `develop`, then `main`. The `production`
branch is what the server pulls: `main` plus the compiled assets.

```bash
task prod:release
```

1. `prod:build` (from `main`): builds Tailwind, the importmap and the assets in `prod`, merges
   `main` into `production`, commits the compiled assets and pushes `production`.
2. `prod:deploy`: connects over SSH (`REMOTE_*` from `.env.local`) and runs `~/deploy.sh` on the
   server, a copy of `.tools/deploy.sh-prod`: it backs up the site and the database, resets the
   site to `origin/production`, installs the dependencies without the dev ones, warms the cache and
   dumps the sitemap. If it fails, the `production` branch is rolled back.
3. `prod:dev`: restores the development dependencies, cache and assets, even after a failure.

The deployment does not run the migrations: apply them on the server by hand when needed
(`php bin/console doctrine:migrations:migrate`). The migrations were squashed into one baseline
in November 2026: a database created before it only needs `doctrine:migrations:rollup` once.

`task symfony:build-prod` checks the production build in a temporary copy, without touching the
working copy or the branches.

The checks to run before and after a release are listed in [docs/DEPLOY.md](docs/DEPLOY.md).

## Updating

`task update` pulls the Docker images, rebuilds the containers and updates the Composer and pnpm
dependencies. Symfony upgrades are described in [UPDATE.md](UPDATE.md).

## Resources

- Icons: [UX Icons](https://ux.symfony.com/icons) (`task symfony:console -- ux:icons:search fa7-solid pdf`,
  then `ux:icons:import`), [IconStack](https://iconstack.io/), [HugeIcons](https://hugeicons.com/)
- Audits: `task utils:seo-audit` runs [Lighthouse](https://developer.chrome.com/docs/lighthouse/overview/),
  [Unlighthouse](https://unlighthouse.dev/) and [pa11y](https://pa11y.org/), reports in `.tools/reports/`;
  see also [PageSpeed Insights](https://pagespeed.web.dev/)
- Design: [docs/style-guide.md](docs/style-guide.md)

## License

All rights reserved. This repository is public so that the code can be read as a showcase of my
work; it is not open source. Its code, text and visual content may not be reused, copied or
redistributed without my permission. See [LICENSE](LICENSE).
