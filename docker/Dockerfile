# Usa la imagen de PHP 8.2 proporcionada por serversideup/php
FROM serversideup/php:8.1-fpm-apache

USER root

ARG APP_ENV=production

LABEL mantainer="Gerard"

ENV AUTORUN_ENABLED=false

RUN apt-get update \
    && curl -sL https://deb.nodesource.com/setup_21.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/* \
    && npm install -g n \
    && n stable

USER www-data