FROM php:8.2-cli

# 1. Install system dependencies + Node.js (needed for Vite)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    gnupg

# Install Node.js (Version 18 or 20 is recommended for Laravel 12)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# 2. Install PHP extensions
RUN docker-php-ext-install gd pdo pdo_mysql

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# 4. Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# 5. Build Vite Assets
# This step fixes the "Vite manifest not found" error
RUN npm install && npm run build

EXPOSE 8000

# 6. Startup Command
# Added --seed to automatically run your AdminSeeder on deployment
CMD php artisan config:clear && \
    php artisan migrate --force --seed && \
    php -S 0.0.0.0:$PORT -t public

    