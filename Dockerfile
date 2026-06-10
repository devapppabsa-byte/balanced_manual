FROM php:8.2-fpm

WORKDIR /var/www/html

# 1. dependencias del sistema
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# 2. extensiones PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    xml \
    curl

# 3. composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. copiar proyecto
COPY . .

# 5. instalar dependencias PHP
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# 6. permisos (IMPORTANTE en Laravel)
RUN chmod -R 777 storage bootstrap/cache

# 7. start
CMD php artisan serve --host=0.0.0.0 --port=$PORT