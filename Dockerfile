# ==========================================================
# Stage 1 : Composer
# ==========================================================
FROM composer:2 AS vendor

WORKDIR /app

# Copy Laravel project ทั้งหมดก่อน
COPY . .

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# ==========================================================
# Stage 2 : Frontend (Vite)
# ==========================================================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run build

# ==========================================================
# Stage 3 : Production
# ==========================================================
FROM php:8.2-fpm-alpine

# ----------------------------------------------------------
# Install packages
# ----------------------------------------------------------
RUN apk add --no-cache \
    nginx \
    curl \
    bash \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

# ----------------------------------------------------------
# PHP Extensions
# ----------------------------------------------------------
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    bcmath \
    exif \
    pcntl \
    gd

WORKDIR /app

# ----------------------------------------------------------
# Copy Application
# ----------------------------------------------------------
COPY . .

# ----------------------------------------------------------
# Copy Composer Vendor
# ----------------------------------------------------------
COPY --from=vendor /app/vendor ./vendor

# ----------------------------------------------------------
# Copy Vite Build
# ----------------------------------------------------------
COPY --from=frontend /app/public/build ./public/build

# ----------------------------------------------------------
# Laravel Permissions
# ----------------------------------------------------------
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

RUN chown -R www-data:www-data /app

RUN chmod -R 775 storage bootstrap/cache

# ----------------------------------------------------------
# Nginx
# ----------------------------------------------------------
COPY nginx.conf /etc/nginx/http.d/default.conf

# ----------------------------------------------------------
# Clear Cache
# ----------------------------------------------------------
RUN php artisan optimize:clear || true

# ----------------------------------------------------------
# Port
# ----------------------------------------------------------
EXPOSE 80

# ----------------------------------------------------------
# Health Check
# ----------------------------------------------------------
HEALTHCHECK --interval=30s --timeout=5s --retries=3 \
CMD curl --fail http://localhost/health || exit 1

# ----------------------------------------------------------
# Start
# ----------------------------------------------------------
CMD php-fpm -D && nginx -g "daemon off;"