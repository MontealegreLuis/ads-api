SHELL = /bin/bash

.PHONY: tests

tests:
	@echo "Running scenarios.."
	@vendor/bin/behat
	@echo "Running unit tests"
	@vendor/bin/phpunit --testdox
