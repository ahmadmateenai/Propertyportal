FROM php:8.2-apache

# Copy your public folder into Apache's web root
COPY public/ /var/www/html/

# Enable mysqli extension for MySQL
RUN docker-php-ext-install mysqli

EXPOSE 80
