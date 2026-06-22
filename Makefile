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
LIGHTPURPLE  := \033[1;35m
WHITE        := \033[0;37m
BG_GREEN     := \033[42m
BG_YELLOW    := \033[43m
BG_RED       := \033[41m
BG_PURPLE    := \033[45m
BG_BLUE      := \033[44m
BG_CYAN      := \033[46m
BOLD         := \033[1m
RESET        := \033[0m

#= FONCTIONS ===========================================================

# display_box BG_COLOR FG_COLOR LABEL MESSAGE|msg1|msg2 [REDIRECT]
# Message simple : $(call display_box,BG_GREEN,GREEN,✓ SUCCESS,Mon message,)
# Matrice        : $(call display_box,BG_RED,RED,✗ ERROR,msg1|msg2|msg3,)
define display_box
{
	_msg="$(4)"

	printf "\n" $(6)
	printf "$(RESET)$($(1))  $(RESET)  $($(2))┌────────────────────────────────────────────────────────────────────┐$(RESET)\n" $(5)
	printf "$(RESET)$($(1))  $(RESET)  $(BOLD)$($(2))$(3)$(RESET)\n" $(5)

	if [ -n "$$_msg" ]; then
		printf "$(RESET)$($(1))  $(RESET)\n" $(5)

		case "$$_msg" in
			*\|*)
				_IFS_BAK="$$IFS"
				IFS="|"

				for _line in $$_msg; do
					printf "$(RESET)$($(1))  $(RESET)    $($(2))•$(RESET) %s\n" "$$_line" $(5)
				done

				IFS="$$_IFS_BAK"
				;;

			*)
				printf "$(RESET)$($(1))  $(RESET)    $($(2))•$(RESET) %s\n" "$$_msg" $(5)
				;;
		esac
	fi

	printf "$(RESET)$($(1))  $(RESET)  $($(2))└────────────────────────────────────────────────────────────────────┘$(RESET)\n\n" $(5)
}
endef

define display_title
	$(call display_box,BG_PURPLE,PURPLE,$(1),,)
endef

define display_subtitle
	printf "\n$(RESET)$(BG_BLUE)  $(RESET)  $(BLUE)$(RESET) $(1)\n\n"
endef

define display_success
    $(call display_box,BG_GREEN,GREEN,✅ SUCCESS,$(1),)
endef

define display_error
    $(call display_box,BG_RED,RED,❌ ERROR,$(1),>&2)
endef

define display_warning
	$(call display_box,BG_YELLOW,YELLOW,⚠️ WARNING,$(1),>&2)
endef

define display_success_root
if [ "$(MAKELEVEL)" = "0" ]; then
	$(call display_success,$(1))
fi
endef

define display_elapsed
	elapsed=$$(($$(date +%s) - $(1)))
	$(call display_box,BG_CYAN,CYAN,⏱️ Duration: $$((elapsed / 60))m $$((elapsed % 60))s,,)
endef

# Avoid some execution from prod environment
define assert_not_prod
if [ "$(ENVIRONMENT)" = "prod" ] || [ "$(ENVIRONMENT)" = "production" ]; then 
	$(call display_error,Authorised only in development environment)
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
	@$(call display_title,📎 Checking if checkmake is installed...)

	success_msg=""

	if [ -f /.dockerenv ]; then
		if ! command -v checkmake >/dev/null 2>&1; then
			$(call display_error,checkmake is not installed in the Docker container.)
			$(MAKE) _fatal msg="checkmake is not installed in the Docker container"
		fi

		$(call display_subtitle,🔄 Running checkmake...)
		$(CHECKMAKE_BIN) Makefile
	else
		if ! $(DC) exec -T app bash -c "command -v checkmake" >/dev/null 2>&1; then
			$(call display_error,checkmake is not installed in the Docker container. Please rebuild it.)
			$(MAKE) _fatal msg="checkmake is not installed in the Docker container. Please rebuild it."
		fi

		success_msg="$$success_msg|Checkmake is ready via Docker Compose"

		$(call display_subtitle,🔄 Running checkmake via Docker...)
		$(DC) exec -T app $(CHECKMAKE_BIN) Makefile

		success_msg="$$success_msg|Checkmake passed"
		success_msg="$$success_msg|Makefile formatting is perfect!"
		success_msg="$${success_msg#|}"

		$(call display_success,$$success_msg)
	fi

# =====================================================================
##@ DOCKER
.PHONY: build
build: ## Docker build
	@$(call display_title,🏗️ Building Docker)

	$(DC) build
	$(call display_success_root,Docker built successfully)

.PHONY: up
up: ## Run Docker containers
	@$(call display_title,🐳 Starting containers)

	$(DC) up -d
	$(call display_success_root,Containers started successfully)

.PHONY: down
down: ## Stop Docker containers
	@$(call display_title,🐳 Stopping containers)

	$(DC) down
	$(call display_success_root,Containers stopped successfully)

.PHONY: destroy
destroy: ## Stop and remove Docker containers
	@$(call display_title,🐳 Stopping and removing containers)

	$(DC) down --remove-orphans
	$(call display_success_root,Containers destroyed successfully)

.PHONY: restart
FORCE ?= 0
restart: ## Docker restart (FORCE=1 to destroy and rebuild)
	@success_msg=""

	start_time=$$(date +%s)

	if [ "$(FORCE)" = "1" ]; then
		$(MAKE) destroy
		success_msg="$$success_msg|Project destroyed"

		$(MAKE) build
		success_msg="$$success_msg|Project built"

		$(MAKE) up
		success_msg="$$success_msg|Containers started successfully"
	else
		$(MAKE) down
		success_msg="$$success_msg|Containers stopped"

		$(MAKE) up
		success_msg="$$success_msg|Containers restarted successfully"
	fi

	success_msg="$${success_msg#|}"

	$(call display_success,$$success_msg)
	$(call display_elapsed,$$start_time)

.PHONY: shell
shell: ## Run a shell in the PHP container
	@$(call display_title,🐚 Running shell in PHP container)

	$(DC_EXEC) bash

.PHONY: logs
LOGS_SERVICE ?= all
logs: ## Show Docker logs (LOGS_SERVICE=app|db|nginx)
	@$(call display_title,🪲 Displaying Docker logs)

	if [ "$(LOGS_SERVICE)" = "all" ]; then
		$(DC) logs -f --tail=100
	else
		$(DC) logs -f --tail=100 $(LOGS_SERVICE)
	fi

.PHONY: update-project
update-project: ## Update docker, composer and pnpm dependencies in a safe way
	@$(call assert_not_prod)

	$(call display_title,⬇️ Downloading latest Docker images...)
	docker compose pull

	$(call display_title,🏗️ Rebuilding and restarting containers...)
	docker compose down
	docker compose up -d --build

	$(call display_title,🔄 Updating Composer dependencies (PHP)...)
	$(DC_EXEC) composer update

	$(call display_title,🔄 Updating pnpm dependencies (JS/CSS)...)
	$(DC_EXEC) pnpm self-update
	$(DC_EXEC) pnpm update

# =====================================================================
##@ CHECKERS

.PHONY: doctor
doctor: ## Check system requirements and project health
	@$(call display_title,🪲 Running health check)
	success_msg=""
	start_time=$$(date +%s)

	$(MAKE) check-tools
	success_msg="$$success_msg|Tools are installed"

	$(MAKE) check-docker
	success_msg="$$success_msg|Docker is running"

	$(MAKE) check-containers
	success_msg="$$success_msg|Containers are running"

	$(MAKE) check-env
	success_msg="$$success_msg|Environment is configured"

	$(MAKE) check-ports
	success_msg="$$success_msg|Ports are available"

	$(MAKE) check-dependencies
	success_msg="$$success_msg|Dependencies are installed"
	
	$(MAKE) check-tools-directory
	success_msg="$$success_msg|Tools are configured"

	success_msg="$$success_msg|All systems are ok! The project is ready."
	success_msg="$${success_msg#|}"
	$(call display_elapsed,$$start_time)
	$(call display_success,$$success_msg)

.PHONY: check-tools
check-tools: ## Check if required CLI tools are installed
	@$(call display_subtitle,🔍 Checking required tools...)
	@command -v docker >/dev/null || { $(call display_error,docker not found); exit 1; }
	@command -v jq >/dev/null || { $(call display_error,jq not found); exit 1; }
	@command -v lsof >/dev/null || { $(call display_error,lsof not found); exit 1; }
	@$(call display_success,All required tools are installed)

.PHONY: check-docker
check-docker: ## Check Docker is running
	@$(call display_subtitle,🔎 Checking Docker daemon...)

	if ! docker info >/dev/null 2>&1; then
		$(call display_error,Docker daemon unavailable. Please start Docker Desktop.)
		$(MAKE) _fatal msg="Docker daemon is not running."
	fi
	$(call display_success_root,Docker is running.)

.PHONY: check-containers
check-containers: ## Check if Docker containers are running
	@$(call display_subtitle,🔎 Checking Docker containers...)

	if ! command -v jq >/dev/null 2>&1; then
		$(call display_error,jq is required but not installed. Install it with: brew install jq)
		$(MAKE) _fatal msg="jq is required but not installed"
	fi
	if ! $(DC) ps --format json | jq -e -s 'any(.[]; .Service == "app" and (.Status | startswith("Up")))' > /dev/null; then
		$(call display_error,Containers are not running. Use 'make up' to start them) && \
		$(MAKE) _fatal msg="Containers are not running."
	fi
	$(call display_success_root,Containers are running.)

.PHONY: check-ports
check-ports: ## Check if required ports are available or used by this project
	@$(call display_subtitle,🔎 Checking network ports...)

	ports_conflict=0
	summary=""
	current_project_ids=$$(docker compose ps -q 2>/dev/null | tr '\n' ' ')
	for port in $(DOCKER_PORTS); do
		blocking_pid=$$(lsof -Pi :$$port -sTCP:LISTEN -t 2>/dev/null)
		if [ ! -z "$$blocking_pid" ]; then
			container_owning_port=$$(docker ps -q --no-trunc --filter "publish=$$port" 2>/dev/null)
			if [ ! -z "$$container_owning_port" ]; then
				if echo "$$current_project_ids" | grep -q -w "$$container_owning_port"; then
					summary="$$summary|Port $$port is used by this project (already up)"
				else
					summary="$$summary|Port $$port is blocked by ANOTHER Docker project"
					ports_conflict=1
				fi
			else
				summary="$$summary|Port $$port is blocked by ANOTHER native application (PID: $$blocking_pid)"
				ports_conflict=1
			fi
		else
			summary="$$summary|Port $$port is free and ready"
		fi
	done
	if [ $$ports_conflict -eq 1 ]; then
		$(call display_error,Some ports are blocked by other services. Run 'make down' elsewhere.)
		$(MAKE) _fatal msg="Some ports are blocked by other services."
	fi
	summary="$${summary#|}"
	$(call display_success_root,$$summary)

.PHONY: check-env
check-env: ## Check .env file
	@$(call display_subtitle,🔎 Checking .env file...)
	
	if [ ! -f .env ]; then
		$(call display_error,.env file is missing. Please create it from .env.sample.)
		$(MAKE) _fatal msg=".env file is missing."
	fi
	$(call display_success_root,.env file exists.)

.PHONY: check-dependencies
check-dependencies: ## Check if PHP dependencies - vendor directory - are installed
	@$(call display_subtitle,🔎 Checking PHP dependencies...)

	if $(DC) ps app | grep -q "Up"; then
		if ! $(DC_EXEC) [ -d vendor ]; then
			$(call display_error,'vendor' directory is missing. Run installation.)
			$(MAKE) _fatal msg="PHP dependencies (vendor) are missing."
		else
			$(call display_success_root,PHP dependencies (vendor) are installed.)
		fi
	else
		$(call display_warning,Containers are not running. Cannot check 'vendor' (run 'make up').)
	fi

.PHONY: check-tools-directory
check-tools-directory: ## Check if tools configuration directory exists
	@$(call display_subtitle,🔎 Checking tools directory...)
	
	if [ ! -d $(TOOLS_CONFIG_DIR) ]; then
		$(call display_error,.tools directory is missing.)
		$(MAKE) _fatal msg=".tools directory is missing."
	fi
	$(call display_success_root,Tools directory exists.)

# =====================================================================
##@ DEVELOPMENT

.PHONY: cc
CC_ENV ?= 
cc: ## Run bin/console cache:clear from docker (CC_ENV=prod|dev|test)
	@$(call display_title,🧹 Clearing Symfony cache)

	if [ -n "$(CC_ENV)" ]; then
		$(SYMFONY) "cache:clear" --env=$(CC_ENV)
	else
		$(SYMFONY) "cache:clear"
	fi
	$(call display_success_root,Cache cleared successfully.)

.PHONY: composer-clear
composer-clear: ## Clear Composer cache and reinstall dependencies
	@$(call display_title,🧹 Clearing Composer cache and reinstalling dependencies)

	rm -rf vendor/
	$(COMPOSER) clear-cache
	$(COMPOSER) install
	$(call display_success_root,Composer cache cleared and dependencies reinstalled.)

.PHONY: pnpm-clear
pnpm-clear: ## Clear pnpm cache and reinstall dependencies
	@$(call display_title,🧹 Clearing pnpm cache and reinstalling dependencies)

	rm -rf node_modules/
	$(PNPM) store prune
	$(PNPM) install
	$(call display_success_root,pnpm cache cleared and dependencies reinstalled.)

.PHONY: pnpm-prune
pnpm-prune: ## Prune unused packages
	@$(call display_title,🧹 Pruning unused packages)

	$(call display_subtitle,🧹 Removing unused packages...)
	$(PNPM) store prune
	$(call display_subtitle,🔍 Checking for unused packages...)
	$(PNPX) depcheck --ignores="animate.css,@biomejs/biome"
	$(call display_success_root,Unused packages check completed.)

.PHONY: clean
clean: ## Clean temporary files: cache, coverage, logs, public build
	@$(call display_title,🧹 Cleaning temporary files)

	$(MAKE) clean-build
	$(MAKE) clean-cache
	$(MAKE) clean-test
	$(call display_success_root,Temporary files cleaned.)

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
	@$(call display_title,🔍 Watching Tailwind CSS changes and re-building)

	$(SYMFONY) "tailwind:build" --watch

.PHONY: secret
secret: ## Generate a new APP_SECRET and display it
	@$(call display_title,🔑 Generating new APP_SECRET)

	secret=$$($(DC_EXEC) openssl rand -hex 32)
	$(call display_success,APP_SECRET generated successfully.|$(GREEN)$$secret$(RESET))

.PHONY: migration
migration: ## Run Doctrine migrations
	@$(call assert_not_prod)
	$(call display_title,💾 Running Doctrine migrations)

	$(SYMFONY) "make:migration"
	$(SYMFONY) "doctrine:migrations:migrate"

.PHONY: fixtures
fixtures: ## Load Doctrine fixtures
	@$(call assert_not_prod)
	$(call display_title,💾 Loading Doctrine fixtures)
	
	$(SYMFONY) "doctrine:fixtures:load"

.PHONY: cs
CS_TARGET ?= all
cs: ## Check all coding standards: PHP, Twig, CSS (CS_TARGET=all|php|yaml|twig|front)
	@$(call display_title,🎨 Checking coding standards)
	
	if [ "$(CS_TARGET)" = "all" ]; then 
		success_msg=""
		start_time=$$(date +%s)

		$(MAKE) cs-php
		success_msg="$$success_msg|PHP coding standards check completed."

		$(MAKE) cs-yaml
		success_msg="$$success_msg|YAML linting completed."

		$(MAKE) cs-twig
		success_msg="$$success_msg|Twig coding standards check completed."

		$(MAKE) cs-front
		success_msg="$$success_msg|CSS and JS coding standards check completed."

		success_msg="$${success_msg#|}"
		$(call display_elapsed,$$start_time)
		$(call display_success_root,$$success_msg)
	else
		$(MAKE) cs-$(CS_TARGET)
	fi
	

.PHONY: cs-php
PHP_FIX ?= 0
cs-php: ## PHP CS Fixer (PHP_FIX=1 to actually fix)
	@$(call assert_not_prod)
	if [ "$(PHP_FIX)" = "1" ]; then
		$(call display_subtitle,🪛 PHP CS Fixer in verbose mode...)
		$(CSPHP) fix --verbose
		$(call display_success_root,PHP coding standards check completed.)
	else
		$(call display_subtitle,🔄 Dry running PHP coding standards...)
		$(CSPHP) check --verbose
		$(call display_subtitle,🔭 Running PHPStan analysis...)
		$(PHPSTAN) analyse
		$(call display_success_root,Dry running PHP coding standards completed.|PHPStan analysis completed.)
	fi

.PHONY: cs-yaml
cs-yaml: ## Validate YAML files
	@$(call assert_not_prod)
	$(call display_subtitle,🔭 Validate YAML files from config directory...)

	$(SYMFONY) "lint:yaml" config/
	$(call display_success_root,YAML linting completed.)

.PHONY: cs-twig
TWIG_FIX ?= 0
cs-twig: ## Twig CS Fixer (TWIG_FIX=1 to actually fix)
	@$(call assert_not_prod)
	if [ "$(TWIG_FIX)" = "1" ]; then
		$(call display_subtitle,🪛 Running Twig CS Fixer in fix mode...)
		$(CSTWIG) fix templates/
		$(SYMFONY) "lint:twig" templates/
	else
		$(call display_subtitle,🔭 Dry running Twig CS Fixer and display diff...)
		$(CSTWIG) check templates/
	fi
	$(call display_success_root,Twig coding standards check completed.)

.PHONY: cs-front
FRONT_FIX ?= 0
cs-front: ## Run linters for CSS and JS (FRONT_FIX=1 to actually fix)
	@$(call assert_not_prod)
	if [ "$(FRONT_FIX)" = "1" ]; then
		$(call display_subtitle,🪛 Running ESLint in fix mode...)
		$(ESLINT) assets/scripts/ --fix
		$(call display_subtitle,🪛 Running Biome in fix mode...)
		$(BIOME) --write assets/
	else
		$(call display_subtitle,🔍 Running ESLint...)
		$(ESLINT) assets/scripts/
		$(call display_subtitle,🔭 Running Biome...)
		$(BIOME) assets/
	fi
	$(call display_success_root,Frontend coding standards check completed (ESLint + Biome).)

.PHONY: cs-fix
cs-fix: ## Fix all coding standards: PHP, Twig, CSS
	@$(call assert_not_prod)
	$(call display_title,🎨 Fixing coding standards)
	success_msg=""
	start_time=$$(date +%s)

	$(MAKE) cs-php PHP_FIX=1
	success_msg="$$success_msg|PHP coding standards fixed."

	$(MAKE) cs-twig TWIG_FIX=1
	success_msg="$$success_msg|Twig coding standards fixed."
	
	$(MAKE) cs-front FRONT_FIX=1
	success_msg="$$success_msg|CSS and JS coding standards fixed."
	
	success_msg="$${success_msg#|}"
	$(call display_elapsed,$$start_time)
	$(call display_success,$$success_msg)

# =====================================================================
##@ TESTS

.PHONY: pre-commit
pre-commit: ## Run pre-commit checks
	@$(MAKE) cs-makefile
	$(MAKE) readme

.PHONY: grum-install
grum-install: ## Install GrumPHP hooks
	@$(call display_title,🪲 Installing GrumPHP hooks)

	$(GRUMPHP) "git:init"

.PHONY: grum-run
grum-run: ## Run GrumPHP checks
	@$(call display_title,🧪 Running GrumPHP checks)

	$(GRUMPHP) run

.PHONY: grum-pre-commit
grum-pre-commit:
	@$(call display_title,🧪 Running GrumPHP pre-commit hooks)
	
	$(GRUMPHP) "git:pre-commit"

.PHONY: qa
qa: ## Run complete Quality Assurance suite: Lint, Static Analysis, Tests
	@$(call assert_not_prod)
	success_msg=""
	start_time=$$(date +%s)

	$(MAKE) cs || exit 1
	success_msg="$$success_msg|PHP coding standards checked."

	$(MAKE) qa-analyse || exit 1
	success_msg="$$success_msg|Static analysis passed."
	
	$(MAKE) cover || exit 1
	success_msg="$$success_msg|Tests passed."
	
	$(MAKE) test-mutation || exit 1
	success_msg="$$success_msg|Infection tests passed."
	
	$(MAKE) test-arch || exit 1
	success_msg="$$success_msg|Architecture tests passed."
	
	$(MAKE) test-ui || exit 1
	success_msg="$$success_msg|UI tests passed."

	success_msg="$$success_msg|✨ QA passed successfully! Your code is amazing. ✨"
	success_msg="$${success_msg#|}"
	$(call display_elapsed,$$start_time)
	$(call display_success,$$success_msg)

.PHONY: test
test: ## Run PHPUnit tests without UI tests
	@$(call display_title,🧪 Running PHPUnit tests)
	
	$(MAKE) check-containers
	$(call display_subtitle,💾 Generating fixtures...)
	$(SYMFONY) "cache:clear" --env=test
	$(SYMFONY) "app:generate-fixtures" --group=test
	
	$(call display_subtitle,🎬 Preparing test database...)
	- $(SYMFONY) "doctrine:schema:drop" --env=test --force --full-database
	$(SYMFONY) "doctrine:schema:update" --env=test --force
	$(SYMFONY) "doctrine:fixtures:load" --env=test --group=test --no-interaction
	
	$(call display_subtitle,🧪 Running PHPUnit tests...)
	$(PHPUNIT) --exclude-group=UI

.PHONY: qa-analyse
qa-analyse: ## Run static analysis
	@$(call display_subtitle,🔎 Running static analysis...)
	success_msg=""

	$(PHPSTAN) analyse
	success_msg="$$success_msg|PHPStan passed."

	$(call display_subtitle,🔎 Running Deptrac...)
	$(DEPTRAC) analyse
	success_msg="$$success_msg|Deptrac passed."
	
	# $(call display_subtitle,🔎 Running Rector...)
	# $(RECTOR) --dry-run
	# success_msg="$$success_msg|Rector passed."
	
	success_msg="$${success_msg#|}"
	$(call display_success_root,$$success_msg)

.PHONY: qa-rector
REC_FIX ?= 0
qa-rector: ## Run Rector (REC_FIX=1 to actually fix)
	@$(call display_subtitle,🔎 Running Rector...)

	if [ "$(REC_FIX)" = "1" ]; then
		$(RECTOR)
	else
		$(RECTOR) --dry-run
	fi
	$(call display_success_root,Rector analysis complete)

.PHONY: test-mutation
test-mutation: ## Run Infection
	@$(call display_subtitle,🧪 Running Infection...)
	
	$(INFECTION) --threads=4
	$(call display_success_root,Infection analysis complete)

.PHONY: test-arch
test-arch: ## Run phparkitect
	@$(call display_subtitle,🔬 Running PHPArkitect...)
	
	$(PHPARKITECT) check
	$(call display_success_root,PHPArkitect analysis complete)

.PHONY: test-ui
test-ui: ## Run PHPUnit tests for UI group
	@$(call display_subtitle,🧪 Running PHPUnit tests for UI group...)

	$(PHPUNIT) --group=UI
	$(call display_success_root,PHPUnit UI tests complete)

.PHONY: cover
cover: ## Run PHPUnit tests with coverage
	@$(call display_subtitle,🧹 Clearing cache...)

	$(SYMFONY) "cache:clear" --env=test
	$(call display_subtitle,🧪 Running PHPUnit tests with coverage...)
	$(PHPUNIT) --coverage-html coverage/ --exclude-group=UI
	$(call display_success_root,Coverage report generated in 'coverage/' directory.)

# =====================================================================
##@ ENVIRONMENT
EXPORT_DIR=$(TOOLS_CONFIG_DIR)/export
ZIP_NAME=portfolio_prod-$(TODAY).zip
SQL_FILE=portfolio_db-$(NOW).sql
# CMD_PROD=zip -r archive-portfolio-$(TODAY).zip * -x analytics/* -x medias/movies/*
REMOTE_PATH=/home/$(REMOTE_USER)/domains/carolinenoyer.fr/public_html

.PHONY: db-dump
FILES_CLEAN ?= 0
db-dump: ## Dump database (FILES_CLEAN=1 to clean previous)
	@$(call display_title,💾 Dumping database...)

	if [ "$(FILES_CLEAN)" = "1" ]; then
		rm -f $(EXPORT_DIR)/*.sql
	fi
	$(DC) exec -i $(DOCKER_DB_SERVICE) mysqldump -uroot -p$(DB_ROOT_PASSWORD) $(DB_NAME) > $(EXPORT_DIR)/$(SQL_FILE) 
	$(call display_success_root,Database dumped to:|$(EXPORT_DIR)/$(SQL_FILE))

.PHONY: deploy
# deploy.sh should be copied to remote server in user folder
# Find it into .tools/ directory if needed
deploy: ## Full production deployment - build + push to remote
	@$(call display_title,🚀 Starting full production deployment)
	@./$(SCRIPTS_DIR)/prod-build.sh
	@./$(SCRIPTS_DIR)/prod-deploy.sh
	$(MAKE) dev
	@$(call display_success_root,Full production deployment completed!)

.PHONY: prod-build
prod-build: ## Build and commit assets to production branch
	@./$(SCRIPTS_DIR)/prod-build.sh

.PHONY: prod-deploy
prod-deploy: ## Deploy to remote server - requires assets already built
	@./$(SCRIPTS_DIR)/prod-deploy.sh

.PHONY: dev
dev: ## Switch back to development environment
	@$(call display_title,🔄 Switching back to development environment...)
	start_time=$$(date +%s)
	success_msg=""

	$(call display_subtitle,🔄 [1/5] Restoring dev dependencies...)
	docker compose exec $(TTY_FLAG) -e APP_ENV=dev app composer install
	success_msg="$$success_msg|Dev dependencies restored."
	
	$(call display_subtitle,🧹 [2/5] Cleaning dev cache...)
	$(SYMFONY) "cache:clear" --env=dev
	success_msg="$$success_msg|Dev cache cleaned."

	$(call display_subtitle,🎨 [3/5] Regenerating assets for development...)
	$(SYMFONY) "tailwind:build" --env=dev
	success_msg="$$success_msg|Assets regenerated for development."

	$(call display_subtitle,🧹 [4/5] Cleaning public assets...)
	rm -rf public/assets/*
	success_msg="$$success_msg|Public assets cleaned."

	$(call display_subtitle,🧹 [5/5] Cleaning archive...)
	rm -f $(ZIP_NAME)
	success_msg="$$success_msg|Archive cleaned."

	success_msg="$$success_msg|✅ Development environment restored!"
	success_msg="$${success_msg#|}"
	$(call display_elapsed,$$start_time)
	$(call display_success,$$success_msg)

	

# =====================================================================
##@ HELP

.PHONY: readme
readme: ## Update README.md Makefile section
	@$(call display_subtitle,🔄 Updating README.md...)

	if [ ! -f README.md ]; then
		$(call display_error,README.md not found)
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

	$(call display_success,README.md updated successfully!)

.PHONY: readme-check
readme-check: ## Check if README.md is up to date
	@tmp=$$(mktemp)
	cp README.md $$tmp
	$(MAKE) readme > /dev/null
	if ! diff -q README.md $$tmp >/dev/null; then 
		mv $$tmp README.md
		$(call display_warning,README.md is outdated. Run 'make readme'.)
	else 
		$(call display_success,README.md is up to date.)
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
