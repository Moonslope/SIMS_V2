#!/bin/bash

# Run migrations
php artisan migrate --force

# Clear stuck queue jobs using custom artisan command
php artisan queue:clear-tables || echo "Queue clear failed, continuing anyway"

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
