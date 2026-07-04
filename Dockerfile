# 1. Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# 2. Definimos de forma estricta la carpeta de trabajo del servidor
WORKDIR /var/www/html

# 3. Actualizamos paquetes e instalamos git y unzip de forma limpia
RUN apt-get update && apt-get install -q -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 4. Traemos Composer de su imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiamos ÚNICAMENTE los archivos de Composer primero para aprovechar la caché de Docker
COPY composer.json ./
# Si el archivo composer.lock existe en tu git, descomenta la siguiente línea:
# COPY composer.lock ./

# 6. Instalamos dependencias limpias e ignoramos requerimientos locales de plataforma
# 6. Instalamos dependencias limpias e ignoramos auditorías y requerimientos locales de plataforma
# 6. Instalamos dependencias limpias evitando el bloqueo por seguridad de paquetes antiguos
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress --ignore-platform-reqs --no-security-blocking

# 7. Copiamos el resto de los archivos del proyecto (respetando el .dockerignore)
COPY . .

# 8. REGLA DE ORO DE PRODUCCIÓN: Apuntar la raíz de Apache a la carpeta /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 9. Activamos el módulo rewrite de Apache para tu Router personalizado
RUN a2enmod rewrite

# 10. Exponemos el puerto estándar
EXPOSE 80