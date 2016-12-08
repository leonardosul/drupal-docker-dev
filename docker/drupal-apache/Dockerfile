# An apache server
FROM httpd:2.2

# Set up directory
RUN mkdir -p /var/www/web && \
    chown -R www-data:www-data /var/www/

# Directory to serve files from
WORKDIR /var/www/web

# Install Apache Common
RUN apt-get update && \
    apt-get install apache2.2-common -y

# Add a vhost
ADD ./conf/drupal-docker.conf /etc/apache2/sites-available/

# Add host to Apache config, and restart Apache
RUN a2ensite drupal-docker.conf && \
    a2dissite 000-default && \
    a2enmod proxy && \
    a2enmod proxy_http && \
    a2enmod proxy_connect && \
    service apache2 restart