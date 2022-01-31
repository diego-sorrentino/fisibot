FROM php:7.4-fpm-alpine  
RUN apk update && apk add autoconf gcc g++ make yaml-dev
RUN pecl install yaml && docker-php-ext-enable yaml
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
