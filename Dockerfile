FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --optimize-autoloader

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
