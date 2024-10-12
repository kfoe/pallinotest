#!/bin/bash

set -e

if [ ! -f /var/www/html/.env ]; then
  echo "Generating .env file..."
  cp /var/www/html/.env.example /var/www/html/.env
  echo ".env file generated successfully."
else
  echo ".env file already exists."
fi

echo "Starting PHP-FPM..."
php-fpm