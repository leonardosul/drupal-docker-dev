#!/usr/bin/env bash
/usr/bin/env PHP_OPTIONS="-d sendmail_path=`which true`" ./vendor/drush/drush/drush -y --root=/var/www/web si --db-url=mysql://drupal-user:drupal-password@drupal-mysql/drupal-db