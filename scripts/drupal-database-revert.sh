#!/usr/bin/env bash
drush @ddd -y sql-cli < "/var/www/backups/$1"