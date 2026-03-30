# ---------------------------
# Stage 1: Build (Composer)
# ---------------------------
FROM composer:2 AS vendor

WORKDIR /app

COPY . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist


# ---------------------------
# Stage 2: Production
# ---------------------------
FROM php:8.2-fpm-alpine

# Install dependencies
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

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Working dir
WORKDIR /app

# Copy app
COPY . /app

# Copy vendor จาก stage แรก
COPY --from=vendor /app/vendor /app/vendor

# Permissions (Laravel สำคัญมาก)
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Nginx config
COPY nginx.conf /etc/nginx/http.d/default.conf

# Optimize Laravel (optional แต่แนะนำ)
RUN php artisan config:clear || true \
    && php artisan cache:clear || true \
    && php artisan route:clear || true \
    && php artisan view:clear || true

# Expose port
EXPOSE 80

# Healthcheck (ผ่านแน่นอน)
HEALTHCHECK --interval=30s --timeout=10s --retries=3 \
  CMD curl --fail http://localhost/health || exit 1

# Start services
CMD php-fpm -D && nginx -g "daemon off;"