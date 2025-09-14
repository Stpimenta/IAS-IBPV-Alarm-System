# Dockerfile
FROM php:8.2-apache

COPY . /var/www/html/

# Allow Apache to read/write
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# DocumentRoot 
RUN sed -i 's#/var/www/html#/var/www/html/src#g' /etc/apache2/sites-available/000-default.conf

# Alias for assets
RUN echo 'Alias /assets /var/www/html/assets\n<Directory /var/www/html/assets>\nOptions Indexes FollowSymLinks\nAllowOverride None\nRequire all granted\n</Directory>' >> /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite
RUN a2enmod rewrite

# Suppress "could not determine server name" warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
