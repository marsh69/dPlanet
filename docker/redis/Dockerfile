FROM redis:5.0.5-alpine

COPY docker/expand-secrets.sh /expand-secrets.sh

CMD ["/bin/sh", "-c", ". /expand-secrets.sh && redis-server --requirepass $REDIS_PASSWORD"]
