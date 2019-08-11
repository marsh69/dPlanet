FROM node:12.8.0-alpine AS build

WORKDIR /app

COPY src/ /app/

RUN yarn install --ignore-engines \
    && yarn build

FROM nginx:1.17.2-alpine

COPY docker/nginx/ /
COPY --from=build /app/public var/www/html

ENTRYPOINT /bin/docker-entrypoint
