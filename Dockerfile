FROM php:8.2-fpm

# ติดตั้ง extension
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    wget \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# ติดตั้ง composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# copy project
COPY . .

# install laravel
RUN composer install

# ตั้งค่า permission
RUN chmod -R 755 /var/www

# nginx config
COPY ./nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

CMD service nginx start && php-fpm