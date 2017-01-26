# Node Container
FROM node:7.4.0

# Install ddd-cli
RUN npm install -g ddd-cli

# Install docker-compose and dependencies
RUN apt-get update && \
    apt-get install python-dev python-pip -y && \
    pip install docker-compose

# Change work directory
WORKDIR /usr/local/bin