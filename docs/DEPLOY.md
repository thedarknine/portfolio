# Deployment guide

How to check a release before sending it to production, then deploy it to Hostinger.

- [Before deploying](#before-deploying)
- [Deploying](#deploying)
- [What happens at each step](#what-happens-at-each-step)
- [After deploying](#after-deploying)
- [Rolling back](#rolling-back)
- [Other commands](#other-commands)
- [Checklist](#checklist)

## Before deploying

A release always starts from `main`, once `develop` has been merged into it:

```bash
git checkout main && git pull origin main
```

Run the steps below there, with the containers up (`task up`), and stop at the first failure. If a step needs a
change (a dependency update, a fix), make it on a `cno_` branch, merge it into `develop` then `main` as usual, and
start again from `main`: nothing is committed on `main` directly. `prod:check` refuses to release from any other
branch.

### 0. Update dependencies

Run the following commands to check for outdated dependencies:

```bash
docker compose exec app composer outdated --direct
docker compose exec app pnpm outdated
```

If there are updates available, run the following commands to update the dependencies:

```bash
task update
task qa deep=1
```

### 1. Quality checks

```bash
task qa deep=1
```

Runs the coding standards, linters, PHPStan, Deptrac, Rector, the dependency audit, the Unit, Integration and UI
test suites, the architecture rules and the mutation tests (Infection).

Infection may report a few timed out mutants in `SkillRepository`: they count as killed in the score and do not
block a release.

### 2. Validate the production configuration

Compile the production container and check the templates without deploying anything:

```bash
docker compose exec -e APP_ENV=prod -e APP_DEBUG=0 app php bin/console cache:warmup
docker compose exec -e APP_ENV=prod -e APP_DEBUG=0 app php bin/console lint:container
docker compose exec -e APP_ENV=prod -e APP_DEBUG=0 app php bin/console lint:twig templates
```

This catches the errors that only exist in production: the `when@prod` sections and `config/packages/prod/`
(Monolog alert handler, NelmioSecurity CSP and HSTS) are not loaded in development.

> [!WARNING]
> Do not set `APP_ENV=prod` in `.env.local` to test in the browser: NelmioSecurity sends an HSTS header in
> production, and the browser then forces HTTPS on `localhost` for a year. If it happens, delete the `localhost`
> policy in `chrome://net-internals/#hsts`. The `-e` variables above override the `.env` files for one command only.

### 3. Local audit

```bash
task utils:seo-audit
```

Runs Lighthouse on the home page, Pa11y on every page of the sitemap and SiteOne Crawler on the whole site.
Reports are written to `.tools/reports/`.

- **Pa11y** must pass: the script stops on the first WCAG error. Known false positives (text over images,
  decorative and muted elements, the hidden Altcha field) are hidden in `.tools/config/pa11y.json`.
- **SiteOne Crawler**: the security headers (CSP, HSTS), Brotli compression and cache policies are set in
  production only, so their findings in development are expected. Any 404, duplicate description or heading issue
  must be fixed.
- **Lighthouse** scores are not representative in development (debug toolbar, no minification).

## Deploying

> [!NOTE]
> This section describes the target procedure. It replaces the `production` branch flow (`prod:build`,
> `prod:deploy`, `prod:dev`), which stays available until the first release made this way succeeds.

```bash
task prod:release
```

Versions follow CalVer, `vYYYY.M.PATCH` (see [AGENTS.md](../AGENTS.md#releases)). The version is computed from
the date and the existing tags: `v2026.11.0` for the first release of November 2026, `v2026.11.1` for the next
one in the same month. The release shows it and asks for a confirmation before going on.

`task prod:release version=v2026.11.0` is still accepted, to state the expected version explicitly: the release
stops if it does not match the computed one.

The release is built on this machine from a clean commit of `main`, then copied to the server with rsync. The
server never runs git or Composer: it only receives a ready-to-serve copy of the site, then runs the migrations
and warms up the cache. The working copy never changes branch.

Needs the `REMOTE_*` variables in `.env.local` and the SSH key in `REMOTE_SSH_KEY`.

## What happens at each step

`task prod:release` runs the steps below in this order and stops at the first failure. Each step can also be run
on its own (`task prod:<step>`).

### 1. Checks — `prod:check`

| | |
|---|---|
| **Runs on** | This machine |
| **Does** | Checks that the working copy is on `main`, has no uncommitted change and matches `origin/main`, and that the server answers over SSH. Computes the version (see below), shows it with the commit to release, and asks for a confirmation. |
| **Changes** | Nothing |
| **On failure** | Nothing to undo. Fix the reported problem, or answer `y` to the confirmation, and run the release again. |

The version is `v<year>.<month>.<patch>`, from today's date. `<patch>` is `0` if no `v<year>.<month>.*` tag
exists yet, otherwise the highest one plus 1. When `version=` is given, it must be exactly that value: a wrong
format (`2026.11.0`, `v2026.07.0`, `v2026.11`), another month or a skipped number stops the release.

### 2. Build — `prod:build`

| | |
|---|---|
| **Runs on** | The `app` container |
| **Does** | Exports the current commit with `git archive` into `tmp/release/<sha>/` (untracked and ignored files are never included), installs the Composer dependencies without the dev ones, installs the importmap vendors, builds the minified Tailwind CSS and compiles the assets. Then keeps only the files listed below and writes a `REVISION` file (version, commit, date). |
| **Changes** | Creates `tmp/release/<sha>/` (ignored by git). The working copy, its `vendor/` and its dev assets are not touched. |
| **On failure** | Nothing is sent to the server. Delete `tmp/release/` if needed and run the release again. |

The release holds only what the site needs to run:

- `bin/console`, `config/`, `migrations/`, `public/`, `src/`, `templates/`, `translations/`, `vendor/`
- `composer.json`, `composer.lock`, `importmap.php`, `.env`, `.htaccess`, `REVISION`

Everything else (`.docker/`, `.git/`, `.tools/`, `assets/`, `docs/`, `tests/`, the Compose files, `Taskfile.yml`,
`.env.dev`, `.env.test`...) never reaches the server, and is deleted from it by the upload step.

### 3. Backup — `prod:backup`

| | |
|---|---|
| **Runs on** | The server, streamed to this machine over SSH |
| **Does** | Dumps the production database (`mysqldump`, gzip) and copies the production `.env.local`, both written directly to `.tools/export/prod/<date>/` on this machine. Checks that the dump is a valid, non-empty gzip file. |
| **Changes** | Creates `.tools/export/prod/<date>/` (ignored by git). Nothing is written on the server. |
| **On failure** | The release stops before anything is changed on the server. |

> [!IMPORTANT]
> `.tools/export/prod/` holds the production database and secrets. Keep it out of any shared or synced folder.

### 4. Maintenance on — `prod:maintenance state=on`

| | |
|---|---|
| **Runs on** | The server |
| **Does** | Creates `maintenance.flag` at the root of `public_html`. While it exists, the root `.htaccess` answers every request with a `503` status and the static page `public/maintenance.html`, without starting Symfony. |
| **Changes** | Visitors see the maintenance page. Search engines treat a `503` as temporary. |
| **On failure** | Nothing changed: run the release again. |

### 5. Upload — `prod:upload`

| | |
|---|---|
| **Runs on** | This machine, towards the server |
| **Does** | Copies `tmp/release/<sha>/` into `public_html` with `rsync --delete`. Files that exist on the server but not in the release are deleted, except the protected paths listed below. |
| **Changes** | The code of the site on the server. |
| **On failure** | The site stays in maintenance: run the upload again, or [roll back](#rolling-back). |

Protected paths, never sent nor deleted:

- `.env.local` (production secrets)
- `maintenance.flag`
- `var/` (logs, sessions)
- `analytics/` (Matomo)
- `carolinenoyer_docs/`, `carolinenoyer_medias/` (served by the `docs.` and `medias.` subdomains)
- `.well-known/` (SSL certificate renewal)

### 6. Finalise — `prod:finalize`

| | |
|---|---|
| **Runs on** | The server |
| **Does** | Runs the Doctrine migrations, clears and warms up the production cache, then dumps the sitemap. |
| **Changes** | The database schema (if the release has migrations), `var/cache/prod/`, `public/sitemap*.xml`. |
| **On failure** | The site stays in maintenance. If a migration failed, the database may be half migrated: [roll back](#rolling-back) and restore the dump of step 3. |

### 7. Maintenance off — `prod:maintenance state=off`

| | |
|---|---|
| **Runs on** | The server |
| **Does** | Deletes `maintenance.flag`: the site is served again. |
| **Changes** | Visitors see the new release. |
| **On failure** | Delete the file by hand (`task prod:shell`, then `rm maintenance.flag`). |

### 8. Smoke test — `prod:smoke`

| | |
|---|---|
| **Runs on** | This machine |
| **Does** | Requests the home page, an inner page and the sitemap, which must answer `200`. Requests `/.env`, `/.env.local`, `/composer.json`, `/var/log/prod.log` and `/vendor/autoload.php`, which must answer `403` or `404`. Checks, through `prod:status`, that the version online is the one released. |
| **Changes** | Nothing |
| **On failure** | The release is not tagged. Check the site, then [roll back](#rolling-back) if needed. |

### 9. Tag — `prod:tag`

| | |
|---|---|
| **Runs on** | This machine |
| **Does** | Creates the annotated tag of the version on the released commit, pushes it to `origin`, then creates the GitHub release with `gh release create <version> --generate-notes`: GitHub lists the pull requests merged since the previous version under "What's changed". |
| **Changes** | A new git tag and a GitHub release: the record of what is online, and the starting point of a rollback. |
| **On failure** | The site is online. Create the tag and the release by hand with the same commands. |

## After deploying

- **Audit:** `task utils:seo-audit target=prod`, for the real Lighthouse scores and security headers.
- **Backup:** the new folder in `.tools/export/prod/` holds `db.sql.gz` and `env.local`.
- **Open Graph:** check the preview of the home page and of an inner page in the
  [LinkedIn Post Inspector](https://www.linkedin.com/post-inspector/).
- **Structured data:** check two pages in the [Schema.org validator](https://validator.schema.org/): the Person
  `@id` must be the same on both.
- **Error alerts:** after a change to the mailer or Monolog configuration, run a failing command on the server
  (`php bin/console cache:clear test-alert`) and check that the alert email arrives.
- **Uptime:** the UptimeRobot monitor stayed up during the deployment.

## Rolling back

```bash
task prod:rollback version=v2026.7.0
```

Rebuilds the given tag (step 2), then runs steps 4 to 8 with it. The backup step is skipped: the dump of the
failed release is the one to restore if needed.

Migrations are not reverted. If the failed release changed the database schema, restore the dump taken before it,
while the site is still in maintenance:

```bash
gunzip -c .tools/export/prod/<date>/db.sql.gz | ssh <server> 'mysql -u <user> -p <database>'
```

List the releases with `git tag --list 'v*' --sort=-creatordate`.

## Other commands

| Command | Does |
|---|---|
| `task prod:status` | Shows the version online, read from the `REVISION` file on the server |
| `task prod:shell` | Opens an SSH session in `public_html` |
| `task prod:logs` | Shows the end of `var/log/prod.log` on the server |

## Checklist

- [ ] `task qa deep=1` passes
- [ ] `cache:warmup`, `lint:container` and `lint:twig` pass in `prod`
- [ ] `task utils:seo-audit` passes, SiteOne findings reviewed
- [ ] `task prod:release` completed, smoke test green, version tagged and GitHub release created
- [ ] Backup present in `.tools/export/prod/<date>/`
- [ ] `task utils:seo-audit target=prod` reviewed
- [ ] Open Graph and structured data checked
- [ ] UptimeRobot monitor up
