FROM php:7.3-fpm-alpine

RUN addgroup -g 1000 php \
    && adduser -u 1000 -s /bin/sh -S php \
    && mkdir -p /app \
    && chown -R php:php /app/ \
    && apk --update add \
        php7-mysqli \
        php7-pdo \
    && docker-php-ext-install mysqli

COPY --chown=php:php src/* /app/
COPY docker/php-fpm /

ENTRYPOINT "docker-entrypoint"
