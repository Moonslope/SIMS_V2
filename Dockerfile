FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
   git \
   curl \
   libpng-dev \
   libonig-dev \
   libxml2-dev \
   libpq-dev \
   zip \
   unzip \
   nginx \
   supervisor \
   ca-certificates \
   gnupg

# Install Node.js 20.x
RUN mkdir -p /etc/apt/keyrings && \
   curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && \
   echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list && \
   apt-get update && \
   apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . /var/www

# Create required directories and set permissions
RUN mkdir -p /var/www/storage/framework/cache \
   /var/www/storage/framework/sessions \
   /var/www/storage/framework/views \
   /var/www/storage/logs \
   /var/www/bootstrap/cache

# Copy existing application directory permissions
RUN chown -R www-data:www-data /var/www

# Set permissions before composer install
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and build Vite assets
RUN npm ci --include=dev
RUN NODE_ENV=production npm run build

# Verify build output
RUN ls -la /var/www/public/build || echo "Build directory not found"

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
