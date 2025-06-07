#!/bin/bash

# Este script se ejecuta cada vez que se inicia el contenedor.
# Asegura que los permisos de almacenamiento y caché sean correctos.

# Si 'storage' y 'bootstrap/cache' existen, asegura los permisos.
# Esto es importante porque el volumen montado puede tener permisos diferentes.
if [ -d /var/www/html/storage ]; then
    chown -R www-data:www-data /var/www/html/storage
    chmod -R 775 /var/www/html/storage
fi

if [ -d /var/www/html/bootstrap/cache ]; then
    chown -R www-data:www-data /var/www/html/bootstrap/cache
    chmod -R 775 /var/www/html/bootstrap/cache
fi

# Si no existen, créalos y asigna permisos (esto suele ser manejado por Laravel, pero es una precaución)
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/logs

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ejecuta el comando principal del contenedor (Apache en este caso)
exec "$@"