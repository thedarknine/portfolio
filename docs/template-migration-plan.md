# Plan: move the portfolio onto symfony-docker-template

- **Status:** planned, November 2026
- **Branch:** `cno_template`, created from `develop` **after** `cno_redesign` is merged
- **Template:** `github.com/thedarknine/symfony-docker-template` (local copy: `~/Development/symfony-docker-template`)

## Context

The portfolio and the template already share the same application stack: Symfony 8.1, PHP 8.4 FPM Alpine,
AssetMapper, Tailwind, Doctrine ORM 3, UX Icons, nginx, pnpm. The application code (`src/`, `config/`,
`templates/`, `assets/`, `migrations/`, `translations/`, `tests/`) does not need to change.

The work is about **tooling only**: Docker, the task runner, tool configuration, CI and git hooks.
The repository is kept as is (history, branches, GitHub settings); the template is applied on top of it.

## Guidelines

- One commit per step below, Conventional Commits in English
- Repo content stays in English
- After each step: the stack starts and `qa` passes (`make qa` until step 4, `task qa` afterwards)
- Keep everything the portfolio has that the template lacks (see "Portfolio-only tools")

## Steps

### 0. Prepare

- [x] `cno_redesign` merged into `develop` (and deployed if wanted)
- [x] `git switch develop && git pull && git switch -c cno_template`
- [x] Note the current state as a reference: `make qa` output, local URL working, prod build working

### 1. Docker

Template files: `.docker/php.Dockerfile`, `.docker/install-tools.sh`, `.docker/php/php.ini`,
`.docker/nginx/default.conf`, `docker-compose.yaml`, `docker-compose.dev.yaml`, `docker-compose.ci.yaml`.

- [x] Replace `.docker/php.dockerfile` with the template `php.Dockerfile` (`PHP_VERSION` arg, `install-tools.sh`, UID/GID)
- [x] Add to `install-tools.sh` what the portfolio needs and the template does not install:
  `chromium` and `chromium-chromedriver` (pa11y / unlighthouse / UI tests), `make` only while the Makefile exists,
  extensions used by the portfolio (`gd`, `zip`, `xsl`, `intl`… compare both extension lists)
- [x] Compare the two `default.conf` (nginx) and keep portfolio-specific rules
- [x] Split `docker-compose.yml` into base / dev / ci following the template; services renamed
`app`, `engine`, `db`, `adm`, `mailpit`
- [x] Keep the MySQL config (`.docker/mysql/my.cnf`) and the data / log volumes; check the MySQL version (template: 9.7)
  → switched to MariaDB 11.8 to match production (`MARIADB_VERSION`, `serverVersion=11.8.9-MariaDB`);
  test user created by `.docker/mysql/sql/01-test-user.sql`
- [x] Add `PROJECT_SLUG=portfolio` and ports (`APP_PORT`, `DB_PORT`, `ADM_PORT`, `MAIL_PORT`) to `.env`
  → `.env` is now versioned (replaces `.env.sample`), secrets moved to `.env.local`
- [x] Update `DATABASE_URL` host (service name) and `MAILER_DSN` (Mailpit) in `.env.dev`
- [x] Rebuild, start, import a dump, check the site, admin and 2FA login
- Commit: `build(docker): align docker setup with symfony-docker-template`

### 2. Tool configuration layout

Template layout: configs in `.tools/config/`, scripts in `.tools/scripts/`, tasks in `.tools/tasks/`, cache in `.tools/cache/`.

- [x] Move configs to `.tools/config/` with the template names:
  `.php-cs-fixer.php` → `php-cs-fixer.php`, `.twig-cs-fixer.php` → `twig-cs-fixer.php`, `biome.json`, `gitleaks.toml`,
  `grumphp.yml`, `phpstan.neon`, `phpunit.xml`, `rector.php`, `yamllint.yaml`, plus portfolio-only configs
  (`deptrac.yaml`, `phparkitect.php`, `infection.json`, `pa11y.json`, `unlighthouse.config.ts`, `eslint.config.mjs`,
  `markdownlint-cli2.yaml`)
- [x] Add template-only configs: `commitlint.config.cjs`, `hadolint.yaml`
- [x] Merge rule sets where both exist (php-cs-fixer, phpstan level/paths, rector sets, biome): keep the stricter rule
- [x] Fix every path pointing to the old locations (Makefile, scripts, CI, `composer.json`, `package.json`, `.gitignore`)
- [ ] Drop `checkmake.ini` at step 4 (no Makefile anymore)
- Notes: tool caches now live in `.tools/cache/` (ignored). PHPStan also analyses `tests/`; the template's
  Symfony container settings for PHPStan need `phpstan/phpstan-symfony`, not installed yet. GrumPHP now checks
  commit messages and runs twig-cs-fixer. `run-pre-commit.sh` is renamed at step 3.
- Commit: `chore(tools): move tool configuration to .tools/config`

### 3. Scripts

- [x] Take template scripts: `init.sh`, `help.sh`, `pre-commit.sh`, `utils.sh` (`build-prod.sh` not taken, see notes)
- [x] Merge portfolio `utils.sh` helpers into the template one if some are missing
- [x] Keep the Hostinger deployment: `prod-build.sh`, `prod-deploy.sh` (and `.tools/deploy.sh-prod`); compare
  `prod-build.sh` with the template `build-prod.sh` and keep a single build script
