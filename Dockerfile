# Use the official PHP 8.1 image
FROM php:8.1

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the composer.json and composer.lock files to the working directory
COPY composer.json composer.lock ./

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies
RUN composer install --no-scripts

# Copy the rest of the project files to the working directory
COPY src/ ./src
COPY public/ ./public

# Expose port 80 for web traffic
EXPOSE 80

RUN docker-php-ext-install mysqli

# Start the PHP built-in web server
CMD ["php", "-S", "0.0.0.0:80", "-t", "public/"]