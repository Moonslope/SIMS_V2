#!/bin/bash

# Setup PostgreSQL SSL directory for www-data user (queue worker runs as www-data)
mkdir -p /var/www/.postgresql
chown -R www-data:www-data /var/www/.postgresql
chmod 700 /var/www/.postgresql

# Set HOME for www-data so PostgreSQL can find SSL config
export PGSSLMODE=require
export HOME=/var/www

# Fix log file permissions
mkdir -p /var/www/storage/logs
chown -R www-data:www-data /var/www/storage
chmod -R 775 /var/www/storage

# Run migrations
php artisan migrate --force

# Check if cache table exists, if not run migrations again
php artisan migrate --force

# Clear stuck queue jobs using custom artisan command
php artisan queue:clear-tables || echo "Queue clear failed, continuing anyway"

# Clear and cache configuration
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
