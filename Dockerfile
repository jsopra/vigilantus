# Simple PHP environment for Vigilantus
FROM php:8.2-cli

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
    && docker-php-ext-install pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock* ./
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

COPY . .

EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "web"]
