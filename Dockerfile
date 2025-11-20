FROM php:8.2-apache

# --------------------------------------------------
# 1. Enable Apache mods
# --------------------------------------------------
RUN a2enmod rewrite

# --------------------------------------------------
# 2. Install system dependencies
# --------------------------------------------------
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# --------------------------------------------------
# 3. Install Composer
# --------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --------------------------------------------------
# 4. Install PHP extensions
# --------------------------------------------------
RUN docker-php-ext-install pdo pdo_mysql

# --------------------------------------------------
# 5. Copy project files
# --------------------------------------------------
WORKDIR /var/www/html
COPY . .

# --------------------------------------------------
# 6. Install PHP dependencies (creates vendor/)
# --------------------------------------------------
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# --------------------------------------------------
# 7. Set document root to /public
# --------------------------------------------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri "s#DocumentRoot /var/www/html#DocumentRoot ${APACHE_DOCUMENT_ROOT}#g" /etc/apache2/sites-available/000-default.conf

RUN sed -ri "s#/var/www/#${APACHE_DOCUMENT_ROOT}/#g" /etc/apache2/apache2.conf

RUN echo "<Directory ${APACHE_DOCUMENT_ROOT}>\n\
    AllowOverride All\n\
</Directory>" >> /etc/apache2/apache2.conf
