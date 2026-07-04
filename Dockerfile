# 1. Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 2. Definimos de forma estricta la carpeta de trabajo
WORKDIR /var/www/html

# 3. Actualizamos paquetes e instalamos git y unzip
RUN apt-get update && apt-get install -q -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 4. Traemos Composer de su imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiamos el archivo de dependencias a la carpeta actual (.)
COPY composer.json ./

# 6. Ejecutamos composer install (ahora sí parado en la carpeta correcta)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress --ignore-platform-reqs

# 7. Copiamos todo el resto del código
COPY . .

# 8. Activamos reescritura de URLs para el Router
RUN a2enmod rewrite

# 9. Exponemos el puerto
EXPOSE 80