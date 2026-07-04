# 1. Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 2. Actualizamos paquetes e instalamos git y unzip (obligando a decir 'sí' con -y)
RUN apt-get update && apt-get install -q -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 3. Traemos Composer de su imagen oficial de forma limpia
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copiamos el archivo de dependencias primero
COPY composer.json /var/www/html/

# 5. Ejecutamos composer install ignorando scripts de desarrollo
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress --ignore-platform-reqs

# 6. Copiamos todo el código restante al servidor
COPY . /var/www/html/

# 7. Activamos reescritura de URLs para el Router
RUN a2enmod rewrite

# 8. Exponemos el puerto
EXPOSE 80