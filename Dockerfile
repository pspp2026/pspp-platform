# ใช้ PHP + FPM
FROM php:8.2-fpm-alpine

# ติดตั้ง package ที่จำเป็น
RUN apk add --no-cache \
    nginx \
    curl \
    bash \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip

# ติดตั้ง PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# ตั้ง working directory
WORKDIR /app

# copy project เข้า container
COPY . /app

# ตั้ง permission (สำคัญมากสำหรับ Laravel)
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# copy nginx config
COPY nginx.conf /etc/nginx/http.d/default.conf

# เปิด port
EXPOSE 80

# 🔥 HEALTHCHECK ที่ “ผ่านแน่นอน”
HEALTHCHECK --interval=30s --timeout=10s --retries=3 \
  CMD curl --fail http://localhost/health || exit 1

# start services
CMD php-fpm -D && nginx -g "daemon off;"