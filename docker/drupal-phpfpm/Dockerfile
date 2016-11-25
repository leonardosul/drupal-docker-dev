# PHP FPM Container
FROM php:7.0-fpm

# Install dependencies
RUN apt-get update && \
    apt-get install -y zlib1g-dev && \
    docker-php-ext-install zip && \
    apt-get install git-core wget -y

# Install PHP extensions and composer
RUN echo 'deb http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list && \
    echo 'deb-src http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list && \
    wget https://www.dotdeb.org/dotdeb.gpg && \
    apt-key add dotdeb.gpg && \
    rm dotdeb.gpg && \
    apt-get update && \
    apt-get install php7.0-zip -y && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer




WORKDIR /var/www