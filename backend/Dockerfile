FROM php:8.4-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        intl

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html