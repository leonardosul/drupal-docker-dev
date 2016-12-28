<?php

namespace DrupalDockerDev\Scripts;

use PDO;
use PDOException;

/**
 * Installs a drupal site if one is not already installed.
 */
class SiteInstall {

  /**
   * Accepts input from the command line.
   */
  private function readStdin($prompt, $valid_inputs, $default = '') {
    while(!isset($input) || (is_array($valid_inputs) && !in_array($input, $valid_inputs)) || ($valid_inputs == 'is_file' && !is_file($input))) {
      echo $prompt;
      $input = strtolower(trim(fgets(STDIN)));
      if(empty($input) && !empty($default)) {
        $input = $default;
      }
    }
    return $input;
  }

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
    $request->execute();
    $rowCount = $request->rowCount();

    if ($rowCount >= 1) {
      echo "Existing Database found.\r\nOptions:\r\n[0] Abort Site Install\r\n[1] Install New Site Anyway\r\n";

      // Ask the user what they would like to do.
      $choice = $this->readStdin("Please make your choice: \r\n", array('', '0', '1'));

      if ($choice == 0) {
        echo "Keeping previously installed site.\r\n";
      }
      if ($choice == 1) {
        // If we get this far we should be able to install the db, lets call the shell script.
        echo "Installing new Drupal site.\r\n";
        $output = shell_exec('/var/www/scripts/drupal-database-install');
        echo $output;
      }
    }
    else {
      echo "No database detected, lets try a fresh site install.\r\n";
      $output = shell_exec('/var/www/scripts/drupal-database-install');
      echo $output;
    }

  }

}

$SiteInstaller = new SiteInstall();
$SiteInstaller->install();
