FROM php:8.1-fpm-alpine3.16

RUN apk add --update --no-cache \
    autoconf \
    build-base \
    git \
    libzip-dev \
    zip \
    && \
    docker-php-ext-install \
    opcache \
    zip \
    && \
    apk del --no-cache \
    autoconf \
    build-base

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
