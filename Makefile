SHELL := /bin/sh

MAKEFLAGS := --silent --no-print-directory

.DEFAULT_GOAL := help

help:
	@echo "Please use 'make <target>' where <target> is one of"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z\._-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build images
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet build

up: ## Start containers in development mode
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet up -d

down: ## Stop containers
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet down

php.run: ## Run a command in the php container, requires a 'cmd' argument
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet exec -u php php-fpm ${cmd}

restart: ## Restart containers
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet restart

prod.up: ## Start containers
	docker-compose -f docker/docker-compose.yml -p dplanet up -d

prod.down: ## Stop containers
	docker-compose -f docker/docker-compose.yml -p dplanet down

prod.restart: ## Restart containers in production mdoe
	docker-compose -f docker/docker-compose.yml -p dplanet restart
