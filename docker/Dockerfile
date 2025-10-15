FROM php:8.2-fpm

# System deps
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
 && rm -rf /var/lib/apt/lists/*

# Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Helpful for CI/containers running as root
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

WORKDIR /var/www/html

# 1) Copy only composer manifests to leverage Docker layer cache
COPY composer.json composer.lock* ./

# 2) Install PHP deps now (will be cached unless lock changes)
RUN composer install --no-interaction --prefer-dist --no-dev

# 3) Copy the rest of the app
COPY . .

# (Optional) If you need dev deps inside the image, run:
# RUN composer install --no-interaction --prefer-dist

# Permissions (optional; often not needed when bind-mounting)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
