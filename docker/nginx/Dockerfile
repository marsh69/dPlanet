FROM node:12.8.0-alpine AS build

WORKDIR /build

COPY src/ /build/

RUN yarn install --ignore-engines \
    && yarn build

FROM nginx:1.17.2-alpine

COPY --chown=root:root docker/nginx/ /
COPY src/public/assets /var/www/html/assets/
COPY --from=build /build/public/assets/ var/www/html/assets/

ENTRYPOINT /bin/docker-entrypoint