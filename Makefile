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
GRUMPHP		:= $(DC_EXEC) vendor/bin/grumphp --config=$(TOOLS_CONFIG_DIR)/grumphp.yml
PHPUNIT		:= $(DC_EXEC) php vendor/bin/phpunit --configuration $(TOOLS_CONFIG_DIR)/phpunit.xml
INFECTION	:= $(DC_EXEC) php vendor/bin/infection --configuration=$(TOOLS_CONFIG_DIR)/infection.json
PHPARKITECT	:= $(DC_EXEC) php vendor/bin/phparkitect --config=$(TOOLS_CONFIG_DIR)/phparkitect.php
DEPTRAC		:= $(DC_EXEC) vendor/bin/deptrac --config-file=$(TOOLS_CONFIG_DIR)/deptrac.yaml
PHPSTAN		:= $(DC_EXEC) php vendor/bin/phpstan --memory-limit=512M --configuration=$(TOOLS_CONFIG_DIR)/phpstan.neon
RECTOR		:= $(DC_EXEC) vendor/bin/rector process --config $(TOOLS_CONFIG_DIR)/rector.php
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
    @printf "\n"
    @printf "$(RESET)$(BG_PURPLE)  $(RESET)  $(PURPLE)┌─────────────────────────────────────────────────────────┐$(RESET)\n"
    @printf "$(RESET)$(BG_PURPLE)  $(RESET)  $(BOLD)$(WHITE)%s $(1)$(RESET)\n" "$(2)"
    @printf "$(RESET)$(BG_PURPLE)  $(RESET)  $(PURPLE)└─────────────────────────────────────────────────────────┘$(RESET)\n\n"
endef

define display_subtitle
	printf "\n$(RESET)$(BG_BLUE)  $(RESET)  $(BLUE)⧗$(RESET) $(1)\n\n"
endef

define display_success
    @printf "\n"
    @printf "$(RESET)$(BG_GREEN)  $(RESET)  $(GREEN)┌─────────────────────────────────────────────────────────┐$(RESET)\n"
    @printf "$(RESET)$(BG_GREEN)  $(RESET)  $(BOLD)$(GREEN)✓ SUCCESS:$(RESET) %s\n" "$(1)"
    @printf "$(RESET)$(BG_GREEN)  $(RESET)  $(GREEN)└─────────────────────────────────────────────────────────┘$(RESET)\n\n"
endef

define display_success_matrix
	_render_matrix() { \
		printf "\n"; \
		printf "$(RESET)$(BG_GREEN)  $(RESET)  $(GREEN)┌─────────────────────────────────────────────────────────┐$(RESET)\n"; \
		printf "$(RESET)$(BG_GREEN)  $(RESET)  $(BOLD)$(GREEN)✓ SUCCESS$(RESET)\n"; \
		for line in "$$@"; do \
			printf "$(RESET)$(BG_GREEN)  $(RESET)    $(GREEN)✓$(RESET) %s\n" "$$line"; \
		done; \
		printf "$(RESET)$(BG_GREEN)  $(RESET)  $(GREEN)└─────────────────────────────────────────────────────────┘$(RESET)\n\n"; \
	}; _render_matrix
endef

define display_error
    { \
        printf "\n" >&2; \
        printf "$(RESET)$(BG_RED)  $(RESET)  $(RED)┌─────────────────────────────────────────────────────────┐$(RESET)\n" >&2; \
        printf "$(RESET)$(BG_RED)  $(RESET)  $(BOLD)$(RED)✗ ERROR:$(RESET) %s\n" "$(1)" >&2; \
        printf "$(RESET)$(BG_RED)  $(RESET)  $(RED)└─────────────────────────────────────────────────────────┘$(RESET)\n\n" >&2; \
        exit 1; \
    }
endef

define display_warning
    @printf "\n"
    @printf "$(RESET)$(BG_YELLOW)  $(RESET)  $(YELLOW)┌─────────────────────────────────────────────────────────┐$(RESET)\n"
    @printf "$(RESET)$(BG_YELLOW)  $(RESET)  $(BOLD)$(YELLOW)⚠ WARNING:$(RESET) %s\n" "$(1)"
    @printf "$(RESET)$(BG_YELLOW)  $(RESET)  $(YELLOW)└─────────────────────────────────────────────────────────┘$(RESET)\n\n"
endef

