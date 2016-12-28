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
        $databaseFile =  file_get_contents('/var/www/docker/drupal-mysql/mysql-variables.env');
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
        $userQuery = 'SELECT * FROM users';
        $request = $databaseConnection->prepare($userQuery);
        $result = $request->execute();
        $rowCount = $request->rowCount();

        if ($rowCount >= 4) {
            echo "Database found, cannot install.\r\n";
            return;
        }
        else {

            // If we get this far we should be able to install the db, lets call the shell script.
            $output = shell_exec('/var/www/scripts/drupal-install.sh');
            echo $output;
        }

    }

}

call_user_func_array(array(SiteInstall, install), []);