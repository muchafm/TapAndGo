### INSTRUCTIONS

# With make

After cloning the project, install it using these commands (which require you to have Make on your machine):

``` make dev ```

# Without

After cloning the project, install it using these commands:

``` cp apps/api/.env.dist apps/api/.env ```

And

``` docker-compose build --no-cache```

Then

``` docker-compose up -d ```

``` docker-compose run --rm api composer install ```

``` docker-compose run --rm api bin/console doctrine:database:drop --if-exists --force ```

``` docker-compose run --rm api bin/console doctrine:database:create --if-not-exists ```

```docker-compose run --rm api bin/console doctrine:migration:migrate ```

# Tests

``` docker-compose exec api vendor/bin/phpspec run ```