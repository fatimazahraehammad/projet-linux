
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \

    git curl zip unzip \

    libpng-dev libonig-dev libxml2-dev libzip-dev \

    && docker-php-ext-install pdo pdo_mysql mbstring zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .


RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN a2enmod rewrite
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80


