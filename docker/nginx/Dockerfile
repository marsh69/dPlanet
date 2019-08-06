FROM nginx:1.17.2-alpine

COPY --chown=root:root docker/nginx/ /
COPY assets/ /var/www/html/

ENTRYPOINT /bin/docker-entrypoint
