#!/usr/bin/env bash
now=$(date +"%m_%d_%Y_%H_%M_%S")
drush @ddd -y sql-dump > "/var/www/backups/database-backup-$now@ddd.sql"