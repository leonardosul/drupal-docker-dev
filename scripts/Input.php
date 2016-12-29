<?php

namespace DrupalDockerDev\Scripts;

/**
 * Installs a drupal site if one is not already installed.
 */
class Input {

  /**
   * Accepts input from the command line.
   */
  public function readStdin($prompt, $valid_inputs, $default = '') {
    while(!isset($input) || (is_array($valid_inputs) && !in_array($input, $valid_inputs)) || ($valid_inputs == 'is_file' && !is_file($input))) {
      echo $prompt;
      $input = strtolower(trim(fgets(STDIN)));
      if(empty($input) && !empty($default)) {
        $input = $default;
      }
    }
    return $input;
  }

}
