# AGENTS.md

Personal portfolio built with Symfony (see `composer.json` for the Symfony and PHP versions,
`symfony.lock` for the recipes). Installed: Doctrine ORM on MariaDB, Twig, AssetMapper and
Tailwind, Symfony UX Icons, EasyAdmin, SecurityBundle with 2FA, Mailer, Altcha, Gedmo extensions.
No API Platform, no Messenger.

## Repository conventions

- Everything in the repository is written in English: code, comments, docs, commit messages.
- Branches: `cno_<topic>` from `develop`; `develop` → `main` → `production` (deployment only).
- Every command runs in the Docker `app` container through Task: run `task` for the list,
  never `symfony serve`. Use `task symfony:console -- <command>` and
  `task symfony:composer -- <command>` instead of calling `bin/console` or `composer` on the host.
- Secrets live in `.env.local` only. Never read them out, never commit them.
- Before saying a change is done, run `task qa` (add `fix=1` to apply the coding standards).

## Adding features: Flex, not hand-wiring

Install new capabilities with `task symfony:composer -- require <package>` and let the Flex
recipe register the bundle and generate its config. Don't hand-edit `config/bundles.php` or
hand-write a bundle's base config. Don't skip a good-fit component just because it isn't
installed yet; installing it is one command.

## Conventions

Follow <https://symfony.com/doc/current/best_practices.html> to write idiomatic
Symfony:

- Use PHP attributes for framework metadata, and not only on controllers:
  `#[Route]`, `#[MapRequestPayload]`, `#[IsGranted]` on actions, `#[Assert\...]`
  on properties, `#[AsCommand]`, `#[AsEventListener]`, `#[AsMessageHandler]`, and
  `#[AsAlias]` / `#[AsTaggedItem]` / `#[Autoconfigure]` on services. No YAML or
  XML routing.
- Rely on autowiring and autoconfiguration. Type-hint constructor arguments and
  let the container resolve them. Where a type-hint can't express it, stay in the
  class with `#[Autowire]` (parameters, env vars, expressions) or `#[Target]` (one
  of several implementations of an interface). A YAML service definition is the
  last resort, not the first.
- Controllers extend `AbstractController`, stay thin, and delegate to services.
- Use the framework for what it already does: Form for server-rendered forms,
  Validator for validation, Serializer for JSON, Messenger for async work,
  Security (voters, authenticators) for access control, Twig `path()`/`url()`
  instead of hardcoded URLs.
- Before hand-writing infrastructure (locks, queues, caches, HTTP clients,
  mailers, schedulers) or reaching for a third-party library, check whether a
  Symfony component covers it. It usually does.

Three specifics worth spelling out, because they are easy to get wrong:

- Bind request data with `#[MapRequestPayload]` / `#[MapQueryString]` on action
  arguments, which wires up Serializer and Validator for you, instead of calling
  `json_decode()` or `SerializerInterface` by hand. If neither package is
  installed yet, `composer require` them rather than falling back to manual
  parsing.
- Use constructor property promotion, and `readonly` for DTOs and value objects.
- Use `symfony/lock` (`LockFactory`) for mutual exclusion. A hand-built flag or
  lock file looks fine in review and is usually wrong under concurrency.

## Everyday workflow

- Start the stack with `task up`; the site is on `http://localhost:$APP_PORT`, the back office
  on `/backstage`. After the `app` container is recreated, nginx may answer 502:
  `docker compose restart engine`.
- When something fails, read `var/log/dev.log` and the web profiler (`/_profiler`) before
  changing code.
- Prefer `task symfony:console -- make:*` with every argument passed up front and
  `--no-interaction`: makers prompt on a terminal by default, which hangs a non-interactive
  shell. If a maker still needs interactive input, hand-write the code instead.
- Schema changes go through migrations (`task db:diff`, then `task db:migrate`), never
  `doctrine:schema:update` or hand-written SQL.
- `.env` is committed and holds defaults only. Real secrets belong in `.env.local`
  (git-ignored), read via `%env(...)%`.

## Testing

- `tests/Unit`: plain `TestCase`, never boots the kernel, mocks the dependencies.
- `tests/Integration`: `KernelTestCase` or `WebTestCase`, reads the test database.
- `tests/UI`: Panther tests, run by `task test:ui`.
- Each folder is a PHPUnit suite (`Unit`, `Integration`, `UI`); a bare `phpunit` runs Unit and Integration.
- `tests/bash`: Bats tests of the shell scripts, run on the host by `task test:bash`.

The test database is loaded with fixtures generated into `src/DataFixtures/Testing`
(`task test:prepare`, never edit them by hand). A feature isn't done until it has a test that
exercises it the way a caller would: an HTTP request for a controller, a service call for a
service, not just "it didn't throw." The architecture rules (`task test:arch`) and Deptrac
layers (`task quality:deptrac`) must stay green.

## Code style

PHP CS Fixer, Twig CS Fixer and Biome, configured in `.tools/config/`: `task cs:all fix=1`.
PHPStan runs at level 6 (`task quality:phpstan`). Rector suggestions are applied deliberately
(`task quality:rector fix=1`), never as part of an unrelated change.

## Discover, don't guess

Framework APIs change between versions and your training data may be stale. Look things up in
the project instead of relying on memory:

- `task symfony:console -- about`: versions, environment, paths.
- `debug:router`, `debug:container`, `debug:autowiring <name>`, `debug:config <bundle>`,
  `config:dump-reference <bundle>`: what exists and how it is configured.
- `lint:container`, `lint:twig templates/`, `lint:yaml config/`: validate before running.
- Read the installed source and docblocks under `vendor/`.
- Docs: <https://symfony.com/doc/current/> (switch to the version matching `composer.json`).

## Commits

Conventional Commits, enforced by the GrumPHP commit-msg hook and by commitlint in CI:
`<type>(<scope>): <subject>`, types `build`, `chore`, `ci`, `docs`, `feat`, `fix`, `perf`, `refactor`, `revert`,
`style`, `test`; lower-case subject, 72 characters max, no final period; body lines of 100 characters max.
The hooks run in the `app` container, so the containers must be running to commit.
Never bypass hooks with `--no-verify`.
