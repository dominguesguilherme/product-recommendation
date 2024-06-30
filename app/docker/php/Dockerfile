FROM php:8.0-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER="1"
ENV PATH="/app/bin:/app/vendor/bin:${PATH}"

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

WORKDIR /app

EXPOSE 9000

RUN apk add --no-cache $PHPIZE_DEPS \
    && apk add --no-cache mysql-client

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN install-php-extensions intl mysqli opcache pdo_mysql

ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]
CMD php-fpm

