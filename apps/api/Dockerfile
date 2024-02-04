FROM nginx:1.23.3-alpine as nginx

COPY ./public/ /usr/src/app/public/

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

##################################

FROM composer:2.6.6 AS composer

##################################

FROM composer AS vendor-dev

WORKDIR /usr/src/app

COPY --chown=1000 ./composer.* ./
COPY --chown=1000 ./symfony.lock ./

RUN composer install \
        --ignore-platform-reqs \
        --no-cache \
        --no-interaction \
        --no-scripts \
        --prefer-dist

##################################

FROM php:8.3.2-fpm-alpine3.19 AS base

RUN apk add --no-cache \
        bash==5.2.21-r0 \
        git==2.43.0-r0 \
        icu-dev==74.1-r0 \
        pcre-dev==8.45-r3 \
 && apk add --no-cache --virtual=.build-deps \
        autoconf==2.71-r2 \
        g++==13.2.1_git20231014-r0 \
        make==4.4.1-r2 \
 && docker-php-ext-install -j"$(nproc)" \
        fileinfo \
        intl \
        opcache \
        pdo_mysql \
 && pecl install \
        apcu-5.1.21 \
 && docker-php-ext-enable \
        apcu \
 && apk del .build-deps

COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV COMPOSER_CACHE_DIR=/dev/null

CMD ["php-fpm"]

WORKDIR /usr/src/app

RUN chown -R 1000 /usr/src/app

USER 1000

ENV APP_ENV=dev
ENV APP_DEBUG=1
ENV APP_SECRET="#B{KV:ijP\>@~3<~9)"

COPY --chown=1000 . /usr/src/app
COPY --chown=1000 --from=vendor-dev /usr/src/app/vendor /usr/src/app/vendor

RUN composer check-platform-reqs

USER 1000

RUN composer run-script post-install-cmd
