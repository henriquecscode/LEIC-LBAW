#!/bin/bash
set -e

cd /var/www; php artisan config:cache; rm -f public/storage/; php artisan storage:link
env >> /var/www/.env
php-fpm8.0 -D
nginx -g "daemon off;"
