FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
        git \
        zip \
        unzip \
        curl \
        libzip-dev \
        oniguruma-dev \
        bash \
    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
