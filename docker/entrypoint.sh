#!/bin/bash

# Run migrations
php artisan migrate --force

# Clear queue tables only using raw SQL
PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -U $DB_USERNAME -d $DB_DATABASE -c "TRUNCATE TABLE jobs, failed_jobs;" || echo "Queue clear failed, continuing anyway"

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
