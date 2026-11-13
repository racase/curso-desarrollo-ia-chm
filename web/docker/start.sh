#!/bin/sh
# Prepares the application on first run and starts the development server.
set -e

if [ ! -f vendor/autoload.php ]; then
    echo "==> Installing Composer dependencies (first run, takes a few minutes)"
    composer install --no-interaction
fi

if [ ! -f .env ]; then
    echo "==> Creating .env"
    cp .env.example .env
fi

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

echo "==> Running migrations and seeders"
php artisan migrate --seed --force

echo "==> InfraTicket ready at http://localhost:8000/admin"
# --no-reload keeps the container environment (DB_HOST=db). Without it, artisan serve
# drops those variables and reads only .env, which points to 127.0.0.1.
exec php artisan serve --host=0.0.0.0 --port=8000 --no-reload
