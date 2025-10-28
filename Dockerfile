# Use official PHP + Apache image
FROM php:8.1-apache

# Copy project files into the container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Install PHP extensions if needed (optional)
RUN docker-php-ext-install json

# Enable Apache mod_rewrite if using routing
RUN a2enmod rewrite

# Make Twig cache folder writable
RUN mkdir -p cache && chmod 777 cache

# Expose default HTTP port
EXPOSE 80

# Start Apache in foreground (Render uses container port 10000 by default)
CMD ["apache2-foreground"]
