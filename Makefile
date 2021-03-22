install:
	composer install

validate:
	composer validate

lint:
	composer run-script phpcs

test:
	composer test

test-coverage:
	composer test -- --coverage-clover build/logs/clover.xml