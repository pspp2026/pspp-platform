FROM php:8.2-fpm

# ติดตั้ง packages + wget (สำคัญสำหรับ healthcheck)

RUN apt-get update && apt-get install -y 
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
&& docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# ติดตั้ง Composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ตั้ง working directory

WORKDIR /var/www

# copy project

COPY . .

# ติดตั้ง Laravel dependencies

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# สร้าง .env และ key (แก้ error 500)

RUN cp .env.example .env
RUN php artisan key:generate
RUN php artisan config:cache

# ตั้ง permission (สำคัญมาก)

RUN chmod -R 755 storage bootstrap/cache

# copy nginx config

COPY nginx.conf /etc/nginx/nginx.conf

# เปิด port

EXPOSE 80

# start service

CMD service nginx start && php-fpm
