FROM php:8.2-cli

# Install system dependencies + Node.js
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    gnupg

# Install Node.js (for Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Install and build frontend assets
RUN npm install
RUN npm run build

# Clear Laravel cache
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear

EXPOSE 8000

# Run migrations + seed + start Laravel
# CMD php artisan migrate --force --seed && php -S 0.0.0.0:$PORT -t public
RUN chmod +x start.sh
CMD ["sh", "start.sh"]