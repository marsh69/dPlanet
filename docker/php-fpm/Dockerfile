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

# Runtime
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
       php7-mysqli \
       php7-gd \
       autoconf \
    && pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && docker-php-ext-install opcache pdo mysqli pdo_mysql

WORKDIR /app/src

ENV APP_ENV prod
ENV APP_DEBUG false

ENTRYPOINT "docker-entrypoint"

COPY --chown=php:php docker/php-fpm /
COPY --chown=php:php docker/expand-secrets.sh /bin/expand-secrets.sh
COPY --chown=php:php --from=builder /app/src/vendor /app/src/vendor
COPY --chown=php:php backend/ /app/src/

# Test with composer
FROM runtime AS test
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apk add php7-pdo_sqlite php7-sqlite3 \
    && openssl genrsa -out /app/src/config/packages/test/jwt_keys/private-test.pem -passout pass:test -aes256 4096 \
    && openssl rsa -passin pass:test -pubout -in /app/src/config/packages/test/jwt_keys/private-test.pem -out /app/src/config/packages/test/jwt_keys/public-test.pem \
    && composer install -d /app/src --optimize-autoloader --no-interaction --no-suggest --no-scripts \
    && /app/src/bin/phpunit --testsuite=unit,integration,functional

# Final image
FROM runtime
