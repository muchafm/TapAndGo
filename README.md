# Subject

TapAndGo provides bike parks for users in several cities. This project consists of an api that will feed a management and maintenance back-office, and a website that will display the location of the stations and the number of bikes available for users at each one.

### INSTRUCTIONS

## With make

After cloning the project, install it using these commands (which require you to have Make on your machine):

``` make dev```

```make migrate```

```make fixtures```


## Without

After cloning the project, install it using these commands:

``` cp apps/api/.env.dist apps/api/.env ```

And

``` docker-compose build --no-cache```

Then

``` docker-compose run --rm api composer install ```

``` docker-compose up -d ```

## Data

``` docker-compose run --rm api bin/console doctrine:database:drop --if-exists --force ```

``` docker-compose run --rm api bin/console doctrine:database:create --if-not-exists ```

```docker-compose run --rm api bin/console doctrine:migration:migrate ```

In order to add some fixtures

```docker-compose exec api php bin/console doctrine:fixtures:load --append```

# Tests

``` docker-compose exec api vendor/bin/phpspec run ```