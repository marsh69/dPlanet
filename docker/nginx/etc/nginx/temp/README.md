# Nginx config files

These config files do not comply with 'normal' nginx config files since
they contain expressions like ${server_name} that represent variables.

These variables are replaced by environment variables during runtime.
These environment variables are defined in docker-compose.dev.yml and
docker-compose.prod.yml.

This means that *_if a variable is not defined it will be replaced by an empty string_*. So be
careful during debugging.
