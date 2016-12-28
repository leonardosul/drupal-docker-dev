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
        $databaseHost = 'mysql:' . 'dbname=' . $databaseCredentials['MYSQL_DATABASE'] . ';host=drupal-mysql';
        $databaseUser = $databaseCredentials['MYSQL_USER'];
        $databasePassword = $databaseCredentials['MYSQL_PASSWORD'];
        $databaseConnection = NULL;

        try {
            $databaseConnection = new PDO($databaseHost, $databaseUser, $databasePassword);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            return;
        }

        // Check to see if there is anything in the node table.
        $result = $databaseConnection->query('SELECT * FROM `node` LIMIT 1');

        if (!empty($result)) {
            echo "Database found, cannot install.\r\n";
            return;
        }
        else {
            echo $result;
            echo $databaseConnection->errorInfo();

            foreach($databaseConnection->errorInfo() as $err) {
                echo $err;
            }
            // If we get this far we should be able to install teh db, lets call the shell script.
            $output = shell_exec('./drupal-install.sh');
            echo $output;
        }

    }

}

call_user_func_array(array(SiteInstall, install), []);