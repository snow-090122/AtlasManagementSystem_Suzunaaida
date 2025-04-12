FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
  git unzip curl libzip-dev libonig-dev libpq-dev \
  && docker-php-ext-install pdo pdo_pgsql zip mbstring

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html \
  && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
