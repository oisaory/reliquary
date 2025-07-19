#!/bin/bash
set -e

# Run migrations
echo "Running database migrations..."
php /var/www/bin/console doctrine:migrations:migrate --no-interaction

# Then start Apache
echo "Starting Apache..."
exec apache2-foreground