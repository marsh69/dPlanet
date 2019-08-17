FROM node:12.8.1-alpine AS build

COPY frontend/ /app/

WORKDIR /app

RUN yarn install \
    && yarn build

FROM nginx:1.17.2-alpine

COPY docker/nginx/ /
COPY --from=build /app/build/* var/www/html

ENTRYPOINT /bin/docker-entrypoint
