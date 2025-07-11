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

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R 775 storage bootstrap/cache database

# Apache virtual host
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Laravel setup
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN cp .env.example .env

# Recreate writable SQLite DB and set proper permissions
RUN touch database/database.sqlite && \
    chmod -R 775 database storage bootstrap/cache && \
    php artisan config:cache && \
    php artisan migrate --force
