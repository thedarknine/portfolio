.DEFAULT_GOAL := help
SHELL := /bin/bash
include .env
NOW := $(shell date +"%Y%m%d_%H%M%S")

#= COLORS =============================================================
ifneq (,$(findstring xterm,$(TERM)))
	BLACK        := $(shell tput -Txterm setaf 0)
	RED          := $(shell tput -Txterm setaf 1)
	GREEN        := $(shell tput -Txterm setaf 2)
	YELLOW       := $(shell tput -Txterm setaf 3)
	LIGHTPURPLE  := $(shell tput -Txterm setaf 4)
	PURPLE       := $(shell tput -Txterm setaf 5)
	BLUE         := $(shell tput -Txterm setaf 6)
	WHITE        := $(shell tput -Txterm setaf 7)
	RESET        := $(shell tput -Txterm sgr0)
	BOLD         := $(shell tput -Txterm bold)
else
	BLACK        :=
	RED          :=
	GREEN        :=
	YELLOW       :=
	LIGHTPURPLE  :=
	PURPLE       :=
	BLUE         :=
	WHITE        :=
	RESET        :=
	BOLD         :=
endif

#= FONCTIONS ===========================================================
define display_title
	@echo ''
	@echo '${PURPLE}• $2 ${ICON} $1${RESET}'
	$(if $(3), $(call display_subtitle,$3), @echo '')
endef

define display_subtitle
	@echo '  ${BLUE}» $1${RESET}'
	@echo ''
endef


# Gérer les erreurs avec un message coloré
define handle_error
	@echo ''
	@echo '${RED}✗ ERREUR${RESET}: $1' >&2
	@exit 1
endef

#= VARIABLES GLOBALES =================================================
TARGET_MAX_CHAR_NUM=20

#= ICÔNES =============================================================
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


# =====================================================================
##@ DOCKER
.PHONY: build
build: ## Docker build
	$(call display_title,Building Docker ........................................,${ICON_BUILD})
	docker compose build 

.PHONY: up
up: ## Run Docker containers
	$(call display_title,Starting containers ....................................,${ICON_DOCKER})
	docker compose up -d 

.PHONY: down
down: ## Stop Docker containers
	$(call display_title,Stopping containers ....................................,${ICON_DOCKER})
	docker compose down

.PHONY: shutdown
shutdown: ## Stop and remove Docker containers
	$(call display_title,Stopping and remove containers .........................,${ICON_DOCKER})
	docker compose down --remove-orphans

.PHONY: restart
restart: ## Docker restart
	@make down && make up

.PHONY: force-restart
force-restart: ## Docker restart (down, remove all containers, re-build and up)
	@make shutdown && make build && make up

.PHONY: shell
shell: ## Run a shell in the PHP container
	$(call display_title,Shell PHP ..............................................,${ICON_SHELL})
	docker compose exec app bash

.PHONY: nginx
nginx: ## Show docker logs for nginx
	$(call display_title,Logs Nginx .............................................,${ICON_DEBUG})
	docker compose logs -f engine


# =====================================================================
##@ DEVELOPMENT
.PHONY: watch
watch: ## Watch Tailwind CSS changes and re-build
	$(call display_title,Watching Tailwind CSS changes and re-building ...........,${ICON_BUILD})
	php bin/console tailwind:build --watch

.PHONY: migrations
migrations: ## Run Doctrine migrations
	$(call display_title,Running Doctrine migrations ..........................,${ICON_DATA})
	php bin/console make:migration && php bin/console doctrine:migrations:migrate

.PHONY: fixtures
fixtures: ## Load Doctrine fixtures
	$(call display_title,Loading Doctrine fixtures ............................,${ICON_DATA})
	php bin/console doctrine:fixtures:load

.PHONY: cc
cc: ## Run bin/console cache:clear from docker
	$(call display_title,Clearing Symfony cache .................................,${ICON_CLEAN})
	symfony console cache:clear

.PHONY: cs-check
cs-check: ## Check all coding standards (PHP, Twig, CSS)
	$(call display_title,Checking coding standards .............................,${ICON_CS})
	@make cs-php-check && make cs-twig-check

.PHONY: cs
cs: ## Fix all coding standards (PHP, Twig, CSS)
	$(call display_title,Fixing coding standards ..............................,${ICON_CS})
	@make cs-php && make cs-twig

.PHONY: cs-php-check
cs-php-check: ## PHP CS Fixer - Only show diff
	$(call display_title,Dry running PHP CS Fixer and display diff ..............,${ICON_CS})
	- ./vendor/bin/php-cs-fixer check --verbose --config=.coding-standard-linters/.php-cs-fixer.php --cache-file=.coding-standard-linters/.php-cs-fixer.cache
	- ./vendor/bin/phpstan analyse --memory-limit=512M --configuration=.coding-standard-linters/phpstan.neon

.PHONY: cs-php
cs-php: ## PHP CS Fixer - Fix code
	$(call display_title,Dry running PHP CS Fixer and display diff ..............,${ICON_CS})
	./vendor/bin/php-cs-fixer fix --diff --verbose --config=.coding-standard-linters/.php-cs-fixer.php --cache-file=.coding-standard-linters/.php-cs-fixer.cache
	

.PHONY: cs-twig-check
cs-twig-check: ## Twig CS Fixer - Only show diff
	$(call display_title,Dry running Twig CS Fixer and display diff .............,${ICON_CS})
	./vendor/bin/twig-cs-fixer check --config=.coding-standard-linters/.twig-cs-fixer.php templates/

.PHONY: cs-twig
cs-twig: ## Twig CS Fixer - Fix code
	$(call display_title,Dry running Twig CS Fixer and display diff .............,${ICON_CS})
	- ./vendor/bin/twig-cs-fixer fix --config=.coding-standard-linters/.twig-cs-fixer.php templates/
	- php bin/console lint:twig templates/

.PHONY: cs-front
cs-front: ## Run linters for CSS and JS
	$(call display_title,Running linters for CSS and JS ........................,${ICON_CS})
	@echo "Running ESLint..."
	-@npx eslint --config .coding-standard-linters/eslint.config.mjs assets/scripts/

	@echo "Running Stylelint..."
	-@npx stylelint --config .coding-standard-linters/.stylelintrc.json assets/styles/

	@echo "Running Prettier..."
	-@npx npx prettier --config .coding-standard-linters/.prettierrc assets/ --check

# LINTER bin/biome lint

# =====================================================================
##@ HELP
.PHONY: help
help: ## Show this help message
	@echo ''
	@echo '  ${BOLD}${YELLOW}${PROJECT_NAME}${RESET} ${BLUE}(${GREEN}${ENVIRONMENT}${BLUE})${RESET}'
	@echo '  ${BLUE}────────────────────────────────────────────────${RESET}'
	@echo ''
	@echo '  ${BOLD}Usage:${RESET}'
	@echo '    ${YELLOW}make${RESET} ${GREEN}<target>${RESET}'
	@echo ''
	@echo '  ${BOLD}Available targets:${RESET}'
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
			printf "    %s%-" width "s%s %s\n", yellow, cmd, reset, desc; \
		}'  $(MAKEFILE_LIST)
	@echo ''
	@echo '  ${BOLD}Examples:${RESET}'
	@echo '    make test            # Run PHPUnit tests'
	@echo '    make cs              # Check and fix coding standards'
	@echo '    make up              # Run Docker containers'
	@echo ''
