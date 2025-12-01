#!/bin/bash

# Run migrations
php artisan migrate --force

# Clear queue tables using direct SQL commands
php artisan db:wipe --drop-types --force --quiet || true
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
