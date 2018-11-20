FROM php:7.2-fpm

COPY ./php.ini  "$PHP_INI_DIR/php.ini"

RUN pecl install xdebug-2.6.1 \
    && docker-php-ext-enable xdebug