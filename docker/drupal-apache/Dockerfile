# An apache server
FROM httpd:2.4

# Add a vhost.
RUN rm /etc/apapche2/sites-available/vhosts.conf
ADD ./conf/vhosts.conf /etc/apapche2/sites-available/
