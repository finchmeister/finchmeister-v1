FROM php:7.2-apache

RUN docker-php-ext-install -j$(nproc) mysqli

COPY html/ /var/www/html/
COPY secure-php-scripts/ /var/www/secure-php-scripts/

RUN a2enmod rewrite
RUN service apache2 restart


