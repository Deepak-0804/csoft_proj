# Use official PHP + Apache image
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install required PHP extensions (add more if your app needs them)
RUN docker-php-ext-install pdo pdo_mysql

# Copy project files into Apache web root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 (Render maps it automatically)
EXPOSE 80

# Set the document root to /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update Apache config to change document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf

RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
