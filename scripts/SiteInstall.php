<?php

namespace DrupalDockerDev\Scripts;

use PDO;
use PDOException;
use DrupalDockerDev\Scripts\Input;

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
  public static function install() {

    $databaseCredentials = [];
    $databaseFile =  file_get_contents('/var/www/docker/drupal-mysql/conf/mysql-variables.env');
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

    // Check to see if there is anything in the users table.
    $userQuery = 'SELECT * FROM users';
    $request = $databaseConnection->prepare($userQuery);
    $request->execute();
    $rowCount = $request->rowCount();

    if ($rowCount >= 1) {
      echo "Existing Database found.\r\n"
        . "Options:\r\n"
        . "[0] Abort Site Install\r\n"
        . "[1] Install New Site Anyway\r\n"
        . "[2] Backup Current DB\r\n"
        . "[3] Backup Current DB and then Install New Site\r\n"
        . "[4] Revert to Previous Backup\r\n"
        . "[5] Backup Current DB and then Revert to Previous Backup\r\n";

      /**
       * Input object.
       * @var Input $read
       */
      $read = new Input();

      // Ask the user what they would like to do.
      $choice = $read->readStdin("Please make your choice: \r\n", array('', '0', '1', '2', '3', '4', '5'));

      switch ($choice) {
        case 0:
          echo "Keeping previously installed site.\r\n";
          break;

        case 1:
          echo "Installing new Drupal site.\r\n";
          $output = shell_exec('/var/www/scripts/drupal-database-install.sh 2>&1');
          echo $output;
          break;

        case 2:
          echo "Backing up Drupal Site.\r\n";
          $output = shell_exec('/var/www/scripts/drupal-database-backup.sh 2>&1');
          echo $output;
          echo "Site Backed Up.\r\n";
          break;

        case 3:
          echo "Backing up Drupal Site.\r\n";
          $output = shell_exec('/var/www/scripts/drupal-database-backup.sh 2>&1');
          echo $output;
          echo "Old Site Backed Up.\r\n";
          echo "Installing new Drupal site.\r\n";
          $output = shell_exec('/var/www/scripts/drupal-database-install.sh 2>&1');
          echo $output;
          break;

        case 4:
          // Get list of all backups.
          $backup_files = array_filter(scandir('/var/www/backups'), function($item) {
            return !is_dir('../backups/' . $item);
          });

          // Set up arrays for choice selection.
          $backups = [];
          $backups_numbered = [];
          $count = 0;
          foreach ($backup_files as $backup_file) {
            $backups[$count] = $backup_file;
            $backups_numbered[] = $count . ' - ' . $backup_file;
            $count++;
          }

          // Output some instructions.
          $backup_list = implode(" \r\n", $backups_numbered);
          $backup_options = implode(" \r\n", array_keys($backups));
          echo "Backups List: \r\n";
          echo $backup_list . " \r\n";
          $database_choice = $read->readStdin("Please choose a DB to revert to: \r\n", $backup_options);

          // If the user selects a valid choice, revert to selected database.
          if ($database_choice != NULL) {
            echo "Reverting to Previous Backup.\r\n";
            $output = shell_exec('/var/www/scripts/drupal-database-revert.sh ' . $backups[$database_choice] . ' 2>&1');
            echo $output;
            echo "Database Revert Complete.\n";
          }

          break;
        default:
      }
    }
    else {
      echo "No database detected, lets try a fresh site install.\r\n";
      $output = shell_exec('/var/www/scripts/drupal-database-install.sh 2>&1');
      echo $output;
    }

  }

}