- [x] Keep `audit.sh`; replace `dev.sh` and `run-pre-commit.sh` with template equivalents if they cover the same need
- Notes: `utils.sh` is the template one plus the deployment checks (`check_on_main_branch`, ...) and still hides
  nested success boxes for `make` (`MAKELEVEL`) until step 4. `prod-build.sh` is kept: it builds the assets and
  pushes the `production` branch, which the template `build-prod.sh` (a build check in a temporary copy) does not
  do. `dev.sh` has no template equivalent and is kept. `run-pre-commit.sh` is replaced by `pre-commit.sh`
  (gitleaks, Biome and yamllint on staged files); the full gitleaks scan, checkmake and the README refresh are no
  longer run on commit. `help.sh` and `init.sh` are used from step 4 (Taskfile).
- Commit: `chore(scripts): adopt template scripts and keep hostinger deployment`

### 4. Makefile → Taskfile

Template: `Taskfile.yml` with includes `docker`, `symfony`, `lint`, `cs`, `quality`, `test`, `db`, `check`
(`.tools/tasks/*.yml`). Bring all `.tools/tasks/*.yml` from the template, then map the Makefile targets:

| Makefile | Taskfile | Note |
| --- | --- | --- |
| build, up, down, destroy, restart, shell, logs | `docker:*` | template |
| doctor, check-tools, check-docker, check-containers, check-ports, check-env, check-dependencies, check-tools-directory | `check:*` | template, compare checks |
| cc, secret, watch, composer-clear, pnpm-clear, pnpm-prune | `symfony:*` | check which exist |
| clean, clean-build, clean-cache, clean-test | `symfony:*` or new | |
| migration, fixtures, db-dump | `db:*` | add `db-dump` if missing |
| cs, cs-php, cs-yaml, cs-twig, cs-front, cs-fix | `cs:*` | template |
| lint, lint-shell, lint-docker, lint-md | `lint:*` | add `lint-md` (markdownlint) |
| qa, qa-analyse, qa-rector, audit | `quality:*` and `qa` | template |
| test, test-bash, cover | `test:*` | add bash tests (`tests/bash`) |
| test-mutation, test-arch, test-ui | `test:*` | **add**: infection, deptrac + phparkitect, pa11y / unlighthouse |
| pre-commit, grum-install, grum-run, grum-pre-commit | hooks / `check` | follow the template hook setup |
| update-project, check-project | new tasks | portfolio-only |
| deploy, prod-build, prod-deploy | new `deploy` include | portfolio-only, Hostinger |
| dev, readme, readme-check, help | template `help` / new | |
| cs-makefile | — | drop |

- [ ] Write missing tasks, keep the template UI helpers (`{{.UI}}`, `{{.EXEC}}`)
- [ ] Run every task once
- [ ] Delete `Makefile` and `checkmake.ini`
- Commit: `build(task): replace the Makefile with the template Taskfile`

### 5. CI and hooks

- [ ] Replace `.github/workflows/ci.yml` with the template one (uses `docker-compose.ci.yaml`), add portfolio-only jobs
  (mutation, architecture, UI / accessibility) if they ran in CI before
- [ ] Add `.github/dependabot.yml` (target `develop`)
- [ ] GrumPHP + commitlint hook as in the template; check a bad commit message is rejected
- [ ] CI green on the PR
- Commit: `ci: align workflow and hooks with symfony-docker-template`

### 6. Tests layout (optional)

Template: `tests/Unit`, `tests/Functional`. Portfolio: `tests/Unit`, `tests/Integration`, `tests/UI`, `tests/bash`.

- [ ] Keep the portfolio layout (richer); only make sure `phpunit.xml` test suites and the test tasks match it
- Commit if anything changes: `test: align test suites with the task setup`

### 7. Docs and cleanup

- [ ] README rewritten from the template README (requirements with Task, services table, commands) plus portfolio specifics
  (Hostinger, docs / medias subdomains, deployment)
- [ ] Add `AGENTS.md` from the template, adapted (Conventional Commits in English, `cno_` branches, repo in English)
- [ ] Update `UPDATE.md` (`task update-project`)
- [ ] `.editorconfig`, `.gitignore`, `.env.sample` / `.env` aligned with the template
- [ ] Remove leftovers: `tmp/`, old paths, unused scripts
- Commit: `docs: document the template-based setup`

### 8. Release

- [ ] Fresh clone test: `task init` (or equivalent) from zero works
- [ ] PR `cno_template` → `develop`, CI green
- [ ] Prod build and deployment tested before merging to `main` / `production`

## Portfolio-only tools (to keep)

deptrac, phparkitect, infection, pa11y, unlighthouse, eslint, markdownlint, bash tests, Hostinger deployment scripts.
Dropped: checkmake (Makefile removed).

## Template-only additions

Taskfile, commitlint, hadolint, Mailpit, AdminNeo, dependabot, `PROJECT_SLUG`, UID/GID build args, `AGENTS.md`.
