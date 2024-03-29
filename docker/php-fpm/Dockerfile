#
# DOWNLOAD DEPENDENCIES
#
FROM php:7.3-fpm-alpine as builder

ARG USER_ID
ARG GROUP_ID

COPY backend/composer.json backend/composer.lock backend/symfony.lock /app/src/
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN addgroup -g ${GROUP_ID} php \
    && adduser -u ${USER_ID} -s /bin/sh -S php \
    && mkdir -p /app \
    && chown -R php:php /app \
    && apk add --update --no-cache --virtual \
       git \
    && composer install -d /app/src --optimize-autoloader --no-interaction --no-suggest --no-scripts --no-dev

#
# SET UP RUNTIME ENVIRONMENT
#
FROM php:7.3-fpm-alpine AS runtime

COPY --from=builder /etc/passwd /etc/group /etc/

RUN mkdir -p /app \
    && chown php:php /app \
    && apk add --update --no-cache \
       alpine-sdk \
       php7-redis \
       php7-opcache \
       php7-pdo_mysql \
       php7-pdo \
       autoconf \
    && pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && docker-php-ext-install opcache pdo pdo_mysql

WORKDIR /app/src

ENV APP_ENV prod
ENV APP_DEBUG false

ENTRYPOINT ["docker-entrypoint"]
CMD ["/usr/local/sbin/php-fpm"]

COPY --chown=php:php docker/php-fpm /
COPY --chown=php:php docker/expand-secrets.sh /bin/expand-secrets.sh
COPY --chown=php:php --from=builder /app/src/vendor /app/src/vendor
COPY --chown=php:php backend/ /app/src/

#
# INSTALL TEST DEPENDENCIES AND TEST
#
# Including a redis server to make sure the functional tests run
#
FROM runtime AS test

ENV SYMFONY_PHPUNIT_VERSION 8.0.0

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add php7-pdo_sqlite php7-sqlite3 redis \
    && redis-server --daemonize yes --requirepass test \
    && composer install -d /app/src --optimize-autoloader --no-interaction --no-suggest --no-scripts \
    && openssl genrsa -out /app/src/config/packages/test/jwt_keys/private-test.pem -passout pass:test -aes256 4096 \
    && openssl rsa -passin pass:test -pubout -in /app/src/config/packages/test/jwt_keys/private-test.pem -out /app/src/config/packages/test/jwt_keys/public-test.pem \
    && /app/src/bin/phpunit --testsuite=unit,integration,functional

#
# SPEW OUT FINAL IMAGE
#
FROM runtime
