#!/bin/bash

# Create SQLite file at runtime with correct permissions
if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
  chmod 775 database/database.sqlite
fi

# Fix all Laravel write folders
chmod -R 775 storage bootstrap/cache

# Laravel setup commands
php artisan config:cache
php artisan migrate --force

# Start Apache
apache2-foreground