define display_elapsed
	end_time=$(1); \
	elapsed=$$((end_time - $(2))); \
	minutes=$$((elapsed / 60)); \
	seconds=$$((elapsed % 60)); \
 	printf "\n" ; \
 	printf "$(RESET)$(BG_CYAN)  $(RESET)  $(CYAN)┌─────────────────────────────────────────────────────────┐$(RESET)\n"; \
	printf "$(RESET)$(BG_CYAN)  $(RESET)  $(BOLD)$(CYAN)⏱ Duration:$(RESET) $${minutes}m $${seconds}s\n"; \
	printf "$(RESET)$(BG_CYAN)  $(RESET)  $(CYAN)└─────────────────────────────────────────────────────────┘$(RESET)\n\n";
endef

# Avoid some execution from prod environment
define assert_not_prod
    @if [ "$(ENVIRONMENT)" = "prod" ] || [ "$(ENVIRONMENT)" = "production" ]; then \
        printf "$(RED)   Uniquement autorisé en environnement de développement !$(RESET)\n"; \
        exit 1; \
    fi
endef

# =====================================================================

CHECKMAKE := checkmake --config=$(TOOLS_CONFIG_DIR)/checkmake.ini
.PHONY: cs-makefile
cs-makefile: ## Lint Makefile with checkmake
	@$(call display_subtitle,Checking if checkmake is installed...)
	@if ! command -v checkmake >/dev/null 2>&1; then \
		$(call display_error,checkmake is not installed. Please run 'brew install checkmake' or 'apt install checkmake'.) \
	fi
	$(call display_success,checkmake is installed)
	@$(call display_subtitle,Running checkmake...)
	@$(CHECKMAKE) Makefile
	$(call display_success,Makefile formatting is perfect!)

# =====================================================================
##@ DOCKER
.PHONY: build
build: ## Docker build
	$(call display_title,Building Docker,${ICON_BUILD})
	@$(DC) build 

.PHONY: up
up: ## Run Docker containers
	$(call display_title,Starting containers,${ICON_DOCKER})
	@$(DC) up -d 

.PHONY: down
down: ## Stop Docker containers
	$(call display_title,Stopping containers,${ICON_DOCKER})
	@$(DC) down

.PHONY: destroy
destroy: ## Stop and remove Docker containers
	$(call display_title,Stopping and remove containers,${ICON_DOCKER})
	@$(DC) down --remove-orphans

.PHONY: restart
FORCE ?= 0
restart: ## Docker restart (FORCE=1 to destroy and rebuild)
	@if [ "$(FORCE)" = "1" ]; then \
		$(MAKE) destroy; \
		$(MAKE) build; \
		$(MAKE) up; \
	else \
		$(MAKE) down; \
		$(MAKE) up; \
	fi

.PHONY: shell
shell: ## Run a shell in the PHP container
	$(call display_title,Running shell in PHP container,${ICON_SHELL})
	@$(DC_EXEC) bash

.PHONY: logs
LOGS_SERVICE ?= all
logs: ## Show Docker logs (LOGS_SERVICE=app|db|nginx)
	$(call display_title,Displaying Docker logs,${ICON_DEBUG})
	@if [ "$(LOGS_SERVICE)" = "all" ]; then \
		$(DC) logs -f --tail=100; \
	else \
		$(DC) logs -f --tail=100 $(LOGS_SERVICE); \
	fi

# =====================================================================
##@ CHECKERS

.PHONY: doctor
doctor: ## Check system requirements and project health
	$(call display_title,Running health check,${ICON_DEBUG})
	@$(MAKE) --no-print-directory check-docker
	@$(MAKE) --no-print-directory check-containers
	@$(MAKE) --no-print-directory check-env
	@$(MAKE) --no-print-directory check-ports
	@$(MAKE) --no-print-directory check-dependencies
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
	@$(call display_subtitle,Checking network ports...)
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
	@$(call display_subtitle,Checking .env file...)
	@[ -f .env ] || $(call display_error,.env file is missing. Please create it from .env.dist.)
	$(call display_success,.env file exists.)

.PHONY: check-dependencies
check-dependencies: ## Check if PHP dependencies - vendor directory - are installed
	@$(call display_subtitle,Checking PHP dependencies...)
	@$(DC) ps app | grep -q "Up" || $(call display_warning,Containers are not running. Cannot check 'vendor' (run 'make up').)
	@if $(DC) ps app | grep -q "Up"; then \
		if ! $(DC_EXEC) [ -d vendor ]; then \
			printf "\n${RED}✗ ERROR${RESET}: %s\n\n" "'vendor' directory is missing. Run installation." >&2; exit 1; \
		else \
			printf "  $(GREEN)✓$(RESET) PHP dependencies (vendor) are installed.\n"; \
		fi; \
	fi

