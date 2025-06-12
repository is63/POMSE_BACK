FROM php:8.3.6-apache

# Habilitamos módulos necesarios de Apache y PHP
RUN a2enmod rewrite expires headers

# Instalamos extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    nano \
    # Limpiar caché de apt
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath gd exif

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos de la aplicación Laravel
# ¡Importante! Asegúrate de tener un archivo .dockerignore en la raíz de tu proyecto
# para excluir directorios como node_modules, .git, storage/logs/* (excepto .gitignore), etc.
COPY . /var/www/html/

# Instala las dependencias de Composer para producción
RUN composer install --no-interaction --no-dev --optimize-autoloader --prefer-dist

# Configura Apache para usar el .htaccess de Laravel
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Copia un script de entrada que manejará los permisos y el arranque de Apache
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Establece la propiedad y permisos correctos para los directorios de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Usar el script de entrada
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]