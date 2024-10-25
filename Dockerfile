# Dockerfile
FROM php:8.1-fpm

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia el archivo composer.json y composer.lock
COPY composer.json composer.lock ./

# Permitir que Composer se ejecute como superusuario
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalar las dependencias de Laravel sin ejecutar scripts de Composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copiar .env.example a .env si no existe
RUN cp .env.example .env || true

# Generar la clave de la aplicación de Laravel
# (Descomentar si es necesario)
# RUN php artisan key:generate

# Copia el resto de los archivos del proyecto
COPY . .



# Instalar dependencias de Node.js
RUN npm install


# Compila los assets de Laravel
RUN npm run build

# Establece los permisos correctos para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Ejecuta las optimizaciones de Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Exponer el puerto 9000 para PHP-FPM
EXPOSE 9000

# Comando por defecto para iniciar PHP-FPM
CMD ["php-fpm"]
