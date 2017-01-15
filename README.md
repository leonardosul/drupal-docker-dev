##Drupal Docker Dev

Drupal Docker Dev is a local development environment for creating Drupal websites on OSX.

* Provides multiple Docker containers to provide isolation and separate responsibilities. The complete Dockerfile for each container is provided, so that you can tinker. Docker Compose brings them all together and provides some exta config.
* Uses Composer to gather and install dependencies for the Drupal site. The composer.json provided should be edited to add the modules you require for your site.
* Additional Composer post install and post update commands are provided to automatically install the Drupal site for you. There is also a mechanism for DB backups, using the composer run-script command.
* [Drupal Scaffold](https://github.com/drupal-composer/drupal-scaffold) creates required Drupal files and directories. 

####Prerequisites

[Dinghy](https://github.com/codekitchen/dinghy) allows you to run docker containers on OSX with acceptable performance. It uses a VirtaulBox VM to host the Docker containers and NFS shares to speed up file transfer between the host system and the Docker containers.

I have had some issues with VirtualBox & Dinghy on El Capitan and Sierra. I have found version [5.0.20](http://download.virtualbox.org/virtualbox/5.0.20/VirtualBox-5.0.20-106931-OSX.dmg) of VirtualBox is stable for use with Dinghy.

[Docker Compose](https://docs.docker.com/compose/) Is used to wrangle the docker containers.

####Usage

Clone the repo locally and then run:
````bash
docker-compose up -d
````
to create the docker images and start the containers.

When all of the containers are up and running, you can install composer dependencies:
````bash
docker exec -it drupal-php composer install
````
The above command passes the "composer install" command into the drupal-php docker container. This will take a while and towards the end you will see Drupal Composer installing required files and directories.

To install a Drupal database run composer install again:
````bash
docker exec -it drupal-php composer install
````
This will kick off a composer post-install-cmd that will install a Drupal DB to the drupal-mysql container. We are using a [drush alias](docker/drupal-phpfpm/conf/ddd.alias.drushrc.php) for all of our drush commands.

We also have provided a basic CLI for backing up the Drupal database and reverting to a previous backup:
````bash
docker exec -it drupal-php composer run-script drupal-install
````
This commmand will provide you options for backing up, reverting and reinstalling Drupal.

####Docker Containers

* drupal-mysql is built on MySQL 5.7
* drupal-nginx uses a preconfigured vhost file
* drupal-php uses PHP 7 and php-fpm
