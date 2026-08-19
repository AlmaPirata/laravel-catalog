FROM composer:2 AS composer

FROM php:8.5-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git libonig-dev libsqlite3-dev unzip \
    && docker-php-ext-install mbstring pdo_mysql pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .
RUN composer dump-autoload --optimize

RUN chmod +x docker/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["docker/entrypoint.sh"]

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
