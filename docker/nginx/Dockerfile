FROM nginx:1.17.2-alpine

COPY --chown=root:root docker/nginx/ /
COPY src/public/assets /var/www/html/assets/

ENTRYPOINT /bin/docker-entrypoint
