.DEFAULT_GOAL := help
.ONESHELL:
SHELL := /bin/bash
.SHELLFLAGS := -eu -o pipefail -c
export LANG := C.UTF-8
export LC_ALL := C.UTF-8

# Required environment variables for production deployment:
# REMOTE_USER - SSH user
# REMOTE_HOST - Remote server hostname
# REMOTE_SSH_KEY - Path to SSH private key
# REMOTE_PORT - SSH port (default: 22)
# DB_ROOT_PASSWORD - Database root password
# DB_NAME - Database name

MAKEFLAGS += --no-print-directory

# =====================================================================
# GNU MAKE VERSION CHECK
# =====================================================================
MIN_MAKE_VERSION := 3.82

ifeq (,$(filter oneshell,$(.FEATURES)))
$(error GNU Make >= 3.82 is required (.ONESHELL not supported). \
    On macOS install with brew install make. \
    Then add export PATH="/opt/homebrew/opt/make/libexec/gnubin:$$PATH" to ~/.zshrc)
endif

ifneq ($(MIN_MAKE_VERSION),$(firstword $(sort $(MAKE_VERSION) $(MIN_MAKE_VERSION))))
$(error GNU Make >= $(MIN_MAKE_VERSION) required. Current is $(MAKE_VERSION). On macOS run brew install make)
endif

-include .env
ENVIRONMENT ?= dev
PROJECT_NAME ?= Portfolio

NOW = $$(date +%Y%m%d_%H%M%S)
TODAY = $$(date +%Y%m%d)

# Manage Docker container execution flags for CI compatibility
TTY_FLAG := $(shell test -t 0 && echo "-it" || echo "-T")

# =====================================================================
# CONFIGURATION
# =====================================================================

# Docker configuration and commands
DOCKER_APP_SERVICE := app
DOCKER_DB_SERVICE := db
DOCKER_NGINX_SERVICE := engine
DOCKER_PORTS := 8000 3306 8088 4444

#= GLOBAL VARIABLES ===================================================
TOOLS_CONFIG_DIR	:= .tools
SCRIPTS_DIR 		:= .tools/scripts
REPORTS_DIR 		:= .tools/reports
TARGET_MAX_CHAR_NUM	:= 24
DESC_MAX_CHAR_NUM	:= 45

#= COMMAND SHORTCUTS ==================================================
DC			:= docker compose
DC_EXEC		:= $(DC) exec $(TTY_FLAG) $(DOCKER_APP_SERVICE)

SYMFONY		:= $(DC_EXEC) php bin/console
SYMFONY_PROD:= $(DC_EXEC) -e APP_ENV=prod php bin/console
COMPOSER	:= $(DC_EXEC) composer
PNPM		:= $(DC_EXEC) pnpm
PNPX		:= $(DC_EXEC) pnpx
GRUMPHP		:= $(DC_EXEC) vendor/bin/grumphp --config=$(TOOLS_CONFIG_DIR)/grumphp.yml
PHPUNIT		:= $(DC_EXEC) php vendor/bin/phpunit --configuration $(TOOLS_CONFIG_DIR)/phpunit.xml
INFECTION	:= $(DC_EXEC) php vendor/bin/infection --configuration=$(TOOLS_CONFIG_DIR)/infection.json
PHPARKITECT	:= $(DC_EXEC) php vendor/bin/phparkitect --config=$(TOOLS_CONFIG_DIR)/phparkitect.php
DEPTRAC		:= $(DC_EXEC) vendor/bin/deptrac --config-file=$(TOOLS_CONFIG_DIR)/deptrac.yaml
PHPSTAN		:= $(DC_EXEC) php vendor/bin/phpstan --memory-limit=512M --configuration=$(TOOLS_CONFIG_DIR)/phpstan.neon
RECTOR		:= $(DC_EXEC) vendor/bin/rector process --config $(TOOLS_CONFIG_DIR)/rector.php
CSPHP		:= $(DC_EXEC) php vendor/bin/php-cs-fixer --config=$(TOOLS_CONFIG_DIR)/.php-cs-fixer.php --cache-file=$(TOOLS_CONFIG_DIR)/.php-cs-fixer.cache
CSTWIG		:= $(DC_EXEC) vendor/bin/twig-cs-fixer --config=$(TOOLS_CONFIG_DIR)/.twig-cs-fixer.php
ESLINT		:= $(DC_EXEC) pnpm exec eslint --config $(TOOLS_CONFIG_DIR)/eslint.config.mjs
BIOME		:= $(DC_EXEC) pnpm biome check --config-path=$(TOOLS_CONFIG_DIR)/biome.json

