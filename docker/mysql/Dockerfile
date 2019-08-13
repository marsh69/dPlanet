FROM mysql:8.0

COPY docker/expand-secrets.sh /bin/expand-secrets.sh

CMD ["/bin/sh", "-c", ". /bin/expand-secrets.sh && docker-entrypoint.sh mysqld"]
