FROM php:8.2-fpm

# ติดตั้ง packages + tools
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

# ตั้ง working directory
WORKDIR /app

# copy project
COPY . .

# ติดตั้ง Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# setup env + key
RUN cp .env.example .env && php artisan key:generate

# permission
RUN chmod -R 775 storage bootstrap/cache

# copy nginx config (สำคัญมาก)
COPY nginx.conf /etc/nginx/conf.d/default.conf

# 🔥 สร้าง health endpoint (แก้ปัญหา Coolify)
RUN echo "OK" > /app/public/health

# เปิด port
EXPOSE 80

# 🔥 healthcheck (สำคัญมาก)
HEALTHCHECK CMD curl --fail http://localhost/health || exit 1

# start services
CMD service nginx start && php-fpm