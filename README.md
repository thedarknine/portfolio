# Caroline Noyer - Portfolio & Lab 🚀

Bienvenue sur le dépôt de mon site personnel et portfolio (**carolinenoyer.fr**).

Ce projet va bien au-delà d'une simple vitrine : c'est un véritable **laboratoire technique** qui me sert de
terrain d'expérimentation pour appliquer les meilleures pratiques de développement web full-stack, de
l'architecture backend au design moderne, en passant par l'automatisation de la qualité de code.

---

## 🛠️ Stack Technique

### Backend & Base de données

* **PHP >= 8.4** (Utilisation native des nouveautés du langage, modernisation et typage strict)
* **Symfony 8.0.x** (Architecture découplée avec contrôleurs optimisés, gestion des assets via AssetMapper)
* **Doctrine ORM 3.x** & **MySQL** (Gestion stricte des entités, migrations et fixtures)

### Frontend

* **Tailwind CSS v4** (Design moderne basé sur le *Glassmorphism*, animations CSS fluides via `animate.css`
et transitions soignées)
* **Twig 3.x** (Moteur de templates structuré pour une intégration propre des composants)

### DevOps & Outillage

* **Docker & Docker Compose** (Environnement multi-conteneurs : `app` (PHP), `db` (MySQL), et `engine` (Nginx))
* **Makefile** (Point d'entrée unique et automatisé pour toutes les commandes du projet)

---

## 📐 Qualité de Code & Automatisation

Pour garantir la maintenabilité et la robustesse de l'application, une suite complète d'outils d'analyse
statique et d'automatisation est intégrée via **GrumPHP** (les configurations sont centralisées dans le
dossier `.tools/`).

Le projet intègre notamment :

* **PHPStan 2.x** (Analyse statique avec configuration stricte pour Doctrine)
* **PHP-CS-Fixer** (Respect des standards de code PSR-12 / PER)
* **Twig-CS-Fixer** (Linter et formateur pour les fichiers de templates)
* **Rector** (Refactoring automatisé pour le maintien du code aux normes de PHP 8.4)
* **Deptrac** (Analyse des dépendances architecturales et étanchéité des couches)
* **Infection** (Mutation Testing pour valider la pertinence et la force des tests)
* **PHPArkitect** (Validation automatique des règles de design architectural)
* **Biome** & **Linters Node** (Formatage et linting ultra-rapide des assets front avec ESLint)
* **Checkmake** (Linter intégré pour valider la syntaxe et la propreté du `Makefile`)

---

## 🧰 Commandes du Makefile

Le projet est entièrement piloté par un `Makefile` situé à la racine. Il sert d'interface unique pour
orchestrer les conteneurs Docker, le serveur de développement et les outils de QA.

