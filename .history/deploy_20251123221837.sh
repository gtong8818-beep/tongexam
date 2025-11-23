#!/bin/bash

# Ensure all required directories exist with proper permissions
mkdir -p storage/app
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Clear any cached configs from previous deployments
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes.php
rm -f bootstrap/cache/views.php

# Run Laravel commands
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment setup complete"
