# PHP FPM Container
FROM hhvm/hhvm:3.15.3

# Install dependencies
RUN apt-get update && \
    apt-get install curl git-core -y

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add in startup scripts
ADD ./scripts/* /scripts/

# Run HHVM
RUN chmod 755 /scripts/*.sh

# Where to run composer from
WORKDIR /var/www

# Default command
CMD ["/scripts/startup.sh"]