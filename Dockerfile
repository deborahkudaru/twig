# Use official PHP image with composer
FROM php:8.2-cli

# Set working dir
WORKDIR /app

# Install system deps (pdo mysql etc)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    default-mysql-client \
  && docker-php-ext-install pdo_mysql zip

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project
COPY . /app

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Ensure logs/ tmp dirs exist
RUN mkdir -p /app/storage && chown -R www-data:www-data /app/storage || true

# Expose port (Railway sets $PORT anyway)
ENV PORT 8080
EXPOSE 8080

# Run migrations (safe: run then start server)
CMD php scripts/migrate.php || true && php -S 0.0.0.0:${PORT:-8080} -t public
