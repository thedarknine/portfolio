# Deployment guide

How to check a release before sending it to production, then deploy it to Hostinger.

- [Before deploying](#before-deploying)
- [Deploying](#deploying)
- [After deploying](#after-deploying)
- [Checklist](#checklist)

## Before deploying

Run these steps on the branch to release, with the containers up (`task up`). Stop at the first failure.

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

1. `prod:build` builds the assets for production (Tailwind, importmap, AssetMapper) and commits them to the
   `production` branch.
2. `prod:deploy` runs `deploy.sh` on the server: it backs up the web directory and dumps the database to
   `~/backups/<date>/`, then pulls the `production` branch.
3. The latest database dump is downloaded to `.tools/export/prod/`, even when the deployment fails.
4. On failure, the `production` branch is rolled back automatically.
5. `prod:dev` switches back to the development environment.

Needs the `REMOTE_*` variables in `.env.local` and the SSH key in `REMOTE_SSH_KEY`.

## After deploying

- **Audit:** `task utils:seo-audit target=prod`, for the real Lighthouse scores and security headers.
- **Database dump:** a new `db_<date>.sql.gz` file is in `.tools/export/prod/`.
- **Open Graph:** check the preview of the home page and of an inner page in the
  [LinkedIn Post Inspector](https://www.linkedin.com/post-inspector/).
- **Structured data:** check two pages in the [Schema.org validator](https://validator.schema.org/): the Person
  `@id` must be the same on both.
- **Error alerts:** after a change to the mailer or Monolog configuration, run a failing command on the server
  (`php bin/console cache:clear test-alert`) and check that the alert email arrives.
- **Uptime:** the UptimeRobot monitor stayed up during the deployment.

## Checklist

- [ ] `task qa deep=1` passes
- [ ] `cache:warmup`, `lint:container` and `lint:twig` pass in `prod`
- [ ] `task utils:seo-audit` passes, SiteOne findings reviewed
- [ ] `task prod:release` completed
- [ ] Database dump downloaded to `.tools/export/prod/`
- [ ] `task utils:seo-audit target=prod` reviewed
- [ ] Open Graph and structured data checked
- [ ] UptimeRobot monitor up
