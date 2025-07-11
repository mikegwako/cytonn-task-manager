FROM php:8.2-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    libpng-dev libonig-dev libxml2-dev \
    sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring xml zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy the Laravel app
COPY . /var/www/html

# Set correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R ug+rwX storage bootstrap/cache

# Apache virtual host
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Laravel dependencies and frontend assets
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install && npm run build

# Set environment, migrate, and cache config
RUN cp .env.example .env && \
    sed -i 's|APP_URL=http://localhost|APP_URL=https://cytonn-task-manager.onrender.com|g' .env && \
    touch database/database.sqlite && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan migrate --force
