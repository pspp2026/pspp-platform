FROM php:8.2-fpm

# ติดตั้ง packages
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

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ตั้ง working dir ให้ตรง nginx
WORKDIR /app

# copy project
COPY . .

# install laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# setup env + key
RUN cp .env.example .env && php artisan key:generate

# permission
RUN chmod -R 775 storage bootstrap/cache

# 🔥 จุดสำคัญ (แก้ตรงนี้)
COPY nginx.conf /etc/nginx/conf.d/default.conf

# expose
EXPOSE 80

# start
CMD service nginx start && php-fpm