dev: cp-env build install-deps up

cp-env:
	cp apps/api/.env.dist apps/api/.env

build:
	docker-compose build --no-cache

up:
	docker-compose up -d

install-deps:
	docker-compose run --rm api composer install

migrate:
	docker-compose run --rm api bin/console doctrine:database:drop --if-exists --force
	docker-compose run --rm api bin/console doctrine:database:create --if-not-exists
	docker-compose run --rm api bin/console doctrine:migration:migrate

phpstan:
	docker-compose exec api vendor/bin/phpstan

phpspec:
	docker-compose exec api vendor/bin/phpspec run