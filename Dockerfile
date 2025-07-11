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

# Copy Laravel app
COPY . /var/www/html

# Apache virtual host
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# ✅ Install Node.js 18 and npm (BEFORE vite build)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# ✅ Laravel backend dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# ✅ Laravel frontend (Vite) assets
RUN npm install && npm run build

# ✅ Set up .env, permissions, and migrate database
RUN cp .env.example .env && \
    sed -i 's|APP_URL=http://localhost|APP_URL=https://cytonn-task-manager.onrender.com|g' .env && \
    touch database/database.sqlite && \
    chmod -R 775 database storage bootstrap/cache && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan migrate --force
