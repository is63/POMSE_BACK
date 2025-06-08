#!/bin/bash

# Crea la carpeta de imágenes si no existe
mkdir -p /var/www/html/storage/app/public/imagenes

# Da permisos correctos a storage y bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Borra symlink si existe y créalo de nuevo
if [ -L /var/www/html/public/storage ]; then
    rm /var/www/html/public/storage
fi

php artisan storage:link || true

# Arranca Apache
exec "$@"