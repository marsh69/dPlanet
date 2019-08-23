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

php.restart: ## Restart php container
	docker restart dplanet_php-fpm_1

php.run: ## Run a command in the php container, requires a 'cmd' argument
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet exec -u php php-fpm ${cmd}

php.sh: ## Open the shell of the php container
	docker exec -u php -it dplanet_php-fpm_1 sh

php.logs: ## Get the php logs in realtime
	docker logs -f dplanet_php-fpm_1

php.fix: ## Run the php-cs-fixer over all the code in the repository
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet exec -u php php-fpm /app/src/vendor/bin/php-cs-fixer fix /app/src/src

php.stan: ## Run phpstan to check php code
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet exec -u php php-fpm /app/src/vendor/bin/phpstan analyze -c /app/src/phpstan.neon --level=2 -a autoload.php /app/src/src

php.hooks: hooks
hooks: ## Run hooks like phpstan and php-cs-fixer
	make php.fix
	make php.stan
	make js.fix

node.sh: node.shell
node.shell: ## Enter the shell of the node container
	docker exec -itu node dplanet_node_1 sh

js.fix: node.fix
node.fix: ## Run prettier over the code
	docker exec -itu node dplanet_node_1 /app/node_modules/prettier/bin-prettier.js fix --write /app/src/**/*

test: ## Run phpunit tests
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet exec -u php php-fpm bin/phpunit

test.unit: ## Run phpunit unit tests
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet exec -u php php-fpm bin/phpunit --testsuite=unit

test.integration: ## Run phpunit integration tests
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet exec -u php php-fpm bin/phpunit --testsuite=integration

test.functional: ## Run phpunit functional tests, please beware that this requires the application to be running
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet exec -u php php-fpm bin/phpunit --testsuite=functional

test.coverage: ## Run unit tests with PHPunit and create a coverage report in $PWD/PHPunitReport
	make php.run cmd="/app/src/bin/phpunit -c /app/src --coverage-html /app/src/test-coverage"
	@echo 'Generated a coverage report in backend/test-coverage!'

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

vault.edit: ## Edit the secrets in the vault (requires .dplanet_password to be present
	ansible-vault edit ansible/shared_vars/vault.yml --vault-password-file=../.dplanet-vault-password

vault.expand: ## Expand the vault secrets locally
	ansible-playbook -i ansible/inventories/development --vault-password-file=../.dplanet-vault-password ansible/expand-secrets-dev.yml
