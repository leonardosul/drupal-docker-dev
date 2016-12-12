# PHP FPM Container
FROM php:7.0-fpm

# Install dependencies
RUN apt-get update && \
    apt-get install -y zlib1g-dev libfreetype6-dev libjpeg62-turbo-dev libpng12-dev && \
    docker-php-ext-install zip && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/ && \
    docker-php-ext-install -j$(nproc) gd && \
    apt-get install git-core wget -y && \
    mkdir -p /var/www/web

# Install PHP extensions and composer
RUN echo 'deb http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list && \
    echo 'deb-src http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list && \
    wget https://www.dotdeb.org/dotdeb.gpg && \
    apt-key add dotdeb.gpg && \
    rm dotdeb.gpg && \
    apt-get update && \
    apt-get install php7.0-zip -y && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Directory to serve files from
WORKDIR /var/www