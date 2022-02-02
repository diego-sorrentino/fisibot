FROM php:7.4-fpm-alpine  
RUN apk update && \
	apk add autoconf gcc g++ make yaml-dev php7-curl && \
	pecl install yaml && \
	docker-php-ext-enable yaml && \
	apk del autoconf gcc g++ make
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
