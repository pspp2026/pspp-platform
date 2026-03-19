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

#ติดตั้ง Composer

COPY --from=composer /usr/bin/composer /usr/bin/composer

#ตั้ง working directory

WORKDIR /var/www

#copy project ทั้งหมด

COPY . .

#ติดตั้ง Laravel dependencies (production mode)

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

#สร้างไฟล์ .env และ APP_KEY

RUN cp .env.example .env
RUN php artisan key:generate

#สร้าง SQLite database (แก้ error 500)

RUN mkdir -p database
RUN touch database/database.sqlite
RUN chmod -R 777 database

#ตั้ง permission ให้ Laravel ทำงานได้

RUN chmod -R 775 storage bootstrap/cache

#copy nginx config

COPY nginx.conf /etc/nginx/nginx.conf

#เปิด port 80

EXPOSE 80

#start nginx + php-fpm

CMD service nginx start && php-fpm