<?php

//namespace DrupalDockerDev\Scripts;

use Drupal\Core\Database\Connection;

/**
 * Installs a drupal site if one is not already installed.
 */
class SiteInstall {

    /**
     * Checks for installed site, and then performs install if required.
     */
    public function install() {

        $databaseCredentials = [];
        $databaseFile =  file_get_contents('../docker/drupal-mysql/mysql-variables.env');
        $credentialRows = explode(PHP_EOL, $databaseFile);

        foreach ($credentialRows as $credentialRow) {
            $credentialParts = explode('=', $credentialRow);
            $databaseCredentials[$credentialParts[0]] = $credentialParts[1];
        }

        // Create PDO connection.
        $dsn = 'mysql:' . 'dbname=' . $databaseCredentials['MYSQL_DATABASE'] . ';host=drupal-mysql';
        $user = $databaseCredentials['MYSQL_USER'];
        $password = $databaseCredentials['MYSQL_PASSWORD'];

        try {
            $dbh = new PDO($dsn, $user, $password);
            echo 'truth';
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

}

call_user_func_array(array(SiteInstall, install), []);