FROM redis:5.0.5-alpine

COPY docker/expand-secrets.sh /bin/expand-secrets.sh
COPY docker/redis/ /

CMD ["/bin/sh", "-c", ". expand-secrets.sh && redis-server /var/redis/redis.conf --requirepass $REDIS_PASSWORD"]