# =====================================================================
##@ DEVELOPMENT

.PHONY: cc
CC_ENV ?= 
cc: ## Run bin/console cache:clear from docker (CC_ENV=prod|dev|test)
	@$(call display_title,Clearing Symfony cache,${ICON_CLEAN})
	@start_time=$$(date +%s); \
	if [ -n "$(CC_ENV)" ]; then \
		$(SYMFONY) cache:clear --env=$(CC_ENV); \
	else \
		$(SYMFONY) cache:clear; \
	fi; \
	$(call display_elapsed,$$(date +%s),$$start_time)

.PHONY: composer-clear
composer-clear: ## Clear Composer cache and reinstall dependencies
	$(call display_title,Clearing Composer cache and reinstalling dependencies,${ICON_CLEAN})
	@rm -rf vendor/
	@$(COMPOSER) clear-cache
	@$(COMPOSER) install

.PHONY: clean
clean: ## Clean temporary files: cache, coverage, logs, public build
	$(call display_title,Cleaning temporary files,${ICON_CLEAN})
	@rm -rf var/cache/* var/log/* public/build/* coverage/ .phpunit.result.cache 
	@$(call display_success,Temporary files cleaned.)

.PHONY: watch
watch: ## Watch Tailwind CSS changes and re-build
	$(call display_title,Watching Tailwind CSS changes and re-building,${ICON_BUILD})
	@$(SYMFONY) tailwind:build --watch

.PHONY: secret
secret: ## Generate a new APP_SECRET and display it
	$(call display_title,Generating new APP_SECRET,${ICON_INSTALL})
	@printf "  $(GREEN)APP_SECRET=$(RESET)"; $(DC_EXEC) openssl rand -hex 32

.PHONY: migration
migration: ## Run Doctrine migrations
	$(call assert_not_prod)
	$(call display_title,Running Doctrine migrations,${ICON_DATA})
	@$(SYMFONY) make:migration
	@$(SYMFONY) doctrine:migrations:migrate

.PHONY: fixtures
fixtures: ## Load Doctrine fixtures
	$(call assert_not_prod)
	$(call display_title,Loading Doctrine fixtures,${ICON_DATA})
	@$(SYMFONY) doctrine:fixtures:load

.PHONY: cs
CS_TARGET ?= all
cs: ## Check all coding standards: PHP, Twig, CSS (CS_TARGET=all|php|yaml|twig|front)
	$(call display_title,Checking coding standards,${ICON_CS})
	@if [ "$(CS_TARGET)" = "all" ]; then \
		$(MAKE) --no-print-directory cs-php; \
		$(MAKE) --no-print-directory cs-yaml; \
		$(MAKE) --no-print-directory cs-twig; \
		$(MAKE) --no-print-directory cs-front; \
	else \
		$(MAKE) --no-print-directory cs-$(CS_TARGET); \
	fi;
	@$(display_success_matrix) \
		"PHP coding standards check completed." \
		"PHPStan analysis completed." \
		"YAML linting completed." \
		"Twig coding standards check completed." \
		"CSS and JS coding standards check completed."

.PHONY: cs-php
PHP_FIX ?= 0
cs-php: ## PHP CS Fixer (PHP_FIX=1 to actually fix)
	$(call assert_not_prod)
	@if [ "$(PHP_FIX)" = "1" ]; then \
		$(call display_subtitle, PHP CS Fixer in verbose mode...); \
		$(CSPHP) fix --verbose; \
	else \
 		$(call display_subtitle,Dry running PHP coding standards...); \
		$(CSPHP) check --verbose; \
		$(call display_subtitle,Running PHPStan analysis...); \
		$(PHPSTAN) analyse; \
	fi;

.PHONY: cs-yaml
cs-yaml: ## Validate YAML files
	$(call assert_not_prod)
	@$(call display_subtitle,Validate YAML files from config directory...)
	@$(SYMFONY) lint:yaml config/

.PHONY: cs-twig
TWIG_FIX ?= 0
cs-twig: ## Twig CS Fixer (TWIG_FIX=1 to actually fix)
	$(call assert_not_prod)
	@if [ "$(TWIG_FIX)" = "1" ]; then \
		$(call display_subtitle,Running Twig CS Fixer in fix mode...); \
		$(CSTWIG) fix templates/; \
		$(SYMFONY) lint:twig templates/; \
	else \
		$(call display_subtitle,Dry running Twig CS Fixer and display diff...); \
		$(CSTWIG) check templates/; \
	fi;

.PHONY: cs-front
FRONT_FIX ?= 0
cs-front: ## Run linters for CSS and JS (FRONT_FIX=1 to actually fix)
	$(call assert_not_prod)
	@if [ "$(FRONT_FIX)" = "1" ]; then \
		$(call display_subtitle,Running ESLint in fix mode...); \
		$(ESLINT) assets/scripts/ --fix; \
		$(call display_subtitle,Running Stylelint in fix mode...); \
		$(STYLELINT) assets/styles/ --fix; \
		$(call display_subtitle,Running Prettier in fix mode...); \
		$(PRETTIER) assets/ --write; \
		$(call display_subtitle,Running Biome in fix mode...); \
		$(BIOME) check --write; \
	else \
		$(call display_subtitle,Running ESLint...); \
		$(ESLINT) assets/scripts/; \
		$(call display_subtitle,Running Stylelint...); \
		$(STYLELINT) assets/styles/; \
		$(call display_subtitle,Running Prettier...); \
		$(PRETTIER) assets/ --check; \
		$(call display_subtitle,Running Biome...); \
		$(BIOME) lint; \
	fi;

.PHONY: cs-fix
cs-fix: ## Fix all coding standards: PHP, Twig, CSS
	$(call assert_not_prod)
	$(call display_title,Fixing coding standards,${ICON_CS})
	@$(MAKE) --no-print-directory cs-php PHP_FIX=1
	@$(MAKE) --no-print-directory cs-twig TWIG_FIX=1
	@$(MAKE) --no-print-directory cs-front-fix
	@$(display_success_matrix) \
		"PHP coding standards fixed." \
		"Twig coding standards fixed." \
		"CSS and JS coding standards fixed."

# =====================================================================
##@ TESTS

.PHONY: pre-commit
pre-commit:
	@$(MAKE) cs-makefile
	@$(MAKE) readme
	@git add README.md

.PHONY: grum-install
grum-install: ## Install GrumPHP hooks
	$(call display_title,Installing GrumPHP hooks,${ICON_DEBUG})
	@$(GRUMPHP) git:init

.PHONY: grum-run
grum-run: ## Run GrumPHP checks
	$(call display_title,Running GrumPHP checks,${ICON_TEST})
	@$(GRUMPHP) run

.PHONY: qa
qa: ## Run complete Quality Assurance suite: Lint, Static Analysis, Tests
	$(call assert_not_prod)
	@start=$$(date +%s); \
	$(MAKE) --no-print-directory cs || exit 1; \
	$(MAKE) --no-print-directory qa-analyse || exit 1; \
	$(MAKE) --no-print-directory cover || exit 1; \
	$(MAKE) --no-print-directory test-mutation || exit 1; \
	$(MAKE) --no-print-directory test-arch || exit 1; \
	$(MAKE) --no-print-directory test-ui || exit 1; \
	$(call display_elapsed,$$(date +%s),$$start)
	@$(display_success_matrix) \
		"Quality Assurance Suite Passed !" \
		"Coding standards passed." \
		"Static analysis passed." \
		"Tests passed." \
		"Infection tests passed." \
		"Architecture tests passed." \
		"UI tests passed."
	@printf "\n  ${BOLD}${GREEN}✨ QA passed successfully! Your code is amazing. ✨${RESET}\n\n"

.PHONY: test
test: ## Run PHPUnit tests
	$(call display_title,Running PHPUnit tests,${ICON_TEST})
	@$(MAKE) --no-print-directory check-containers
	@$(call display_subtitle,Generating fixtures...)
#@$(SYMFONY) cache:clear --env=test
#$(SYMFONY) app:generate-fixtures --group=test
	@$(call display_subtitle,Preparing test database...)
	- @$(SYMFONY) doctrine:schema:drop --env=test --force --full-database
	@$(SYMFONY) doctrine:schema:update --env=test --force
	@$(SYMFONY) doctrine:fixtures:load --env=test --group=test --no-interaction
	@$(call display_subtitle,Running PHPUnit tests...)
	@$(PHPUNIT) --exclude-group=UI

.PHONY: qa-analyse
qa-analyse: ## Run static analysis
	@$(call display_subtitle,Running static analysis...); 
	$(PHPSTAN) analyse; \
	$(call display_subtitle,Running Deptrac...); \
	$(DEPTRAC) analyse; \

.PHONY: qa-rector
REC_FIX ?= 0
qa-rector: ## Run Rector (REC_FIX=1 to actually fix)
	@$(call display_subtitle,Running Rector...);
	@if [ "$(REC_FIX)" = "1" ]; then \
		$(RECTOR); \
	else \
		$(RECTOR) --dry-run; \
	fi;

.PHONY: test-mutation
test-mutation: ## Run Infection
	@$(call display_subtitle,Running Infection...)
	@$(INFECTION) --threads=4

.PHONY: test-arch
test-arch: ## Run phparkitect
	@$(call display_subtitle,Running PHPArkitect...)
	@$(PHPARKITECT) check

.PHONY: test-ui
test-ui: ## Run PHPUnit tests for UI group
	@$(call display_subtitle,Running PHPUnit tests for UI group...)
	@$(PHPUNIT) --group=UI

.PHONY: cover
cover: ## Run PHPUnit tests with coverage
	@$(call display_subtitle,Clearing cache...)
	@$(SYMFONY) cache:clear --env=test
	@$(call display_subtitle,Running PHPUnit tests with coverage...)
	@$(PHPUNIT) --coverage-html coverage/ --exclude-group=UI
	@$(call display_success,Coverage report generated in 'coverage/' directory.)

# =====================================================================
##@ HELP
TARGET_MAX_CHAR_NUM ?= 15
DESC_MAX_CHAR_NUM ?= 45

.PHONY: help
help: ## Show this help message
	@printf '\n'
	@printf '  ${BOLD}${YELLOW}${PROJECT_NAME}${RESET} ${BLUE}(${GREEN}${ENVIRONMENT}${BLUE})${RESET}\n'
	@printf '  ${BLUE}───────────────────────────────────────────────────────────────────────────────${RESET}\n'
	@printf '\n'
	@printf '  ${BOLD}Usage:${RESET}\n'
	@printf '    ${YELLOW}make${RESET} ${GREEN}<target>${RESET} ${PURPLE}[OPTION=value]${RESET}\n'
	@printf '\n'
	@printf '  ${BOLD}Available targets:${RESET}\n'
	@awk -v yellow="${YELLOW}" -v green="${GREEN}" -v cyan="${CYAN}" -v purple="${PURPLE}" -v reset="${RESET}" -v tw="$(TARGET_MAX_CHAR_NUM)" -v dw="$(DESC_MAX_CHAR_NUM)" ' \
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
			\
			options=""; \
			p_start = index(desc, "("); \
			p_end = index(desc, ")"); \
			if (p_start > 0 && p_end > p_start) { \
				options = substr(desc, p_start + 1, p_end - p_start - 1); \
				desc = substr(desc, 1, p_start - 1); \
				gsub(/[ \t]+$$/, "", desc); \
				options = purple""options""reset; \
			} \
			\
			printf "    %s%-" tw "s%s  %-" dw "s  %s\n", yellow, cmd, reset, desc, options; \
		}'  $(MAKEFILE_LIST)
	@printf '\n'
	@printf '  ${BOLD}Examples:${RESET}\n'
	@printf '    make test            	# Run PHPUnit tests\n'
	@printf '    make cs              	# Check and fix coding standards\n'
	@printf '    make cs-php PHP_FIX=1	# Run linter and apply changes\n'
	@printf '\n'

.PHONY: readme
readme: ## Update README.md Makefile section
	@$(call display_subtitle,Updating README.md...)

	@if [ ! -f README.md ]; then \
		echo "README.md not found"; \
		exit 1; \
	fi

	@tmp_help=$$(mktemp); \
	tmp_readme=$$(mktemp); \
	\
	echo '```bash' > $$tmp_help; \
	$(MAKE) --no-print-directory help | sed -E 's/\x1B\[[0-9;]*[[:alpha:]]//g' >> $$tmp_help; \
	echo '```' >> $$tmp_help; \
	\
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
	' README.md > $$tmp_readme; \
	\
	mv $$tmp_readme README.md; \
	rm -f $$tmp_help

	@$(call display_success,README.md updated successfully!)