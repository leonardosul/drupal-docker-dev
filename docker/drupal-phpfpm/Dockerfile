# PHP FPM Container
FROM php:7.0-fpm

# Install PHP extensions and dependencies
RUN apt-get update && \
    apt-get install -y zlib1g-dev libfreetype6-dev libjpeg62-turbo-dev libpng12-dev && \
    docker-php-ext-install zip && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/ && \
    docker-php-ext-install -j$(nproc) gd && \
    apt-get install git-core wget -y && \
    mkdir -p /var/www/web

# Install Composer
RUN echo 'deb http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list && \
    echo 'deb-src http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list && \
    wget https://www.dotdeb.org/dotdeb.gpg && \
    apt-key add dotdeb.gpg && \
    rm dotdeb.gpg && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug
RUN pecl install xdebug -y && \
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.remote_autostart=true" >> /usr/local/etc/php/conf.d/xdebug.ini

# Add drush, git and scripts to path
ENV PATH $PATH:/var/www/vendor/drush/drush:/var/www/scripts:/usr/lib/git-core

# Add drush alias for our site
ADD ./conf/ddd.alias.drushrc.php /root/.drush/

# Directory to serve files from
WORKDIR /var/www