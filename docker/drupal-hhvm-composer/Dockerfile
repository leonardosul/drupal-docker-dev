# PHP FPM Container
FROM hhvm/hhvm:3.15.3

# Install dependencies
RUN apt-get update && \
    apt-get install curl -y

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Directory to serve files from
WORKDIR /var/www