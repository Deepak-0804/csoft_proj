FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy project
COPY . /var/www/html

# Set document root to /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Apply document root override
RUN sed -ri "s#DocumentRoot /var/www/html#DocumentRoot ${APACHE_DOCUMENT_ROOT}#g" /etc/apache2/sites-available/000-default.conf

# Directory directive fix
RUN sed -ri "s#/var/www/#${APACHE_DOCUMENT_ROOT}/#g" /etc/apache2/apache2.conf

# Update directory permissions
RUN echo "<Directory ${APACHE_DOCUMENT_ROOT}>\n\
    AllowOverride All\n\
</Directory>" >> /etc/apache2/apache2.conf
