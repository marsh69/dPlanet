FROM alpine:3.9

RUN apk add --no-cache redis

COPY docker/expand-secrets.sh /bin/expand-secrets.sh
COPY docker/redis/ /

EXPOSE 6379

RUN chown -R redis:redis /var/redis

CMD ["/bin/sh", "-c", ". expand-secrets.sh && redis-server /var/redis/redis.conf --requirepass $REDIS_PASSWORD"]
