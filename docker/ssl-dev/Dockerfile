# Docker image to generate SSL certificates in your development environment
FROM frapsoft/openssl:latest

WORKDIR /ssl

RUN ["/bin/sh", "-c", "openssl req -x509 -nodes -newkey rsa:4096 -keyout /ssl/privkey.pem -out /ssl/fullchain.crt -days 9999 -subj '/C=NL/ST=Somewhere/L=City/O=Orgnaisation/OU=Development/CN=localhost/emailAddress=example@example.com' \
    && openssl x509 -in /ssl/fullchain.crt -out /ssl/fullchain.pem -outform PEM\
    && openssl dhparam -out /ssl/dhparam.pem 1024"]
