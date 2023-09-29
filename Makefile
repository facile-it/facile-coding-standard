.PHONY: pre-commit-check

cs:
	vendor/bin/php-cs-fixer fix --verbose

cs-dry-run:
	vendor/bin/php-cs-fixer fix --verbose --dry-run

psalm:
	vendor/bin/psalm

test:
	vendor/bin/phpunit

pre-commit-check: cs psalm test
