dev: cp-env build install-deps up

cp-env:
	cp apps/api/.env.dist apps/api/.env

build:
	docker-compose build --no-cache

up:
	docker-compose up -d

phpstan:
	docker-compose exec api vendor/bin/phpstan