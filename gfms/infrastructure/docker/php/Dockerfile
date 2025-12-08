# Multi-stage Dockerfile for Laravel 11 with PHP 8.3
# Stage 1: Base image with PHP extensions
FROM php:8.3-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    libxml2-dev \
    autoconf \
    g++ \
    make \
    openssl-dev \
    linux-headers

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        zip \
        gd \
        bcmath \
        intl \
        opcache \
        pcntl \
        soap \
        exif

# Install Redis extension
RUN pecl install redis-6.0.2 \
    && docker-php-ext-enable redis

# Install Swoole extension (for Laravel Octane - optional)
RUN pecl install swoole-5.1.1 \
    && docker-php-ext-enable swoole

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Stage 2: Development environment
FROM base AS development

# Install development tools
RUN apk add --no-cache \
    git \
    vim \
    bash

# Set permissions
RUN chown -R www-data:www-data /var/www/html

USER www-data

# Stage 3: Production environment
FROM base AS production

# Copy application files
COPY --chown=www-data:www-data . /var/www/html

# Install production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Optimize Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
