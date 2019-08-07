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

test: ## Run phpunit tests
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p image-crawler exec -u php php-fpm bin/phpunit

test.unit: ## Run phpunit unit tests
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p image-crawler exec -u php php-fpm bin/phpunit --testsuite=unit

test.integration: ## Run phpunit integration tests
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p image-crawler exec -u php php-fpm bin/phpunit --testsuite=integration

test.functional: ## Run phpunit functional tests, please beware that this requires the application to be running
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p image-crawler exec -u php php-fpm bin/phpunit --testsuite=functional

composer.install: ## Run composer install in the php container in development
	make php.run cmd="bin/composer install"

composer.update: ## Run composer update in the php container in development
	make php.run cmd="bin/composer update"

fixtures: ## Throw away the database and fill it with test data (only in development!)
	make php.run cmd="bin/console doctrine:fixtures:load -n"

migrations.diff: ## Generate a new migration based on the ORM files
	make php.run cmd="bin/console doctrine:cache:clear-metadata"
	make php.run cmd="bin/console doctrine:migrations:diff"

migrations.migrate: ## Migrate the database
	make php.run cmd="bin/console doctrine:migrations:migrate -n"

schema.validate: ## Validate the mapping settings
	make php.run cmd="bin/console doctrine:cache:clear-metadata"
	make php.run cmd="bin/console doctrine:schema:validate"

database.reset: ## Delete and recreate the database, then fill it with fixture data
	echo ""
	echo "I sincerely hope you know what you're doing..."
	echo "Deleting database..."
	echo ""
	make php.run cmd="bin/console doctrine:database:drop --force"
	make php.run cmd="bin/console doctrine:database:create"
	make migrations.migrate
	make fixtures

cache.clear: ## Clear the cache
	make php.run cmd="bin/console cache:clear"
	make php.run cmd="bin/console doctrine:cache:clear-metadata"
	make php.run cmd="bin/console doctrine:cache:clear-query"
	make php.run cmd="bin/console doctrine:cache:clear-result"

restart: ## Restart containers
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet restart

prod.up: ## Start containers
	docker-compose -f docker/docker-compose.yml -p dplanet up -d

prod.down: ## Stop containers
	docker-compose -f docker/docker-compose.yml -p dplanet down

prod.restart: ## Restart containers in production mdoe
	docker-compose -f docker/docker-compose.yml -p dplanet restart