#= COLORS =============================================================
BLACK        := \033[0;30m
RED          := \033[0;31m
GREEN        := \033[0;32m
YELLOW       := \033[0;33m
BLUE         := \033[0;34m
CYAN         := \033[0;36m
PURPLE       := \033[0;35m
WHITE        := \033[0;37m
BG_GREEN     := \033[42m
BG_YELLOW    := \033[43m
BG_RED       := \033[41m
BG_BLUE      := \033[44m
BG_CYAN      := \033[46m
BOLD         := \033[1m
RESET        := \033[0m

#= FONCTIONS ===========================================================

# Avoid some execution from prod environment
define assert_not_prod
if [ "$(ENVIRONMENT)" = "prod" ] || [ "$(ENVIRONMENT)" = "production" ]; then 
	display_error "Authorised only in development environment"
	$(MAKE) _fatal msg="Authorised only in development environment"
fi
endef

.PHONY: _fatal
_fatal:
	$(error $(msg))

# =====================================================================

CHECKMAKE := $(DC_EXEC) checkmake --config=$(TOOLS_CONFIG_DIR)/checkmake.ini
CHECKMAKE_BIN := checkmake --config=$(TOOLS_CONFIG_DIR)/checkmake.ini

.PHONY: cs-makefile
cs-makefile: ## Lint Makefile with checkmake
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "📎 Checking if checkmake is installed..."
	success_msgs=()

	if [ -f /.dockerenv ]; then
		if ! command -v checkmake >/dev/null 2>&1; then
			display_error "checkmake is not installed in the Docker container."
			$(MAKE) _fatal msg="checkmake is not installed in the Docker container"
		fi

		display_subtitle "🔄 Running checkmake..."
		$(CHECKMAKE_BIN) Makefile
	else
		if ! $(DC) exec -T app bash -c "command -v checkmake" >/dev/null 2>&1; then
			display_error "checkmake is not installed in the Docker container. Please rebuild it."
			$(MAKE) _fatal msg="checkmake is not installed in the Docker container. Please rebuild it."
		fi

		success_msgs+=("Checkmake is ready via Docker Compose")

		display_subtitle "🔄 Running checkmake via Docker..."
		$(DC) exec -T app $(CHECKMAKE_BIN) Makefile

		success_msgs+=("Checkmake passed")
		success_msgs+=("Makefile formatting is perfect!")

		display_success "$${success_msgs[@]}"
	fi

# =====================================================================
##@ DOCKER
.PHONY: build
build: ## Docker build
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🏗️ Building Docker"

	$(DC) build
	display_success "Docker built successfully"

.PHONY: up
up: ## Run Docker containers
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🐳 Starting containers"

	$(DC) up -d
	display_success "Containers started successfully"

.PHONY: down
down: ## Stop Docker containers
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🐳 Stopping containers"

	$(DC) down
	display_success "Containers stopped successfully"

.PHONY: destroy
destroy: ## Stop and remove Docker containers
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🐳 Stopping and removing containers"

	$(DC) down --remove-orphans
	display_success "Containers destroyed successfully"

.PHONY: restart
FORCE ?= 0
restart: ## Docker restart (FORCE=1 to destroy and rebuild)
	@source $(SCRIPTS_DIR)/utils.sh
	success_msgs=()
	start_time=$$(date +%s)

	if [ "$(FORCE)" = "1" ]; then
		$(MAKE) destroy
		success_msgs+=("Project destroyed")

		$(MAKE) build
		success_msgs+=("Project built")

		$(MAKE) up
		success_msgs+=("Containers started successfully")
	else
		$(MAKE) down
		success_msgs+=("Containers stopped")

		$(MAKE) up
		success_msgs+=("Containers restarted successfully")
	fi

	display_elapsed "$$start_time"
	display_success "$${success_msgs[@]}"

.PHONY: shell
shell: ## Run a shell in the PHP container
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🐚 Running shell in PHP container"

	$(DC_EXEC) bash

.PHONY: logs
LOGS_SERVICE ?= all
logs: ## Show Docker logs (LOGS_SERVICE=app|db|nginx)
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🪲 Displaying Docker logs"

	if [ "$(LOGS_SERVICE)" = "all" ]; then
		$(DC) logs -f --tail=100
	else
		$(DC) logs -f --tail=100 $(LOGS_SERVICE)
	fi

.PHONY: update-project
update-project: ## Update docker, composer and pnpm dependencies in a safe way
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	success_msgs=()
	start_time=$$(date +%s)

	display_title "⬇️ Downloading latest Docker images..."
	$(DC) pull
	success_msgs+=("Docker images updated")

	display_title "🏗️ Rebuilding and restarting containers..."
	$(DC) down
	$(DC) up -d --build
	success_msgs+=("Containers rebuilt and restarted")

	display_title "🔄 Updating Composer dependencies (PHP)..."
	$(DC_EXEC) composer update
	success_msgs+=("Composer dependencies updated")

	display_title "🔄 Updating pnpm dependencies (JS/CSS)..."
	$(DC_EXEC) pnpm self-update
	$(DC_EXEC) pnpm update
	success_msgs+=("PNPM dependencies updated")

	display_elapsed "$$start_time"
	display_success "$${success_msgs[@]}"

# =====================================================================
##@ CHECKERS

.PHONY: doctor
doctor: ## Check system requirements and project health
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🪲 Running health check"
	success_msgs=()
	start_time=$$(date +%s)

	$(MAKE) check-tools
	success_msgs+=("Tools are installed")

	$(MAKE) check-docker
	success_msgs+=("Docker is running")

	$(MAKE) check-containers
	success_msgs+=("Containers are running")

	$(MAKE) check-env
	success_msgs+=("Environment is configured")

	$(MAKE) check-ports
	success_msgs+=("Ports are available")

	$(MAKE) check-dependencies
	success_msgs+=("Dependencies are installed")
	
	$(MAKE) check-tools-directory
	success_msgs+=("Tools are configured")

	success_msgs+=("All systems are ok! The project is ready.")
	display_elapsed "$$start_time"
	display_success "$${success_msgs[@]}"

.PHONY: check-tools
check-tools: ## Check if required CLI tools are installed
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔎 Checking required tools..."
	command -v docker >/dev/null || { display_error "docker not found"; exit 1; }
	command -v jq >/dev/null || { display_error "jq not found"; exit 1; }
	command -v lsof >/dev/null || { display_error "lsof not found"; exit 1; }
	if ! command -v bats &> /dev/null; then
		display_error "Bats is not installed. To install Bats :" \
			"   On macOS : brew install bats-core" \
			"   On Linux (Debian/Ubuntu) : sudo apt-get install bats"
		exit 1
	fi
	display_success "All required tools are installed"

.PHONY: check-docker
check-docker: ## Check Docker is running
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔎 Checking Docker daemon..."

	if ! docker info >/dev/null 2>&1; then
		display_error "Docker daemon unavailable. Please start Docker Desktop."
		$(MAKE) _fatal msg="Docker daemon is not running."
	fi
	display_success "Docker is running."

.PHONY: check-containers
check-containers: ## Check if Docker containers are running
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔎 Checking Docker containers..."

	if ! command -v jq >/dev/null 2>&1; then
		display_error "jq is required but not installed. Install it with: brew install jq"
		$(MAKE) _fatal msg="jq is required but not installed"
	fi
	if ! $(DC) ps --format json | jq -e -s 'any(.[]; .Service == "app" and (.Status | startswith("Up")))' > /dev/null; then
		display_error "Containers are not running. Use 'make up' to start them" && \
		$(MAKE) _fatal msg="Containers are not running."
	fi
	display_success "Containers are running."

.PHONY: check-ports
check-ports: ## Check if required ports are available or used by this project
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔎 Checking network ports..."

	ports_conflict=0
	success_msgs=()
	current_project_ids=$$(docker compose ps -q 2>/dev/null | tr '\n' ' ')
	for port in $(DOCKER_PORTS); do
		blocking_pid=$$(lsof -Pi :$$port -sTCP:LISTEN -t 2>/dev/null)
		if [ ! -z "$$blocking_pid" ]; then
			container_owning_port=$$(docker ps -q --no-trunc --filter "publish=$$port" 2>/dev/null)
			if [ ! -z "$$container_owning_port" ]; then
				if echo "$$current_project_ids" | grep -q -w "$$container_owning_port"; then
					success_msgs+=("Port $$port is used by this project - already up")
				else
					success_msgs+=("Port $$port is blocked by ANOTHER Docker project")
					ports_conflict=1
				fi
			else
				success_msgs+=("Port $$port is blocked by ANOTHER native application - PID: $$blocking_pid")
				ports_conflict=1
			fi
		else
			success_msgs+=("Port $$port is free and ready")
		fi
	done
	if [ $$ports_conflict -eq 1 ]; then
		display_error "Some ports are blocked by other services. Run 'make down' elsewhere."
		$(MAKE) _fatal msg="Some ports are blocked by other services."
	fi
	display_success "$${success_msgs[@]}"

.PHONY: check-env
check-env: ## Check .env file
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔎 Checking .env file..."
	
	if [ ! -f .env ]; then
		display_error ".env file is missing. Please create it from .env.sample."
		$(MAKE) _fatal msg=".env file is missing."
	fi
	display_success ".env file exists."

.PHONY: check-dependencies
check-dependencies: ## Check if PHP dependencies - vendor directory - are installed
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔎 Checking PHP dependencies..."

	if $(DC) ps app | grep -q "Up"; then
		if ! $(DC_EXEC) [ -d vendor ]; then
			display_error "'vendor' directory is missing. Run installation."
			$(MAKE) _fatal msg="PHP dependencies (vendor) are missing."
		else
			display_success "PHP dependencies - vendor - are installed."
		fi
	else
		display_warning "Containers are not running. Cannot check 'vendor' (run 'make up')."
	fi

.PHONY: check-tools-directory
check-tools-directory: ## Check if tools configuration directory exists
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔎 Checking tools directory..."
	
	if [ ! -d $(TOOLS_CONFIG_DIR) ]; then
		display_error ".tools directory is missing."
		$(MAKE) _fatal msg=".tools directory is missing."
	fi
	display_success "Tools directory exists."

# =====================================================================
##@ AUDIT

.PHONY: audit
AUDIT_ENV ?= dev
audit: ## Run audit (AUDIT_ENV=prod|dev)
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🔍 Local audit"
	
# 	$(DC_EXEC) bash ./.tools/scripts/audit.sh
	@./$(SCRIPTS_DIR)/audit.sh $(AUDIT_ENV)

# =====================================================================
##@ DEVELOPMENT

.PHONY: cc
CC_ENV ?= 
cc: ## Run bin/console cache:clear from docker (CC_ENV=prod|dev|test)
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🧹 Clearing Symfony cache"

	if [ -n "$(CC_ENV)" ]; then
		$(SYMFONY) "cache:clear" --env=$(CC_ENV)
	else
		$(SYMFONY) "cache:clear"
	fi
	display_success "Cache cleared successfully."

.PHONY: composer-clear
composer-clear: ## Clear Composer cache and reinstall dependencies
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🧹 Clearing Composer cache and reinstalling dependencies"

	rm -rf vendor/
	$(COMPOSER) clear-cache
	$(COMPOSER) install
	display_success "Composer cache cleared and dependencies reinstalled."

.PHONY: pnpm-clear
pnpm-clear: ## Clear pnpm cache and reinstall dependencies
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🧹 Clearing pnpm cache and reinstalling dependencies"

	rm -rf node_modules/
	$(PNPM) store prune
	$(PNPM) install
	display_success "pnpm cache cleared and dependencies reinstalled."

.PHONY: pnpm-prune
pnpm-prune: ## Prune unused packages
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🧹 Pruning unused packages"

	display_subtitle "🧹 Removing unused packages..."
	$(PNPM) store prune
	display_subtitle "🔍 Checking for unused packages..."
	$(PNPX) depcheck --ignores="animate.css,@biomejs/biome"
	display_success "Unused packages check completed."

.PHONY: clean
clean: ## Clean temporary files: cache, coverage, logs, public build
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🧹 Cleaning temporary files"

	$(MAKE) clean-build
	$(MAKE) clean-cache
	$(MAKE) clean-test
	display_success "Temporary files cleaned."

.PHONY: clean-build
clean-build: ## Clean only build artifacts
	@rm -rf public/build/* public/assets/*

.PHONY: clean-cache
clean-cache: ## Clean only cache and logs
	@rm -rf var/cache/* var/log/*

.PHONY: clean-test
clean-test: ## Clean PHPUnit cache and code coverage
	@rm -rf coverage/ .phpunit.result.cache

.PHONY: watch
watch: ## Watch Tailwind CSS changes and re-build
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🔍 Watching Tailwind CSS changes and re-building"

	$(SYMFONY) "tailwind:build" --watch

.PHONY: secret
secret: ## Generate a new APP_SECRET and display it
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🔑 Generating new APP_SECRET"

	secret=$$($(DC_EXEC) openssl rand -hex 32)
	display_success \
		"APP_SECRET generated successfully." \
		"$${GREEN}$${secret}$${RESET}"

.PHONY: migration
migration: ## Run Doctrine migrations
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	display_title "💾 Running Doctrine migrations"

	$(SYMFONY) "make:migration"
	$(SYMFONY) "doctrine:migrations:migrate"

.PHONY: fixtures
fixtures: ## Load Doctrine fixtures
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	display_title "💾 Loading Doctrine fixtures"
	
	$(SYMFONY) "doctrine:fixtures:load"

.PHONY: cs
CS_TARGET ?= all
cs: ## Check all coding standards: PHP, Twig, CSS (CS_TARGET=all|php|yaml|twig|front)
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🎨 Checking coding standards"
	
	if [ "$(CS_TARGET)" = "all" ]; then 
		success_msgs=()
		start_time=$$(date +%s)

		$(MAKE) cs-php
		success_msgs+=("PHP coding standards check completed.")

		$(MAKE) cs-yaml
		success_msgs+=("YAML linting completed.")

		$(MAKE) cs-twig
		success_msgs+=("Twig coding standards check completed.")

		$(MAKE) cs-front
		success_msgs+=("CSS and JS coding standards check completed.")

		display_elapsed "$$start_time"
		display_success "$${success_msgs[@]}"
	else
		$(MAKE) cs-$(CS_TARGET)
	fi

.PHONY: cs-php
PHP_FIX ?= 0
cs-php: ## PHP CS Fixer (PHP_FIX=1 to actually fix)
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	if [ "$(PHP_FIX)" = "1" ]; then
		display_subtitle "🪛 PHP CS Fixer in verbose mode..."
		$(CSPHP) fix --verbose
		display_success "PHP coding standards check completed."
	else
		display_subtitle "🔄 Dry running PHP coding standards..."
		$(CSPHP) check --verbose
		display_subtitle "🔭 Running PHPStan analysis..."
		$(PHPSTAN) analyse
		display_success \
			"Dry running PHP coding standards completed." \
			"PHPStan analysis completed."
	fi

.PHONY: cs-yaml
cs-yaml: ## Validate YAML files
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	display_subtitle "🔭 Validate YAML files from config directory..."

	$(SYMFONY) "lint:yaml" config/
	display_success "YAML linting completed."

.PHONY: cs-twig
TWIG_FIX ?= 0
cs-twig: ## Twig CS Fixer (TWIG_FIX=1 to actually fix)
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	if [ "$(TWIG_FIX)" = "1" ]; then
		display_subtitle "🪛 Running Twig CS Fixer in fix mode..."
		$(CSTWIG) fix templates/
		$(SYMFONY) "lint:twig" templates/
	else
		display_subtitle "🔭 Dry running Twig CS Fixer and display diff..."
		$(CSTWIG) check templates/
	fi
	display_success "Twig coding standards check completed."

.PHONY: cs-front
FRONT_FIX ?= 0
cs-front: ## Run linters for CSS and JS (FRONT_FIX=1 to actually fix)
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	if [ "$(FRONT_FIX)" = "1" ]; then
		display_subtitle "🪛 Running ESLint in fix mode..."
		$(ESLINT) assets/scripts/ --fix
		display_subtitle "🪛 Running Biome in fix mode..."
		$(BIOME) --write assets/
	else
		display_subtitle "🔍 Running ESLint..."
		$(ESLINT) assets/scripts/
		display_subtitle "🔭 Running Biome..."
		$(BIOME) assets/
	fi
	display_success "Frontend coding standards check completed (ESLint + Biome)."

.PHONY: cs-fix
cs-fix: ## Fix all coding standards: PHP, Twig, CSS
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	display_title "🎨 Fixing coding standards"
	success_msgs=()
	start_time=$$(date +%s)

	$(MAKE) cs-php PHP_FIX=1
	success_msgs+=("PHP coding standards fixed.")

	$(MAKE) cs-twig TWIG_FIX=1
	success_msgs+=("Twig coding standards fixed.")
	
	$(MAKE) cs-front FRONT_FIX=1
	success_msgs+=("CSS and JS coding standards fixed.")
	
	display_elapsed "$$start_time"
	display_success "$${success_msgs[@]}"

.PHONY: cs-shell
cs-shell: ## Run shellcheck on shell scripts
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	display_title "🐚 Running shellcheck"
	$(DC_EXEC) shellcheck -x -P $(SCRIPTS_DIR)/*.sh
	$(DC_EXEC) shfmt -d $(SCRIPTS_DIR)/*.sh
	display_success "Shellcheck completed."

.PHONY: cs-docker
cs-docker: ## Validate Dockerfiles with hadolint
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	display_title "🐳 Validating Dockerfiles with hadolint"
	$(DC_EXEC) hadolint .docker/*.dockerfile
	display_success "Dockerfile validation completed."

.PHONY: cs-md
cs-md: ## Validate Markdown files with markdownlint
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	display_title "📝 Validating Markdown files"
	$(PNPX) markdownlint-cli2 --config .tools/markdownlint-cli2.yaml
	display_success "Markdown validation completed."

# =====================================================================
##@ TESTS

.PHONY: pre-commit
pre-commit: ## Run pre-commit checks
	@source $(SCRIPTS_DIR)/utils.sh
	if [ -f /.dockerenv ]; then
		display_subtitle "🔍 Running gitleaks"
		gitleaks detect --source . -v -c .tools/gitleaks.toml
	else
		display_subtitle "🔍 Running gitleaks"
		$(DC_EXEC) gitleaks detect --source . -v -c .tools/gitleaks.toml
	fi
	$(MAKE) cs-makefile
	$(MAKE) readme

.PHONY: grum-install
grum-install: ## Install GrumPHP hooks
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🪲 Installing GrumPHP hooks"

	$(GRUMPHP) "git:init"

.PHONY: grum-run
grum-run: ## Run GrumPHP checks
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🧪 Running GrumPHP checks"

	$(GRUMPHP) run

.PHONY: grum-pre-commit
grum-pre-commit:
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🧪 Running GrumPHP pre-commit hooks"
	
	$(GRUMPHP) "git:pre-commit"

.PHONY: qa
qa: ## Run complete Quality Assurance suite: Lint, Static Analysis, Tests
	@source $(SCRIPTS_DIR)/utils.sh
	$(call assert_not_prod)
	success_msgs=()
	start_time=$$(date +%s)

	$(MAKE) cs || exit 1
	success_msgs+=("PHP coding standards checked.")

	$(MAKE) qa-analyse || exit 1
	success_msgs+=("Static analysis passed.")
	
	$(MAKE) cover || exit 1
	success_msgs+=("Tests passed.")
	
	$(MAKE) test-mutation || exit 1
	success_msgs+=("Infection tests passed.")
	
	$(MAKE) test-arch || exit 1
	success_msgs+=("Architecture tests passed.")
	
	$(MAKE) test-ui || exit 1
	success_msgs+=("UI tests passed.")

	summary="$$(color_green "✨ QA passed successfully! Your code is amazing. ✨")"
	success_msgs+=("$${summary}")
	display_elapsed "$$start_time"
	display_success "$${success_msgs[@]}"

.PHONY: test
test: ## Run PHPUnit tests without UI tests
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🧪 Running PHPUnit tests"
	
	$(MAKE) check-containers
	display_subtitle "💾 Generating fixtures..."
	$(SYMFONY) "cache:clear" --env=test
	$(SYMFONY) "app:generate-fixtures" --group=test
	
	display_subtitle "🎬 Preparing test database..."
	- $(SYMFONY) "doctrine:schema:drop" --env=test --force --full-database
	$(SYMFONY) "doctrine:schema:update" --env=test --force
	$(SYMFONY) "doctrine:fixtures:load" --env=test --group=test --no-interaction
	
	display_subtitle "🧪 Running PHPUnit tests..."
	$(PHPUNIT) --exclude-group=UI

.PHONY: test-bash
test-bash: ## Run Bats tests
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🧪 Running Bats tests"
	bats tests/bash/

.PHONY: qa-analyse
qa-analyse: ## Run static analysis
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔎 Running static analysis..."
	success_msgs=()

	$(PHPSTAN) analyse
	success_msgs+=("PHPStan passed.")

	display_subtitle "🔎 Running Deptrac..."
	$(DEPTRAC) analyse
	success_msgs+=("Deptrac passed.")
	
	# display_subtitle "🔎 Running Rector..."
	# $(RECTOR) --dry-run
	# success_msg+=("Rector passed.")
	
	display_success "$${success_msgs[@]}"

.PHONY: qa-rector
REC_FIX ?= 0
qa-rector: ## Run Rector (REC_FIX=1 to actually fix)
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔎 Running Rector..."

	if [ "$(REC_FIX)" = "1" ]; then
		$(RECTOR)
	else
		$(RECTOR) --dry-run
	fi
	display_success "Rector analysis complete"

.PHONY: test-mutation
test-mutation: ## Run Infection
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🧪 Running Infection..."
	
	$(INFECTION) --threads=4
	display_success "Infection analysis complete"

.PHONY: test-arch
test-arch: ## Run phparkitect
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔬 Running PHPArkitect..."
	
	$(PHPARKITECT) check
	display_success "PHPArkitect analysis complete"

.PHONY: test-ui
test-ui: ## Run PHPUnit tests for UI group
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🧪 Running PHPUnit tests for UI group..."

	$(PHPUNIT) --group=UI
	display_success "PHPUnit UI tests complete"

.PHONY: cover
cover: ## Run PHPUnit tests with coverage
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🧹 Clearing cache..."

	$(SYMFONY) "cache:clear" --env=test
	display_subtitle "🧪 Running PHPUnit tests with coverage..."
	$(PHPUNIT) --coverage-html coverage/ --exclude-group=UI
	display_success "Coverage report generated in 'coverage/' directory."

# =====================================================================
##@ ENVIRONMENT
EXPORT_DIR=$(TOOLS_CONFIG_DIR)/export
ZIP_NAME=portfolio_prod-$(TODAY).zip
SQL_FILE=portfolio_db-$(NOW).sql
# CMD_PROD=zip -r archive-portfolio-$(TODAY).zip * -x analytics/* -x medias/movies/*

.PHONY: db-dump
FILES_CLEAN ?= 0
db-dump: ## Dump database (FILES_CLEAN=1 to clean previous)
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "💾 Dumping database..."

	if [ "$(FILES_CLEAN)" = "1" ]; then
		rm -f $(EXPORT_DIR)/*.sql
	fi
	$(DC) exec -i $(DOCKER_DB_SERVICE) mysqldump -uroot -p$(DB_ROOT_PASSWORD) $(DB_NAME) > $(EXPORT_DIR)/$(SQL_FILE)
	display_success "Database dumped to: $${GREEN}$(EXPORT_DIR)/$(SQL_FILE)$${RESET}"

.PHONY: deploy
# deploy.sh should be copied to remote server in user folder
# Find it into .tools/ directory if needed
deploy: ## Full production deployment - build + push to remote
	@source $(SCRIPTS_DIR)/utils.sh
	display_title "🚀 Starting full production deployment"
	@./$(SCRIPTS_DIR)/prod-build.sh
	@./$(SCRIPTS_DIR)/prod-deploy.sh
	$(MAKE) dev
	display_success "Full production deployment completed!"

.PHONY: prod-build
prod-build: ## Build and commit assets to production branch
	@./$(SCRIPTS_DIR)/prod-build.sh

.PHONY: prod-deploy
prod-deploy: ## Deploy to remote server - requires assets already built
	@./$(SCRIPTS_DIR)/prod-deploy.sh

.PHONY: dev
dev: ## Switch back to development environment
	@./$(SCRIPTS_DIR)/dev.sh

# =====================================================================
##@ HELP

.PHONY: readme
readme: ## Update README.md Makefile section
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔄 Updating README.md..."

	if [ ! -f README.md ]; then
		display_error "README.md not found"
		$(MAKE) _fatal msg="README.md not found"
	fi

	tmp_help=$$(mktemp)
	tmp_readme=$$(mktemp)
	
	echo '```bash' > $$tmp_help
	$(MAKE) help | sed -E 's/\x1B\[[0-9;]*[[:alpha:]]//g' >> $$tmp_help
	echo '```' >> $$tmp_help
	
	awk -v helpfile="$$tmp_help" '\
		/<!-- MAKEFILE:START -->/ { \
			print; \
			while ((getline line < helpfile) > 0) print line; \
			inblock=1; \
			next; \
		} \
		/<!-- MAKEFILE:END -->/ { \
			inblock=0; \
			print; \
			next; \
		} \
		!inblock \
	' README.md > $$tmp_readme
	
	mv $$tmp_readme README.md
	rm -f $$tmp_help
	git add README.md

	display_success "README.md updated successfully!"

.PHONY: readme-check
readme-check: ## Check if README.md is up to date
	@source $(SCRIPTS_DIR)/utils.sh
	display_subtitle "🔄 Checking README.md..."

	tmp=$$(mktemp)
	cp README.md $$tmp
	$(MAKE) readme > /dev/null
	if ! diff -q README.md $$tmp >/dev/null; then 
		mv $$tmp README.md
		display_warning "README.md is outdated. Run 'make readme'."
	else 
		display_success "README.md is up to date."
	fi
	rm -f $$tmp

.PHONY: help
help: ## Show this help message
	@printf '\n'
	@printf '  $(BOLD)$(YELLOW)$(PROJECT_NAME)$(RESET) $(BLUE)($(GREEN)$(ENVIRONMENT)$(BLUE))$(RESET)\n'
	@printf '  $(BLUE)───────────────────────────────────────────────────────────────────────────────$(RESET)\n'
	@printf '\n'
	@printf '  $(BOLD)Usage:$(RESET)\n'
	@printf '    $(YELLOW)make$(RESET) $(GREEN)<target>$(RESET) $(PURPLE)[OPTION=value]$(RESET)\n'
	@printf '\n'
	@printf '  $(BOLD)Available targets:$(RESET)\n'
	@awk -v yellow="$(YELLOW)" -v green="$(GREEN)" -v cyan="$(CYAN)" -v purple="$(PURPLE)" -v reset="$(RESET)" -v tw="$(TARGET_MAX_CHAR_NUM)" -v dw="$(DESC_MAX_CHAR_NUM)" ' \
		/^##@/ { \
			section=substr($$0,5); \
			printf "\n%s  %s%s\n", cyan, section, reset; \
			next \
		} \
		/^[a-zA-Z0-9\._-]+:.*##/ { \
			split($$0, parts, "##"); \
			cmd=parts[1]; desc=parts[2]; \
			sub(":.*","",cmd); \
			gsub(/^[ \t]+/, "", desc); \
			gsub(/^[ \t]+/, "", cmd); \
			options=""; \
			p_start = index(desc, "("); \
			p_end = index(desc, ")"); \
			if (p_start > 0 && p_end > p_start) { \
				options = substr(desc, p_start + 1, p_end - p_start - 1); \
				desc = substr(desc, 1, p_start - 1); \
				gsub(/[ \t]+$$/, "", desc); \
				options = purple""options""reset; \
			} \
			printf "    %s%-" tw "s%s  %-" dw "s  %s\n", yellow, cmd, reset, desc, options; \
		}'  $(MAKEFILE_LIST)
	@printf '\n'
	@printf '  $(BOLD)Examples:$(RESET)\n'
	@printf '    $(YELLOW)make$(RESET) test              	# Run PHPUnit tests (excluding UI)\n'
	@printf '    $(YELLOW)make$(RESET) cover             	# Run tests with coverage\n'
	@printf '    $(YELLOW)make$(RESET) cs-php PHP_FIX=1  	# Fix PHP coding standards\n'
	@printf '    $(YELLOW)make$(RESET) qa                	# Run full QA suite\n'
	@printf '    $(YELLOW)make$(RESET) deploy            	# Deploy to production\n'
	@printf '    $(YELLOW)make$(RESET) dev               	# Restore development environment\n'
	@printf '\n'
