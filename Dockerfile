# Use official PHP image with Apache
FROM php:8.1-apache

# Copy project files into container
COPY . /var/www/html/

# Enable mod_rewrite for Apache (optional if using routing)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Make cache folder writable
RUN mkdir -p cache && chmod 777 cache
