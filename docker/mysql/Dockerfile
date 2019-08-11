FROM mysql:5.7

COPY docker/expand-secrets.sh /bin/expand-secrets.sh

CMD ["/bin/sh", "-c", ". /bin/expand-secrets.sh && docker-entrpoint.sh"]