*(Cette section sera automatiquement écrasée et mise à jour textuellement lors de l'exécution de la commande `make readme`)*

<!-- MAKEFILE:START -->
```bash

  Portfolio (dev)
  ───────────────────────────────────────────────────────────────────────────────

  Usage:
    make <target> [OPTION=value]

  Available targets:
    cs-makefile               Lint Makefile with checkmake                   

  DOCKER
    build                     Docker build                                   
    up                        Run Docker containers                          
    down                      Stop Docker containers                         
    destroy                   Stop and remove Docker containers              
    restart                   Docker restart                                 FORCE=1 to destroy and rebuild
    shell                     Run a shell in the PHP container               
    logs                      Show Docker logs                               LOGS_SERVICE=app|db|nginx
    update-project            Update docker, composer and pnpm dependencies in a safe way  
    check-project             Check project security audit                   

  CHECKERS
    doctor                    Check system requirements and project health   
    check-tools               Check if required CLI tools are installed      
    check-docker              Check Docker is running                        
    check-containers          Check if Docker containers are running         
    check-ports               Check if required ports are available or used by this project  
    check-env                 Check .env file                                
    check-dependencies        Check if PHP dependencies - vendor directory - are installed  
    check-tools-directory     Check if tools configuration directory exists  

  AUDIT
    audit                     Run audit                                      AUDIT_ENV=prod|dev

  DEVELOPMENT
    cc                        Run bin/console cache:clear from docker        CC_ENV=prod|dev|test
    composer-clear            Clear Composer cache and reinstall dependencies  
    pnpm-clear                Clear pnpm cache and reinstall dependencies    
    pnpm-prune                Prune unused packages                          
    clean                     Clean temporary files: cache, coverage, logs, public build  
    clean-build               Clean only build artifacts                     
    clean-cache               Clean only cache and logs                      
    clean-test                Clean PHPUnit cache and code coverage          
    watch                     Watch Tailwind CSS changes and re-build        
    secret                    Generate a new APP_SECRET and display it       
    migration                 Run Doctrine migrations                        
    fixtures                  Load Doctrine fixtures                         
    cs                        Check all coding standards: PHP, Twig, CSS     CS_TARGET=all|php|yaml|twig|front
    cs-php                    PHP CS Fixer                                   PHP_FIX=1 to actually fix
    cs-yaml                   Validate YAML files                            
    cs-twig                   Twig CS Fixer                                  TWIG_FIX=1 to actually fix
    cs-front                  Run linters for CSS and JS                     FRONT_FIX=1 to actually fix
    cs-fix                    Fix all coding standards: PHP, Twig, CSS       
    lint                      Run all linting checks                         
    lint-shell                Run shellcheck on shell scripts                
    lint-docker               Validate Dockerfiles with hadolint             
    lint-md                   Validate Markdown files with markdownlint      

  TESTS
    pre-commit                Run pre-commit checks                          
    grum-install              Install GrumPHP hooks                          
    grum-run                  Run GrumPHP checks                             
    qa                        Run complete Quality Assurance suite: Lint, Static Analysis, Tests  
    test                      Run PHPUnit tests without UI tests             
    test-bash                 Run Bats tests                                 
    qa-analyse                Run static analysis                            
    qa-rector                 Run Rector                                     REC_FIX=1 to actually fix
    test-mutation             Run Infection                                  
    test-arch                 Run phparkitect                                
    test-ui                   Run PHPUnit tests for UI group                 
    cover                     Run PHPUnit tests with coverage                

  ENVIRONMENT
    db-dump                   Dump database                                  FILES_CLEAN=1 to clean previous
    deploy                    Full production deployment - build + push to remote  
    prod-build                Build and commit assets to production branch   
    prod-deploy               Deploy to remote server - requires assets already built  
    dev                       Switch back to development environment         

  HELP
    readme                    Update README.md Makefile section              
    readme-check              Check if README.md is up to date               
    help                      Show this help message                         

  Examples:
    make test.             # Run PHPUnit tests (excluding UI)
    make cover.            # Run tests with coverage
    make cs-php PHP_FIX=1  # Fix PHP coding standards
    make qa                # Run full QA suite
    make deploy            # Deploy to production
    make dev               # Restore development environment

```
<!-- MAKEFILE:END -->

---

## 🧠 Cheat Sheet

Cette section regroupe les workflows, commandes natives Symfony/Docker et paquets utiles à conserver pour
l'initialisation ou la maintenance de projets similaires.

### 🛠️ Initialisation d'un nouveau projet (Dans Docker)

```bash
   # Configure Git identity inside the container
    git config --global user.email "you@example.com"
    git config --global user.name "Your Name"

    # Ignore formatting commits (CS Fixer) in git blame
    git config blame.ignoreRevsFile .git-blame-ignore-revs

    # Check system requirements
    symfony check:requirements

    # Create project in a temporary folder and move it to root
    symfony new tmp --version="8.0.*"
    mv tmp/* . 
    # Note: Remember to check and merge hidden files (e.g., .gitignore, .env)

    # Initialize frontend
    php bin/console tailwind:init
    # php bin/console importmap:require jquery
```

### 🗄️ Gestion de la Base de données

```bash
    # Classic migration workflow
    make migration  # Runs make:migration + doctrine:migrations:migrate

    # Run the automated fixtures pipeline (Custom composer script)
    composer run pipeline:fixtures
    # This script generates test fixtures, drops the test database, recreates it, and loads the fixtures cleanly.

    # Complete database reset with purge and fixtures loading
    php bin/console doctrine:database:drop --force
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force
    php bin/console doctrine:fixtures:load
```

### 🧪 Environnement de Tests & CI

#### Préparation de la base de test (.env.test)

S'assurer d'avoir la variable adaptée (ex: `DATABASE_URL="mysql://test:test@127.0.0.1:3306/portfolio_test?serverVersion=8.1.0&charset=utf8mb4"`).

```bash
    # Manual connection to the DB container to initialize access
    docker compose exec db mysql -p

    # SQL commands to execute if necessary :
    CREATE USER 'test'@'%' IDENTIFIED BY 'test';
    CREATE DATABASE portfolio_integration_test;
    GRANT ALL PRIVILEGES ON portfolio_integration_test.* TO 'test'@'%';
    FLUSH PRIVILEGES;

    # Initialize schema and fixtures for test environment
    php bin/console doctrine:schema:update --force --env=test
    php bin/console doctrine:fixtures:load --env=test
```

#### Exécution des tests

```bash
    # Generate a new test
    php bin/console make:test WebTestCase PageControllerTest    

    # Run PHPUnit test suite
    php bin/phpunit

    # Run tests with HTML coverage report generation
    make cover  # Runs PHPUnit and generates the report in the coverage/ folder (php bin/phpunit --coverage-html coverage)

    # Run mutation tests (Infection)
    make test-mutation

    # Run End-to-End / interface tests (Panther + Chrome Driver via BDI)
    make test-ui
```

### 🔍 Outils avancés de Quality Assurance

```bash
    # Run everything at once via Makefile
    make qa

    # Deptrac (Analysis of architectural dependencies and layers)
    composer require --dev qossmic/deptrac-shim
    vendor/bin/deptrac init
    vendor/bin/deptrac analyse --config-file=deptrac.yaml
    vendor/bin/deptrac debug:layer --config-file=.tools/deptrac.yaml

    # Rector (Analysis of refactoring rules / Dry-run)
    composer require --dev rector/rector
    vendor/bin/rector process --dry-run --config .tools/rector.php
```

### 🏗️ Backoffice & Sécurité (EasyAdmin)

```bash
    # Installation and initialization of Dashboard and CRUDs
    composer require easycorp/easyadmin-bundle
    php bin/console make:admin:dashboard
    php bin/console make:admin:crud

    # Securing access
    php bin/console make:user
    php bin/console make:security:form-login

    # Managing sortable fields
    composer require gedmo/doctrine-extensions
    composer require stof/doctrine-extensions-bundle
```

### 🚀 Optimisation Frontend (AssetMapper & Tailwind)

```bash
    # Run Tailwind watcher in background to compile on the fly
    make watch

    # Compilation and minification of assets managed via AssetMapper
    php bin/console asset-map:compile
```

## 📦 Inventaire des Dépendances

### Packages Standards

* `logger` & `symfony/twig-bundle` : Logs et moteur de rendu.
* `symfony/asset` & `symfony/asset-mapper` : Gestion moderne des assets sans Node.js.
* `symfony/twig-pack` & `twig/intl-extra` : Extensions Twig avancées (dates, filtres internationaux).
* `symfonycasts/tailwind-bundle` : Intégration transparente de Tailwind CSS.
* `symfony/ux-icons` : Icônes SVG via AssetMapper.
* `symfony/orm-pack` & `doctrine/doctrine-migrations-bundle` : Persistance de données.
* `gedmo/doctrine-extensions` : Ajout de comportements (Sluggable, Timestampable).
* `nesbot/carbon` : Manipulation avancée et fluide des dates.
* `symfony/mailer` : Envoi d'emails.
* `tito10047/altcha-bundle` : Protection anti-spam via Altcha.
* `easycorp/easyadmin-bundle` : Moteur de Backoffice fluide et moderne (v5).
* `scheb/2fa-google-authenticator` : Authentification à deux facteurs via Google Authenticator.
* `symfony/rate-limiter` : Limitation des tentatives de connexion.
* `presta/sitemap-bundle` : Génération automatique des sitemaps XML.

### Packages de Développement / QA

* `symfony/maker-bundle` : Générateur de code de l'écosystème.
* `phpunit/phpunit` & `symfony/test-pack` : Framework de tests.
* `dama/doctrine-test-bundle` : Isolation des tests en base de données via des transactions.
* `friendsofphp/php-cs-fixer` & `vincentlanglet/twig-cs-fixer` : Linters et formateurs de code PHP/Twig.
* `phpstan/phpstan` & `phpstan/phpstan-doctrine` : Analyse statique rigoureuse.
* `phpro/grumphp` : Gestionnaire de Git Hooks pour valider le code avant le commit.
* `doctrine/doctrine-fixtures-bundle` : Génération de fausses données pour le développement.
* `infection/infection` : Outil de mutation testing.
* `phparkitect/phparkitect` : Validation des règles de design architectural.
* `deptrac/deptrac` : Contrôle de l'étanchéité des couches logicielles.
* `symfony/panther` & `dbrekelmans/bdi` : Tests End-to-End et UI avec un vrai navigateur (Chrome Driver).

## 🎨 Librairies Frontend & Linters d'intégration

* ESLint → JS
* Biome → CSS/Tailwind

```bash
    # Add and declaration of UI/Animation libraries via AssetMapper
    php bin/console importmap:require animate.css
    php bin/console importmap:require typed.js
    php bin/console importmap:require photoswipe
    php bin/console importmap:require photoswipe/lightbox

    # Linters tools Node (ESLint, Biome)
    pnpm add -D eslint @eslint/js globals 
    pnpm exec eslint --init
    pnpm add -D @biomejs/biome
    pnpm add -D eslint-plugin-tailwindcss
    pnpm add -D @html-eslint/parser
```

## 🎨 Ressources Graphiques & Icônes

* Package [UX Icons](https://ux.symfony.com/icons) : Icônes SVG via AssetMapper.

```bash
    php bin/console importmap:require @symfony/ux-icons
    php bin/console debug:config ux_icons

    # Search icons
    symfony console ux:icons:search flowbite pdf
    symfony console ux:icons:search fa7-solid pdf
    symfony console ux:icons:search fa7-brands github

    # Import icons
    symfony console ux:icon:import fa7-solid:file-pdf
```

* [IconStack](https://iconstack.io/) — Découverte et gestion de sets d'icônes.
* [HugeIcons](https://hugeicons.com/icons/stroke-rounded?search=inst) — Icônes filaires haut de gamme.

## 📊 Librairies d'audit SEO

* lighthouse
* unlighthouse
* pa11y pa11y-ci

* [Lighthouse](https://developer.chrome.com/docs/lighthouse/overview/) — Audit d'accessibilité et performance.
* [PageSpeed Insights](https://pagespeed.web.dev/) — Analyse de la performance des pages web.

---

## 📝 Licence

Ce projet est personnel et son contenu textuel/visuel m'appartient. Le code source est partagé à des fins de
démonstration technique.
