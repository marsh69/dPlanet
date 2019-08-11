FROM node:12.8.0-alpine AS build

WORKDIR /build

COPY src/ /build/

RUN yarn install --ignore-engines \
    && yarn build

FROM nginx:1.17.2-alpine

COPY docker/nginx/ /
COPY --from=build /build/public/assets/ var/www/html/assets/

ENTRYPOINT /bin/docker-entrypoint
