ARG PHP_VERSION=7.2
ARG NGINX_VERSION=1.15

# FOR PHP
FROM php:${PHP_VERSION}-fpm-alpine as php_fpm

# persistent / runtime deps
RUN apk add --no-cache \
        $PHPIZE_DEPS \
		acl \
		file \
		gettext \
		git \
		libpng-dev \
		libjpeg-turbo-dev \
		freetype-dev \
		bzip2-dev \
		icu-dev \
		libsodium-dev \
		rabbitmq-c-dev \
		supervisor \
	; \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
        && docker-php-ext-install bz2 \
        && docker-php-ext-install opcache \
        && docker-php-ext-install intl \
        && docker-php-ext-install zip \
        && docker-php-ext-install pdo_mysql \
        && docker-php-ext-install sodium \
        && docker-php-ext-install bcmath \
        && pecl install amqp \
        && docker-php-ext-enable amqp

RUN pecl install mongodb-1.5.3 \
    && echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/docker-php-ext-mongodb.ini \
    && pecl install apcu-5.1.12 \
    && echo "extension=apcu.so" >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    ; \
    pecl clear-cache;

COPY docker/php/config/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY docker/php/config/php.ini /usr/local/etc/php/
COPY docker/php/config/amqp.ini /usr/local/etc/php/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN set -eux; \
	composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache

WORKDIR /var/www

ENV PATH="${PATH}:/root/.composer/vendor/bin"

# copy only specifically what we need
COPY composer.json composer.lock symfony.lock .env.dist ./
COPY bin bin/
COPY config config/
COPY src src/
COPY public public/

RUN set -eux; \
	composer install --prefer-dist --no-progress --no-suggest; \
	composer clear-cache

RUN set -eux; \
    mkdir -p var/cache var/log var/data; \
    chmod +x bin/console; sync

VOLUME /var/www/var

RUN mkdir -p /var/log/supervisor
COPY docker/php/supervisor.conf /etc/supervisor/conf.d/supervisord.conf

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# FOR NGINX

FROM nginx:${NGINX_VERSION}-alpine AS nginx

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www

COPY --from=php_fpm /var/www/public public/
