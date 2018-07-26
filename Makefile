SHELL = /bin/bash

.PHONY: tests setup

tests:
	@echo "Running scenarios.."
	@vendor/bin/behat
	@echo "Running unit tests"
	@vendor/bin/phpunit --testdox

setup:
	@echo "Installing application dependencies"
	@composer install
	@echo "Creating database schema"
	@vendor/bin/doctrine orm:schema-tool:create
