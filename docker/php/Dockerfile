FROM composer:latest AS composer

WORKDIR /app

FROM php:8.5-fpm-alpine

WORKDIR /app

ARG UID=1000
ARG GID=1000

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN mkdir -p /app && chown -R $UID:$GID /app

RUN addgroup -g $GID user \
    && adduser -D -u $UID -G user user \
    && chown -R user:user /app

RUN apk add --no-cache \
    autoconf \
    bash \
    git \
    libzip-dev \
    icu-dev

RUN apk add --no-cache --virtual .phpize-deps ${PHPIZE_DEPS}
RUN pecl install apcu
RUN docker-php-ext-enable apcu
RUN docker-php-ext-configure zip
RUN docker-php-ext-install -j"$(nproc)" \
        zip \
        intl \
        pdo \
        pdo_mysql

RUN apk del --no-network .phpize-deps

COPY --chown=$UID:$GID back /app

ENV PATH="$PATH:/app/bin"

USER user

ENV APP_ENV=dev
ENV APP_DEBUG=1

COPY docker/php/php.ini /usr/local/etc/php/php.ini