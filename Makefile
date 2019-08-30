SHELL := /bin/bash

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

php.run.root: ## Run a command in the php container as root, requires a `cmd` argument
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet exec -u root php-fpm ${cmd}

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

xdebug.disable:
	make php.run.root cmd='xdebug-disable'
	@echo '!!! xDebug disabled !!!'

xdebug.enable:
	make php.run.root cmd='xdebug-enable'
	@echo '!!! xDebug enabled !!!'

test.keys:
	make php.run cmd='openssl genrsa -out /app/src/config/packages/test/jwt_keys/private-test.pem -passout pass:test -aes256 4096'
	make php.run cmd='openssl rsa -passin pass:test -pubout -in /app/src/config/packages/test/jwt_keys/private-test.pem -out /app/src/config/packages/test/jwt_keys/public-test.pem'

test: test.keys ## Run phpunit tests
	make xdebug.disable
	make php.run cmd="/app/src/bin/phpunit"
	make xdebug.enable

test.unit: ## Run phpunit unit tests
	make xdebug.disable
	make php.run cmd="/app/src/bin/phpunit --testsuite=unit"
	make xdebug.enable

test.integration: ## Run phpunit integration tests
	make xdebug.disable
	make php.run cmd="/app/src/bin/phpunit --testsuite=integration"
	make xdebug.enable

test.functional: test.keys ## Run phpunit functional tests, please beware that this requires the application to be running
	make xdebug.disable
	make php.run cmd="/app/src/bin/phpunit --testsuite=functional"
	make xdebug.enable

test.coverage: test.keys ## Run unit tests with PHPunit and create a coverage report in $PWD/PHPunitReport
	make xdebug.disable
	make php.run cmd="/app/src/bin/phpunit -c /app/src --coverage-html /app/src/test-coverage"
	@echo 'Generated a coverage report in backend/test-coverage!'
	make xdebug.enable

composer.install: ## Run composer install in the php container in development
	make xdebug.disable
	make php.run cmd="bin/composer install"
	make xdebug.enable

composer.update: ## Run composer update in the php container in development
	make xdebug.disable
	make php.run cmd="bin/composer update"
	make xdebug.enable

fixtures: ## Throw away the database and fill it with test data (only in development!)
	make xdebug.disable
	make php.run cmd="bin/console doctrine:fixtures:load -n"
	make xdebug.enable

migrations.diff: ## Generate a new migration based on the ORM files
	make xdebug.disable
	make php.run cmd="bin/console doctrine:cache:clear-metadata"
	make php.run cmd="bin/console doctrine:migrations:diff"
	make xdebug.enable

migrations.migrate: ## Migrate the database
	make xdebug.disable
	make php.run cmd="bin/console doctrine:migrations:migrate -n"
	make xdebug.enable

schema.validate: ## Validate the mapping settings
	make xdebug.disable
	make php.run cmd="bin/console doctrine:cache:clear-metadata"
	make php.run cmd="bin/console doctrine:schema:validate"
	make xdebug.enable

database.reset: ## Delete and recreate the database, then fill it with fixture data
	make xdebug.disable
	echo ""
	echo "I sincerely hope you know what you're doing..."
	echo "Deleting database..."
	echo ""
	make php.run cmd="bin/console doctrine:database:drop --force"
	make php.run cmd="bin/console doctrine:database:create"
	make migrations.migrate
	make fixtures
	make xdebug.enable

cache.clear: ## Clear the cache
	make xdebug.disable
	make php.run cmd="bin/console cache:clear"
	make php.run cmd="bin/console doctrine:cache:clear-metadata"
	make php.run cmd="bin/console doctrine:cache:clear-query"
	make php.run cmd="bin/console doctrine:cache:clear-result"
	make xdebug.enable

restart: ## Restart containers
	docker-compose -f docker/docker-compose.yml -f docker/docker-compose.dev.yml -p dplanet restart

ansible.vault.password: ## Input the vault password and save it to ../.devnl-backend-vault-password
	@echo "Please ask one of your fellow developers for the vault password and input it here:"
	@echo
	read -s -p "Enter Password: " password; \
	echo $$password > ${CURDIR}/../.dplanet-vault-password
	@echo "Thanks!"

ansible.vault.expand: ## Expose ansible-vault secrets, assuming password file exists
	docker run \
		--rm \
		--workdir=/ansible \
		-v ${CURDIR}/../.dplanet-vault:/rootdir \
		-v ${CURDIR}/ansible:/ansible \
		-v ${CURDIR}/../.dplanet-vault-password:/.password \
		-it survivorbat/ansible:v0.2 \
		ansible-playbook expand-secrets-dev.yml -e output_folder=/rootdir --vault-password-file=/.password -e uid=$(shell id -u) -e gid=$(shell id -g)

ansible.vault.edit: ## Edit the vault file to remove or add secrets, assuming password file exists
	docker run \
		--rm \
		--workdir=/ansible \
		-v $(CURDIR)/ansible:/ansible \
		-v ${CURDIR}/../.dplanet-vault-password:/.password \
		-it survivorbat/ansible:v0.2 \
		ansible-vault edit /ansible/shared_vars/vault.yml --vault-password-file=/.password

ansible.lint: ## Run Ansible Lint
	docker run \
		--rm \
		--workdir=/ansible \
		-v $(CURDIR)/ansible:/ansible \
		-it survivorbat/ansible:v0.2 \
		ansible-lint /ansible/site.yml
