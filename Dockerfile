# Use official PHP + Apache image
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy entire project into Apache root
COPY . /var/www/html/

# Set working directory to your project folder
WORKDIR /var/www/html/csoft_proj

# Tell Apache your REAL public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/csoft_proj/public

# Update Apache config with the new DocumentRoot
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf

# Fix <Directory> rules for the new DocumentRoot
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose port 80
EXPOSE 80
