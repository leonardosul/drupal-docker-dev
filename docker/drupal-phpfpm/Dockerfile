# PHP FPM Container
FROM php:7.0-fpm

# Install dependencies
RUN apt-get update && \
    apt-get install git-core -y && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www