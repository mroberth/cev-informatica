# 1. Usamos la imagen oficial de PHP con Apache incorporado
FROM php:8.2-apache

# 2. Copiamos todo el código de nuestro repositorio dentro del servidor Apache
COPY . /var/www/html/

# 3. Activamos el módulo rewrite de Apache (crucial para tu Router personalizado y el .htaccess)
RUN a2enmod rewrite

# 4. Exponemos el puerto 80 que es donde escucha Apache por defecto
EXPOSE 80