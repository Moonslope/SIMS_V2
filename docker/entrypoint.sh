#!/bin/bash

# Run migrations
php artisan migrate --force

# Clear stuck queue jobs (from previous deployments with different mailables)
php artisan tinker --execute="try { DB::table('jobs')->truncate(); DB::table('failed_jobs')->truncate(); echo 'Queue cleared'; } catch (Exception \$e) { echo 'Queue clear failed: ' . \$e->getMessage(); }" || true

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
