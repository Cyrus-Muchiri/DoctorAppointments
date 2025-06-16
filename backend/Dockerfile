# Use an official PHP image as the base image
FROM php:8.2-apache-bullseye

# Set the working directory inside the container
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql gd zip \
    && a2enmod rewrite

# Install Composer (PHP package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# USER www-data
COPY . .

# Configure Apache to serve the Laravel app from the public directory
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf


# # Set permissions for Laravel storage and bootstrap cache directories
RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/bootstrap/cache


# Install application dependencies using Composer
RUN composer install

# Expose port 9000 for the PHP-FPM server
EXPOSE 80

# Start the PHP-FPM server
CMD ["apache2-foreground"]