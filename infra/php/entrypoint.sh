#!/bin/sh

# Wait for database to be ready
until nc -z postgres 5432; do
  echo "Waiting for PostgreSQL..."
  sleep 2
done

echo "PostgreSQL is ready!"

# Wait for Redis to be ready
until nc -z redis 6379; do
  echo "Waiting for Redis..."
  sleep 2
done

echo "Redis is ready!"

# Run Laravel setup if vendor directory doesn't exist
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
fi

# Generate application key if not set
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Generate JWT secret if using JWT
if [ -n "$(php artisan | grep jwt:secret)" ]; then
    php artisan jwt:secret --force
fi

# Install Passport keys
if [ -n "$(php artisan | grep passport:install)" ]; then
    php artisan passport:install --force
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
exec "$@"
