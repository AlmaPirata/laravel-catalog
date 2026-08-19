#!/bin/sh

set -e

until php -r '
    new PDO(
        "mysql:host=".getenv("DB_HOST").";port=".getenv("DB_PORT").";dbname=".getenv("DB_DATABASE"),
        getenv("DB_USERNAME"),
        getenv("DB_PASSWORD"),
    );
' 2>/dev/null; do
    sleep 2
done

php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction

exec "$@"
