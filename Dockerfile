FROM php:8.2-fpm

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

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN cp .env.example .env && php artisan key:generate

RUN chmod -R 775 storage bootstrap/cache

COPY nginx.conf /etc/nginx/conf.d/default.conf

RUN echo "OK" > /app/public/health

EXPOSE 80

HEALTHCHECK CMD curl --fail http://localhost/health || exit 1

CMD service nginx start && php-fpm