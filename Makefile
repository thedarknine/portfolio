.DEFAULT_GOAL := help
SHELL := /usr/bin/env bash
.SHELLFLAGS := -eu -o pipefail -c
.ONESHELL:

-include .env
ENVIRONMENT ?= dev

NOW := $(shell date +"%Y%m%d_%H%M%S")

# Manage Docker container execution flags for CI compatibility
TTY_FLAG := -it
ifndef TERM
    TTY_FLAG := -T
endif

# =====================================================================
# CONFIGURATION
# =====================================================================

# Docker configuration and commands
DOCKER_APP_SERVICE := app
DOCKER_DB_SERVICE := db
DOCKER_NGINX_SERVICE := engine
DOCKER_PORTS := 8000 3306 8088 4444

#= GLOBAL VARIABLES ===================================================
TOOLS_CONFIG_DIR := .tools-config
TARGET_MAX_CHAR_NUM=24

#= COMMAND SHORTCUTS ==================================================
DC        := docker compose
DC_EXEC   = $(DC) exec $(TTY_FLAG) $(DOCKER_APP_SERVICE)

SYMFONY		:= $(DC_EXEC) php bin/console
COMPOSER	:= $(DC_EXEC) composer
PHPUNIT		:= $(DC_EXEC) php vendor/bin/phpunit --configuration $(TOOLS_CONFIG_DIR)/phpunit.xml
INFECTION	:= $(DC_EXEC) php vendor/bin/infection --configuration=$(TOOLS_CONFIG_DIR)/infection.json
PHPARKITECT	:= $(DC_EXEC) php vendor/bin/phparkitect --config=$(TOOLS_CONFIG_DIR)/phparkitect.php
PHPSTAN		:= $(DC_EXEC) php vendor/bin/phpstan --memory-limit=512M --configuration=$(TOOLS_CONFIG_DIR)/phpstan.neon
CSPHP		:= $(DC_EXEC) php vendor/bin/php-cs-fixer  --config=$(TOOLS_CONFIG_DIR)/.php-cs-fixer.php --cache-file=$(TOOLS_CONFIG_DIR)/.php-cs-fixer.cache
CSTWIG		:= $(DC_EXEC) vendor/bin/twig-cs-fixer --config=$(TOOLS_CONFIG_DIR)/.twig-cs-fixer.php
ESLINT		:= $(DC_EXEC) npx eslint --config $(TOOLS_CONFIG_DIR)/eslint.config.mjs
STYLELINT	:= $(DC_EXEC) npx stylelint --config $(TOOLS_CONFIG_DIR)/.stylelintrc.json
PRETTIER	:= $(DC_EXEC) npx prettier --config $(TOOLS_CONFIG_DIR)/.prettierrc
BIOME		:= $(DC_EXEC) bin/biome

