FROM php:8.2-fpm

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git curl zip unzip nginx \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && apt-get clean

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Dossier projet
WORKDIR /var/www/html

# Copier projet
COPY . .

# Installer dépendances Laravel
RUN composer install --no-interaction --optimize-autoloader

# Permissions Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Config Nginx
RUN printf '%s\n' \
'server {' \
'    listen 80;' \
'    root /var/www/html/public;' \
'    index index.php index.html;' \
'' \
'    location / {' \
'        try_files $uri $uri/ /index.php?$query_string;' \
'    }' \
'' \
'    location ~ \.php$ {' \
'        include fastcgi_params;' \
'        fastcgi_pass 127.0.0.1:9000;' \
'        fastcgi_index index.php;' \
'        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' \
'    }' \
'}' \
> /etc/nginx/sites-available/default

# Script de démarrage propre
RUN printf '%s\n' \
'#!/bin/sh' \
'php-fpm -D' \
'nginx -g "daemon off;"' \
> /start.sh && chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
