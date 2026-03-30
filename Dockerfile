FROM php:8.2-fpm

# ติดตั้ง packages + tools ที่จำเป็น (รวม curl + wget สำหรับ healthcheck)
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

# ตั้ง working directory ให้ตรงกับ nginx
WORKDIR /app

# copy project
COPY . .

# ติดตั้ง Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# สร้าง .env และ APP_KEY
RUN cp .env.example .env && php artisan key:generate

# ตั้ง permission
RUN chmod -R 775 storage bootstrap/cache

# copy nginx config (สำคัญมาก)
COPY nginx.conf /etc/nginx/conf.d/default.conf

# เปิด port
EXPOSE 80

# healthcheck (แก้ปัญหา Coolify rollback)
HEALTHCHECK CMD curl --fail http://localhost || exit 1

# start service
CMD service nginx start && php-fpm