#= COLORS =============================================================
BLACK        := \033[0;30m
RED          := \033[0;31m
GREEN        := \033[0;32m
YELLOW       := \033[0;33m
BLUE         := \033[0;34m
PURPLE       := \033[0;35m
LIGHTPURPLE  := \033[1;35m
WHITE        := \033[0;37m
BOLD         := \033[1m
RESET        := \033[0m

#= ICONS =============================================================
ICON_TEST := 🧪
ICON_DOCKER := 🐳
ICON_CS := 🎨
ICON_COVERAGE := 📊
ICON_SHELL := 🐚
ICON_BUILD := 🏗️
ICON_INSTALL := 📦
ICON_DATA := 💾
ICON_CLEAN := 🧹
ICON_HELP := ❓
ICON_DEBUG := 🪲

#= FONCTIONS ===========================================================
define display_title
	@printf "\n$(PURPLE)• $(2) $(1)$(RESET)\n"
	$(if $(3), $(call display_subtitle,$3), @printf "\n")
endef

define display_subtitle
	@printf "\n  $(BLUE)» $(1)$(RESET)\n\n"
endef

# Fonction pour afficher un message de succès
define display_success
	@printf "  $(GREEN)✓$(RESET) $(1)\n"
endef

# Fonction pour afficher un message d'erreur et quitter
define display_error
	{ printf "\n${RED}✗ ERROR${RESET}: %s\n\n" "$1" >&2; exit 1; }
endef

# Fonction pour afficher un message d'avertissement
define display_warning
	@printf "  $(YELLOW)⚠$(RESET) $(1)\n"
endef

# Avoid some execution on prod environment
define assert_not_prod
    @if [ "$(ENVIRONMENT)" = "prod" ] || [ "$(ENVIRONMENT)" = "production" ]; then \
        printf "$(RED)   Uniquement autorisé en environnement de développement !$(RESET)\n"; \
        exit 1; \
    fi
endef

# =====================================================================
##@ DOCKER
.PHONY: build
build: ## Docker build
	$(call display_title,Building Docker ........................................,${ICON_BUILD})
	@$(DC) build 

.PHONY: up
up: ## Run Docker containers
	$(call display_title,Starting containers ....................................,${ICON_DOCKER})
	@$(DC) up -d 

.PHONY: down
down: ## Stop Docker containers
	$(call display_title,Stopping containers ....................................,${ICON_DOCKER})
	@$(DC) down

.PHONY: destroy
destroy: ## Stop and remove Docker containers
	$(call display_title,Stopping and remove containers .........................,${ICON_DOCKER})
	@$(DC) down --remove-orphans

.PHONY: restart
restart: ## Docker restart
	@$(MAKE) down && $(MAKE) up

.PHONY: force-restart
force-restart: ## Docker restart (down, remove all containers, re-build and up)
	@$(MAKE) destroy && $(MAKE) build && $(MAKE) up

.PHONY: shell
shell: ## Run a shell in the PHP container
	$(call display_title,Running shell in PHP container .........................,${ICON_SHELL})
	@$(DC_EXEC) bash

.PHONY: logs
logs: ## Show Docker logs for all services
	$(call display_title,Displaying Docker logs .............................,${ICON_LOGS})
	@$(DC) logs -f --tail=100

.PHONY: logs-app
logs-app: ## Show Docker logs for the app service
	$(call display_title,Displaying app logs .................................,${ICON_LOGS})
	@$(DC) logs -f $(DOCKER_APP_SERVICE)

.PHONY: logs-db
logs-db: ## Show Docker logs for the database service
	$(call display_title,Displaying database logs ............................,${ICON_LOGS})
	@$(DC) logs -f $(DOCKER_DB_SERVICE)

.PHONY: logs-nginx
logs-nginx: ## Show docker logs for nginx
	$(call display_title,Logs Nginx .............................................,${ICON_DEBUG})
	@$(DC) logs -f $(DOCKER_NGINX_SERVICE)

# =====================================================================
##@ CHECKERS

.PHONY: doctor
doctor: ## Check system requirements and project health
	$(call display_title,Running health check ....................................,${ICON_DEBUG})
	@$(MAKE) check-docker
	@$(MAKE) check-containers
	@$(MAKE) check-env
	@$(MAKE) check-ports
	@$(MAKE) check-dependencies
	@$(call display_success,All systems are ok! The project is ready.)

.PHONY: check-docker
check-docker: ## Check Docker is running
	@$(call display_subtitle,Checking Docker daemon...)
	@if ! docker info >/dev/null 2>&1; then \
		$(call display_error,Docker daemon unavailable. Please start Docker Desktop.) \
	fi
	@$(call display_success,Docker is running.)

.PHONY: check-containers
check-containers: ## Check if Docker containers are running
	@$(call display_subtitle,Checking Docker containers...)
	@if ! $(DC) ps | grep -q "app.*Up"; then \
		$(call display_error,Containers are not running. Use 'make up' to start them); \
	fi
	@$(call display_success,Containers are running.)

.PHONY: check-ports
check-ports: ## Check if required ports are available or used by this project
	$(call display_subtitle,Checking network ports...)
	@ports_conflict=0; \
	current_project_ids=$$(docker compose ps -q 2>/dev/null | tr '\n' ' '); \
	for port in $(DOCKER_PORTS); do \
		blocking_pid=$$(lsof -Pi :$$port -sTCP:LISTEN -t 2>/dev/null); \
		if [ ! -z "$$blocking_pid" ]; then \
			container_owning_port=$$(docker ps -q --no-trunc --filter "publish=$$port" 2>/dev/null); \
			if [ ! -z "$$container_owning_port" ]; then \
				if echo "$$current_project_ids" | grep -q -w "$$container_owning_port"; then \
					printf "  ${GREEN}✓${RESET} Port $$port is used by this project (already up).\n"; \
				else \
					printf "${RED}✗${RESET} Port $$port is blocked by ANOTHER Docker project.\n" >&2; \
					ports_conflict=1; \
				fi; \
			else \
				printf "${RED}✗${RESET} Port $$port is blocked by ANOTHER native application (PID: $$blocking_pid).\n" >&2; \
				ports_conflict=1; \
			fi; \
		else \
			printf "  ${GREEN}✓${RESET} Port $$port is free and ready.\n"; \
		fi; \
	done; \
	if [ $$ports_conflict -eq 1 ]; then \
		$(call display_error,Some ports are blocked by other services. Run 'make down' elsewhere.); \
	fi

.PHONY: check-env
check-env: ## Check .env file
	$(call display_subtitle,Checking .env file...)
	@[ -f .env ] || $(call display_error,.env file is missing. Please create it from .env.dist.)
	$(call display_success,.env file exists.)

.PHONY: check-dependencies
check-dependencies: ## Check if PHP dependencies (vendor) are installed
	$(call display_subtitle,Checking PHP dependencies...)
	@$(DC) ps app | grep -q "Up" || $(call display_warning,Containers are not running. Cannot check 'vendor' (run 'make up').)
	@if $(DC) ps app | grep -q "Up"; then \
		if ! $(DC_EXEC) [ -d vendor ]; then \
			printf "\n${RED}✗ ERROR${RESET}: %s\n\n" "'vendor' directory is missing. Run installation." >&2; exit 1; \
		else \
			printf "  $(GREEN)✓$(RESET) PHP dependencies (vendor) are installed.\n"; \
		fi; \
	fi

# =====================================================================
##@ TESTS

.PHONY: qa
qa: ## Run complete Quality Assurance suite (Lint, Static Analysis, Tests)
	$(call assert_not_prod)
	$(call display_title,Running complete Quality Assurance suite ...............,${ICON_CS})
	@TIMEFORMAT='  ⏱️  Temps d’exécution : %R secondes'; \
	time { \
		$(MAKE) --no-print-directory cs || exit 1; \
		$(MAKE) --no-print-directory test-analyse || exit 1; \
		$(MAKE) --no-print-directory cover || exit 1; \
		$(MAKE) --no-print-directory test-mutation || exit 1; \
		$(MAKE) --no-print-directory test-arch || exit 1; \
		$(MAKE) --no-print-directory test-ui || exit 1; \
	}
	@printf "\n"
	$(call display_success,Coding standards passed.)
	$(call display_success,Static analysis passed.)
	$(call display_success,Tests passed.)
	$(call display_success,Infection tests passed.)
	$(call display_success,Architecture tests passed.)
	$(call display_success,UI tests passed.)
	@printf "\n  ${BOLD}${GREEN}✨ QA passed successfully! Your code is amazing. ✨${RESET}\n\n"

.PHONY: test
test: ## Run PHPUnit tests
	$(call display_title,Running PHPUnit tests ..................................,${ICON_TEST})
	@$(MAKE) check-containers
	$(call display_subtitle,Generating fixtures...)
#@$(SYMFONY) cache:clear --env=test
#$(SYMFONY) app:generate-fixtures --group=test
	$(call display_subtitle,Preparing test database...)
	- @$(SYMFONY) doctrine:schema:drop --env=test --force --full-database
	@$(SYMFONY) doctrine:schema:update --env=test --force
	@$(SYMFONY) doctrine:fixtures:load --env=test --group=test --no-interaction
	$(call display_subtitle,Running PHPUnit tests...)
	$(PHPUNIT) --exclude-group=UI

.PHONY: test-analyse
test-analyse: ## Run all tests
	$(call display_subtitle,Running static analysis (PHPStan)...); \
	$(PHPSTAN) analyse

.PHONY: test-mutation
test-mutation: ## Run Infection
	$(call display_subtitle,Running Infection...)
	@$(INFECTION) --threads=4

.PHONY: test-arch
test-arch: ## Run phparkitect
	$(call display_subtitle,Running phparkitect...)
	@$(PHPARKITECT) check

.PHONY: test-ui
test-ui: ## Run PHPUnit tests for UI group
	$(call display_subtitle,Running PHPUnit tests for UI group...)
	@$(PHPUNIT) --group=UI

.PHONY: cover
cover: ## Run PHPUnit tests with coverage
	$(call display_subtitle,Clearing cache...)
	@$(SYMFONY) cache:clear --env=test
	$(call display_subtitle,Running PHPUnit tests with coverage...)
	@$(PHPUNIT) --coverage-html coverage/ --exclude-group=UI
	@$(call display_success,Coverage report generated in 'coverage/' directory.)

# =====================================================================
##@ DEVELOPMENT

.PHONY: cc
cc: ## Run bin/console cache:clear from docker
	$(call display_title,Clearing Symfony cache .................................,${ICON_CLEAN})
	@$(SYMFONY) cache:clear

.PHONY: watch
watch: ## Watch Tailwind CSS changes and re-build
	$(call display_title,Watching Tailwind CSS changes and re-building ...........,${ICON_BUILD})
	@$(SYMFONY) tailwind:build --watch

.PHONY: clean
clean: ## Clean temporary files (cache, coverage, logs)
	$(call display_title,Cleaning temporary files .............................,${ICON_CLEAN})
	@rm -rf var/cache/* var/log/* coverage/ .phpunit.result.cache
	@$(call display_success,Temporary files cleaned.)

.PHONY: secret
secret: ## Generate a new Symfony secret and update .env
	$(call display_title,Generating new Symfony secret ........................,${ICON_INSTALL})
	@$(DC_EXEC) openssl rand -hex 32

.PHONY: migration
migration: ## Run Doctrine migrations
	$(call assert_not_prod)
	$(call display_title,Running Doctrine migrations ..........................,${ICON_DATA})
	@$(SYMFONY) make:migration
	@$(SYMFONY) doctrine:migrations:migrate

.PHONY: fixtures
fixtures: ## Load Doctrine fixtures
	$(call assert_not_prod)
	$(call display_title,Loading Doctrine fixtures ............................,${ICON_DATA})
	@$(SYMFONY) doctrine:fixtures:load

.PHONY: cs
cs: ## Check all coding standards (PHP, Twig, CSS)
	$(call display_title,Checking coding standards .............................,${ICON_CS})
	@$(MAKE) --no-print-directory cs-php 
	@$(MAKE) --no-print-directory cs-twig 
	@$(MAKE) --no-print-directory cs-front
	$(call display_success,PHP coding standards check completed.)
	$(call display_success,PHPStan analysis completed.)
	$(call display_success,Twig coding standards check completed.)
	$(call display_success,CSS and JS coding standards check completed.)
	
.PHONY: cs-php
cs-php: ## PHP CS Fixer - Only show diff
	$(call assert_not_prod)
	$(call display_subtitle,Dry running PHP coding standards...)
	@$(CSPHP) check --verbose
	$(call display_subtitle,Running PHPStan analysis...)
	@$(PHPSTAN) analyse

.PHONY: cs-twig
cs-twig: ## Twig CS Fixer - Only show diff
	$(call assert_not_prod)
	$(call display_subtitle,Dry running Twig CS Fixer and display diff...)
	@$(CSTWIG) check templates/

.PHONY: cs-front
cs-front: ## Run linters for CSS and JS
	$(call assert_not_prod)
	$(call display_subtitle,Running ESLint...)
	@$(ESLINT) assets/scripts/
	$(call display_subtitle,Running Stylelint...)
	@$(STYLELINT) assets/styles/
	$(call display_subtitle,Running Prettier...)
	@$(PRETTIER) assets/ --check
	$(call display_subtitle,Running Biome...)
	@$(BIOME) lint

.PHONY: cs-fix
cs-fix: ## Fix all coding standards (PHP, Twig, CSS)
	$(call assert_not_prod)
	$(call display_title,Fixing coding standards ..............................,${ICON_CS})
	@$(MAKE) --no-print-directory cs-php-fix 
	@$(MAKE) --no-print-directory cs-twig-fix 
	@$(MAKE) --no-print-directory cs-front-fix
	$(call display_success,PHP coding standards fixed.)
	$(call display_success,Twig coding standards fixed.)
	$(call display_success,CSS and JS coding standards fixed.)

.PHONY: cs-php-fix
cs-php-fix: ## PHP CS Fixer - Fix code
	$(call assert_not_prod)
	$(call display_subtitle,Dry running PHP CS Fixer and display diff...)
	@$(CSPHP) fix --diff --verbose

.PHONY: cs-twig-fix
cs-twig-fix: ## Twig CS Fixer - Fix code
	$(call assert_not_prod)
	$(call display_subtitle,Dry running Twig CS Fixer and display diff...)
	@$(CSTWIG) fix templates/
	@$(SYMFONY) lint:twig templates/

.PHONY: cs-front-fix
cs-front-fix: ## Run Stylelint then Prettier and fix issues
	$(call assert_not_prod)
	$(call display_subtitle,Running Stylelint...)
	@$(STYLELINT) assets/styles/ --fix
	$(call display_subtitle,Running Prettier...)
	@$(PRETTIER) assets/ --write

# =====================================================================
##@ HELP
.PHONY: help
help: ## Show this help message
	@printf '\n'
	@printf '  ${BOLD}${YELLOW}${PROJECT_NAME}${RESET} ${BLUE}(${GREEN}${ENVIRONMENT}${BLUE})${RESET}\n'
	@printf '  ${BLUE}────────────────────────────────────────────────${RESET}\n'
	@printf '\n'
	@printf '  ${BOLD}Usage:${RESET}\n'
	@printf '    ${YELLOW}make${RESET} ${GREEN}<target>${RESET}\n'
	@printf '\n'
	@printf '  ${BOLD}Available targets:${RESET}\n'
	@awk -v yellow="${YELLOW}" -v green="${GREEN}" -v blue="${BLUE}" -v reset="${RESET}" -v width="$(TARGET_MAX_CHAR_NUM)" ' \
		/^##@/ { \
			section=substr($$0,5); \
			printf "\n%s  %s%s\n", blue, section, reset; \
			next \
		} \
		/^[a-zA-Z0-9\._-]+:.*##/ { \
			split($$0, parts, "##"); \
			cmd=parts[1]; desc=parts[2]; \
			sub(":.*","",cmd); \
			gsub(/^[ \t]+/, "", desc); \
			printf "    %s%-" width "s%s  %s\n", yellow, cmd, reset, desc; \
		}'  $(MAKEFILE_LIST)
	@printf '\n'
	@printf '  ${BOLD}Examples:${RESET}\n'
	@printf '    make test            # Run PHPUnit tests\n'
	@printf '    make cs              # Check and fix coding standards\n'
	@printf '    make up              # Run Docker containers\n'
	@printf '\n'
