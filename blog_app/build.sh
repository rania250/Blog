#!/bin/bash
# Render build script for Symfony

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Clear and warm up cache for production
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug

# Install and build frontend assets if needed
if [ -f "package.json" ]; then
    npm install
    npm run build
fi