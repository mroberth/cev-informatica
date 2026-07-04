# 1. Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 2. Instalamos las herramientas necesarias del sistema y extensiones zip para Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 3. Descargamos e instalamos Composer de forma oficial dentro del contenedor
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copiamos los archivos de dependencias primero para optimizar la caché de Docker
COPY composer.json /var/www/html/
# Si tienes un composer.lock, descomenta la siguiente línea quitando el '#'
# COPY composer.lock /var/www/html/

# 5. Instalamos las dependencias de Composer sin entornos de desarrollo y optimizando el autoloader
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress

# 6. Copiamos el resto del código de nuestro proyecto
COPY . /var/www/html/

# 7. Activamos el módulo de reescritura de URLs para tu Router
RUN a2enmod rewrite

# 8. Exponemos el puerto 80
EXPOSE 80