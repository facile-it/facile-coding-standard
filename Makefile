.PHONY: pre-commit-check

cs:
	vendor/bin/php-cs-fixer fix --verbose

cs-dry-run:
	vendor/bin/php-cs-fixer fix --verbose --dry-run

psalm:
	vendor/bin/psalm

rector:
	vendor/bin/rector

test:
	vendor/bin/phpunit

pre-commit-check: rector cs psalm test
