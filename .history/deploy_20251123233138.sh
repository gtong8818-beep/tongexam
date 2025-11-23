#!/bin/bash

# Ensure all required directories exist with proper permissions
mkdir -p storage/app/public
mkdir -p storage/app/public/tweets
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/testing
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create the storage symlink (critical for image access)
php artisan storage:link

# Clear any cached configs from previous deployments
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes.php
rm -f bootstrap/cache/views.php

# Run Laravel cache warming commands
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment setup complete"
