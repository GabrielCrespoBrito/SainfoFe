<?php

namespace App;

use App\Empresa;

class EnvHandler
{
  public static function setEmailDataManual($host, $puerto, $encriptacion, $email, $password_email, $driver = "smtp")
  {

    return;
    self::changeEnv([
      'MAIL_HOST'          => $host,
      'MAIL_PORT'          => $puerto,
      'MAIL_ENCRYPTION'   => $encriptacion,
      'MAIL_USERNAME'      => $email,
      'MAIL_PASSWORD'      => $password_email,
      'MAIL_DRIVER'        => $driver,
    ]);
  }

  public static function setEmailData()
  {
    $empresa = get_empresa();
    $data = $empresa->opcion->toArray();
    $data['Email'] = $empresa->EmpLin3;
    self::setEmailDataManual(
      $data['EmaServ'],
      $data['EmaPuer'],
      $data['zoncodi'],
      $data['Email'],
      $data['EmaClav']
    );
  }

  public static function quitar_barra($quitar = true)
  {
    self::changeEnv([
      'DEBUGBAR_ENABLED' => $quitar ? "FALSE" : 'TRUE',
    ]);
  }


  public static function toggle_debug()
  {
    self::changeEnv([
      'DEBUGBAR_ENABLED' => 'TRUE'
    ], true );
  }

  public static function getArrEnv()
  {
    $env = file_get_contents(base_path() . '/.env');
    return preg_split('/\s+/', $env);
  }

  public static function changeEnv($data = array() , $toggleDeBugBar = false)
  {
    if (count($data) > 0) {
      $env = self::getArrEnv();

      // Loop through given data
      foreach ((array)$data as $key => $value) {
        // Loop through .env-data
        foreach ( $env as $env_key => $env_value ) {
          // Turn the value into an array and stop after the first split
          // So it's not possible to split e.g. the App-Key by accident
          $entry = explode("=", $env_value, 2);
          // Check, if new key fits the actual .env-key
          if ($entry[0] == $key) {

            if( $toggleDeBugBar && $env_key == "DEBUGBAR_ENABLED" ){
              $value = $env_value == "FALSE" ? "TRUE" : "FALSE";
     
            }

            // If yes, overwrite it with the new one
            $env[$env_key] = $key . "=" . $value;

          } else {
            // If not, keep the old one
            $env[$env_key] = $env_value;
          }
        }
      }

      // Turn the array back to an String
      $env = implode("\n", $env);

      // And overwrite the .env with the new data
      file_put_contents(base_path() . '/.env', $env);

      return true;
    } else {
      return false;
    }
  }
}
