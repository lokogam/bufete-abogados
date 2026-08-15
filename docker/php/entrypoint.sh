#!/bin/sh
set -e

# Entorno de desarrollo: garantiza permisos de escritura sobre los directorios
# de storage y cache aunque el proyecto esté montado desde el host (Windows/WSL).
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

exec "$@"