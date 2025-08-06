FROM php:8.1-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libgearman-dev \
    && docker-php-ext-install pdo_pgsql \
    && pecl install redis gearman \
    && docker-php-ext-enable redis gearman \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Expose default Apache port
EXPOSE 80

CMD ["apache2-foreground"]
