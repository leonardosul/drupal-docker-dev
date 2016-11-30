# An apache server
FROM httpd:2.4

# Install Apache Common
RUN apt-get update && \
    apt-get install apache2.2-common -y && \
    mkdir /var/www/web

# Add a vhost
ADD ./conf/drupal-docker.conf /etc/apache2/sites-available/

# Add host to Apache config, and restart Apache
RUN a2ensite drupal-docker.conf && \
    a2enmod proxy && \
    a2enmod proxy_http && \
    a2enmod proxy_connect && \
    service apache2 restart

# Directory to serve files from
WORKDIR /var/www/web