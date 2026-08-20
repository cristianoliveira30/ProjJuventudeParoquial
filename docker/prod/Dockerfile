# Production Dockerfile for Laravel + Vite
# Builds JS assets, installs PHP dependencies, and creates a runtime image

# --- Build JS assets (Vite) ---
FROM node:20-alpine AS node-build
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --verbose

# Use example env during CI builds; real .env.production is mounted at runtime on the server
# This avoids baking production secrets into the image while keeping Vite build working.
COPY .env.production.example .env
COPY resources resources
COPY public public
COPY vite.config.js .
RUN npm run build

# --- Install PHP dependencies (Composer) ---
FROM composer:2 AS composer
WORKDIR /app

# Copy composer metadata first to take advantage of layer caching
COPY composer.json composer.lock ./

# Required for Composer "files" autoload entries
COPY app/Helpers/custom_helpers.php app/Helpers/custom_helpers.php

# Required for post-autoload scripts (artisan/package discovery)
COPY artisan ./artisan
COPY bootstrap ./bootstrap
COPY config ./config
COPY app ./app
COPY routes ./routes
COPY database ./database
COPY resources ./resources
COPY tests ./tests
COPY phpunit.xml ./phpunit.xml

# Ensure Laravel cache/storage paths exist and are writable during composer scripts
RUN mkdir -p bootstrap/cache \
  && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
  && chmod -R 0777 bootstrap/cache storage

# ele verific se é teste do actions ou se é produção real
ARG INSTALL_DEV=false
RUN if [ "$INSTALL_DEV" = "false" ]; then \
      composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist --no-progress; \
    else \
      composer install --no-interaction --optimize-autoloader --prefer-dist --no-progress; \
    fi

# --- Runtime image ---
FROM php:8.3-fpm-alpine AS runtime

# Install build dependencies first
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    dpkg-dev \
    dpkg \
    file \
    g++ \
    gcc \
    libc-dev \
    make \
    pkgconfig

# Install runtime dependencies and build tools
RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    gnupg \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    sqlite-dev \
    nginx \
  && docker-php-ext-configure intl \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) intl gd pdo_mysql pdo_sqlite zip bcmath opcache \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apk del --no-cache .build-deps

WORKDIR /var/www/html

# Copy application source
COPY . .

# Copy PHP dependencies from composer stage
COPY --from=composer /app/vendor ./vendor

# Copy built assets from node build stage
COPY --from=node-build /app/public/build ./public/build

# Nginx config
COPY docker/prod/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/prod/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
COPY docker/prod/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Ensure runtime permissions and required Laravel cache directories
RUN mkdir -p /var/www/html/storage/framework/cache/data \
  /var/www/html/storage/framework/sessions \
  /var/www/html/storage/framework/views \
  /var/www/html/storage/logs \
  /var/www/html/bootstrap/cache \
  && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm", "-F"]
