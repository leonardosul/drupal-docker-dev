# Nginx file server
FROM nginx

# Set up directory
RUN mkdir -p /var/www/web && \
    chown -R www-data:www-data /var/www/

# Directory to serve files from
WORKDIR /var/www/web

# Add a vhost
ADD ./conf/vhost.conf /etc/nginx/conf.d/default.conf