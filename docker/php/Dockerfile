FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libicu-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    intl \
    zip \
    ctype \
    iconv

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Set working directory permissions
RUN chown -R www-data:www-data /var/www

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
