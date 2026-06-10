FROM php:8.2-fpm-alpine

# Instalar dependencias necesarias para PostgreSQL
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && echo "clear_env = no" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Configurar el directorio de trabajo
WORKDIR /var/www/html
