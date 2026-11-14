# Deployment guide

How to check a release before sending it to production, then deploy it to Hostinger.

- [Before deploying](#before-deploying)
- [Deploying](#deploying)
- [Release step by step](#release-step-by-step)
- [What happens at each step](#what-happens-at-each-step)
- [After deploying](#after-deploying)
- [Rolling back](#rolling-back)
- [Publishing content changes](#publishing-content-changes)
- [Other commands](#other-commands)
- [Checklist](#checklist)
- [History](#history)

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

```bash
task prod:release
```

Versions follow CalVer, `vYYYY.M.PATCH` (see [AGENTS.md](../AGENTS.md#releases)). The version is computed from
the date and the existing tags: `v2026.11.0` for the first release of November 2026, `v2026.11.1` for the next
one in the same month. The release shows it and asks for a confirmation before going on.
`task prod:release version=v2026.11.1` is still accepted, to state the expected version explicitly: the release
stops if it does not match the computed one.

The release is built on this machine from a clean commit of `main`, then copied to the server with rsync. The
server never runs git or Composer: it only receives a ready-to-serve copy of the site, then runs the migrations
and warms up the cache. The working copy never changes branch.

### Requirements

- **`.env.local`:** `REMOTE_HOST`, `REMOTE_USER`, `REMOTE_PORT`, `REMOTE_SSH_KEY` (a leading `~` is accepted),
  and optionally `UPTIME_API_KEY` (UptimeRobot main API key) and `UPTIME_MONITOR_ID`, to pause the monitor during
  the maintenance. Without them, the release warns and the monitor reports the maintenance as a downtime.
- **SSH key in the macOS keychain,** so the passphrase is not asked at every command. Once, in `~/.ssh/config`:

  ```text
  Host *
      AddKeysToAgent yes
      UseKeychain yes
  ```

  then `ssh-add --apple-use-keychain ~/.ssh/<key>`.
- **GitHub CLI logged in** (`gh auth status`), for the GitHub release.
- **Containers up** (`task up`): the build runs in the `app` container.

## Release step by step

`task prod:release` chains the steps below. Run them one by one instead when a release needs a manual action
between two steps (a data fix, a migration to mark), or to check each step. Stop at the first failure: until
step 7, visitors see the maintenance page, and the database is saved by step 3.

| # | Command | What to check |
| --- | --- | --- |
| 1 | `task prod:check` | The version proposed and the commit are the expected ones. Answer `y`. |
| 2 | `task prod:build` | `cat tmp/release/*/dist/REVISION` shows the version. `dist/` only holds the release files. |
| 3 | `task prod:backup` | `.tools/export/prod/<date>-<version>/` holds `db.sql.gz` and `env.local`. |
| — | `task prod:upload dry=1` | Optional: the files that would be sent or deleted. No protected path may appear. |
| 4 | `task prod:maintenance state=on` | The UptimeRobot monitor is paused. The site shows the maintenance page. |
| 5 | `task prod:upload` | Silent when it succeeds. `task prod:status` shows the new version. |
| 6 | `task prod:finalize` | The migrations run (or "No migrations to execute"), the cache and the sitemap are rebuilt. |
| 7 | `task prod:maintenance state=off` | The new site is online, the monitor is started again. Browse a few pages, the admin, the contact form. |
| 8 | `task prod:smoke` | Every line is green, the version online is the released one. |
| 9 | `task prod:tag` | The tag and the GitHub release exist. Only once the site is checked. |

If a step fails after step 4, fix the problem and run the failed step again, or [roll back](#rolling-back). To
serve the site again as it is, run `task prod:maintenance state=off`.

## What happens at each step

`task prod:release` runs the steps below in this order and stops at the first failure. Each step can also be run
on its own (`task prod:<step>`).

### 1. Checks — `prod:check`

| | |
| --- | --- |
| **Runs on** | This machine |
| **Does** | Checks that the working copy is on `main`, has no uncommitted change and matches `origin/main`, and that the server answers over SSH. Computes the version (see below), shows it with the commit to release, and asks for a confirmation. |
| **Changes** | Nothing |
| **On failure** | Nothing to undo. Fix the reported problem, or answer `y` to the confirmation, and run the release again. |

The version is `v<year>.<month>.<patch>`, from today's date. `<patch>` is `0` if no `v<year>.<month>.*` tag
exists yet, otherwise the highest one plus 1. When `version=` is given, it must be exactly that value: a wrong
format (`2026.11.0`, `v2026.07.0`, `v2026.11`), another month or a skipped number stops the release.

### 2. Build — `prod:build`

| | |
| --- | --- |
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
| --- | --- |
| **Runs on** | The server, streamed to this machine over SSH |
| **Does** | Dumps the production database (`mysqldump`, gzip) and copies the production `.env.local`, both written directly to `.tools/export/prod/<date>/` on this machine. Checks that the dump is a valid, non-empty gzip file. |
| **Changes** | Creates `.tools/export/prod/<date>/` (ignored by git). Nothing is written on the server. |
| **On failure** | The release stops before anything is changed on the server. |

> [!IMPORTANT]
> `.tools/export/prod/` holds the production database and secrets. Keep it out of any shared or synced folder.

### 4. Maintenance on — `prod:maintenance state=on`

| | |
| --- | --- |
| **Runs on** | The server |
| **Does** | Pauses the UptimeRobot monitor (when `UPTIME_*` is set), then creates `maintenance.flag` at the root of `public_html`. While it exists, the root `.htaccess` answers every request with a `503` status and the static page `public/maintenance.html`, without starting Symfony. |
| **Changes** | Visitors see the maintenance page. Search engines treat a `503` as temporary. |
| **On failure** | Nothing changed: run the release again. |

### 5. Upload — `prod:upload`

| | |
| --- | --- |
| **Runs on** | This machine, towards the server |
| **Does** | Copies `tmp/release/<sha>/dist/` into `public_html` with `rsync --delete`. `task prod:upload dry=1` only lists what would change. Files that exist on the server but not in the release are deleted, except the protected paths listed below. |
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
| --- | --- |
| **Runs on** | The server |
| **Does** | Runs the Doctrine migrations, clears and warms up the production cache, then dumps the sitemap. |
| **Changes** | The database schema (if the release has migrations), `var/cache/prod/`, `public/sitemap*.xml`. |
| **On failure** | The site stays in maintenance. If a migration failed, the database may be half migrated: [roll back](#rolling-back) and restore the dump of step 3. |

### 7. Maintenance off — `prod:maintenance state=off`

| | |
| --- | --- |
| **Runs on** | The server |
| **Does** | Deletes `maintenance.flag`: the site is served again. Starts the UptimeRobot monitor again. |
| **Changes** | Visitors see the new release. |
| **On failure** | Delete the file by hand (`task prod:shell`, then `rm maintenance.flag`). |

### 8. Smoke test — `prod:smoke`

| | |
| --- | --- |
| **Runs on** | This machine |
| **Does** | Requests the home page, an inner page and the sitemap, which must answer `200`. Requests `/.env`, `/.env.local`, `/composer.json`, `/var/log/prod.log` and `/vendor/autoload.php`, which must answer `403` or `404`. Checks, through `prod:status`, that the version online is the one released. |
| **Changes** | Nothing |
| **On failure** | The release is not tagged. Check the site, then [roll back](#rolling-back) if needed. |

### 9. Tag — `prod:tag`

| | |
| --- | --- |
| **Runs on** | This machine |
| **Does** | Creates the annotated tag of the version on the released commit, pushes it to `origin`, then creates the GitHub release with `gh release create <version> --generate-notes`: GitHub lists the pull requests merged since the previous version under "What's changed". |
| **Changes** | A new git tag and a GitHub release: the record of what is online, and the starting point of a rollback. |
| **On failure** | The site is online. Create the tag and the release by hand with the same commands. |

## After deploying

- **Audit:** `task utils:seo-audit target=prod`, for the real Lighthouse scores and security headers. Pa11y
  must pass.
- **Browser console:** no Content Security Policy error on the home page, a gallery page (Projets, Photos) and
  the contact page. The CSP only applies in production: every `<script>` must carry the nonce
  (`csp_nonce('script')`), and a page needs a single `importmap()` call listing all its entry points.
- **Backup:** the new folder in `.tools/export/prod/` holds `db.sql.gz` and `env.local`.
- **Open Graph:** check the preview of the home page and of an inner page in the
  [LinkedIn Post Inspector](https://www.linkedin.com/post-inspector/), which also refreshes LinkedIn's cache.
  After a change to a page title, tagline or subtitle, regenerate its card first (`task utils:og-images
  page=<technical name>`).
- **Structured data:** check two pages in the [Schema.org validator](https://validator.schema.org/): the Person
  `@id` must be the same on both.
- **Error alerts:** after a change to the mailer or Monolog configuration, run a failing command on the server
  (`task prod:shell`, then `php bin/console cache:clear test-alert --env=prod`). The command error is expected:
  check that the alert email arrives.
- **Uptime:** the UptimeRobot monitor is active again, without a downtime alert.

## Rolling back

```bash
task prod:rollback version=v2026.7.0
```

Rebuilds the given tag (step 2), then runs steps 4 to 8 with it. The backup step is skipped: the dump of the
failed release is the one to restore if needed.

Migrations are not reverted. If the failed release changed the database schema, restore the dump taken before it,
while the site is still in maintenance:

```bash
scp -P "$REMOTE_PORT" .tools/export/prod/<date>-<version>/db.sql.gz "$REMOTE_USER@$REMOTE_HOST:"
task prod:shell
gunzip -c ~/db.sql.gz | mysql -u <DB_USER> -p <DB_NAME> && rm ~/db.sql.gz
```

`DB_USER` and `DB_NAME` are in the `.env.local` of the server, the password is asked.

List the releases with `git tag --list 'v*' --sort=-creatordate`.

## Publishing content changes

Content (pages, experiences, projects, skills...) is edited in the local admin, then published by replacing the
content tables of production with the local ones. `user` (the production password and 2FA secret) and
`doctrine_migration_versions` are never replaced. Release the code first, so both schemas match.

1. **Compare.** `task prod:backup` and `task db:dump`, then compare the two dumps table by table: anything that
   only exists in production would be lost. Bring it back into the local database first.
2. **Export the local content,** data only, with explicit column lists (the column order differs between the
   local and production schemas):

   ```bash
   docker compose exec -T db mariadb-dump -uroot -p"$DB_ROOT_PASS" --no-create-info --complete-insert \
       --skip-triggers --ignore-table="$DB_NAME.user" --ignore-table="$DB_NAME.doctrine_migration_versions" \
       "$DB_NAME" > tmp/content.sql
   ```

   Then add at the top of `tmp/content.sql`: `SET FOREIGN_KEY_CHECKS = 0;`, `START TRANSACTION;` and a
   `DELETE FROM` for each exported table, and at the end `COMMIT;` and `SET FOREIGN_KEY_CHECKS = 1;`.
3. **Rehearse locally:** import the production `db.sql.gz` into the local database, then `tmp/content.sql`, run
   `doctrine:schema:validate`, browse the site and log in to the admin with the production password. Restore the
   local database afterwards from the `task db:dump` file.
4. **Publish:** `task prod:backup`, `task prod:maintenance state=on`, copy `tmp/content.sql` to the server (`scp`),
   then in `task prod:shell`:

   ```bash
   mysql -u <DB_USER> -p <DB_NAME> < ~/content.sql
   php bin/console cache:clear --env=prod
   php bin/console presta:sitemaps:dump --env=prod
   rm ~/content.sql
   ```

   and `task prod:maintenance state=off`, `task prod:smoke`. Regenerate the Open Graph cards of the pages whose
   texts changed, and release them with the next version.

## Other commands

| Command | Does |
| --- | --- |
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
- [ ] No CSP error in the browser console (home, a gallery page, contact)
- [ ] Open Graph and structured data checked
- [ ] UptimeRobot monitor up

## History

- **v2026.11.0 (13 November 2026), first rsync release.** It replaced a server deployed with git through the
  `production` branch. The migrations had been squashed into a baseline after the previous deployment, so the
  baseline was marked as executed on the server between the upload and the migrations
  (`doctrine:migrations:version 'DoctrineMigrations\Version20261103080725' --add`), after a rehearsal on a copy
  of the production database. The content was then published from the local database.
- **Maintenance page on LiteSpeed:** Hostinger's LiteSpeed does not apply the rewrite rules to error documents,
  so the root `.htaccess` points `ErrorDocument 503` to the real path, `/public/maintenance.html`.
