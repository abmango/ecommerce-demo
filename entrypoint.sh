#!/bin/bash

# Aplica permisos necesarios
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ejecuta Apache en foreground
exec apache2-foreground