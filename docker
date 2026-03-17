# Use official PHP image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy all project files into container
COPY . /var/www/html/

# Enable Apache rewrite (optional, good for clean URLs)
RUN a2enmod rewrite

# Expose port 8080 (Railway uses PORT env)
EXPOSE 8080

# Make Apache listen to Railway's dynamic port
CMD ["bash", "-c", "sed -i 's/80/${PORT:-8080}/g' /etc/apache2/ports.conf && apache2-foreground"]
