FROM node:12.8.0-alpine AS build

WORKDIR /app/src

COPY src/ /app/src/

RUN yarn install --ignore-engines \
    && yarn build

FROM nginx:1.17.2-alpine

COPY docker/nginx/ /
COPY --from=build /app/src/public var/www/html

ENTRYPOINT /bin/docker-entrypoint
