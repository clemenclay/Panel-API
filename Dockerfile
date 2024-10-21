FROM php:8.1-fpm

# Instalar dependencias y extensiones necesarias
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql

# Copiar el archivo de configuración de PHP si es necesario
# COPY ./php.ini /usr/local/etc/php/

# Otros comandos que necesites...
