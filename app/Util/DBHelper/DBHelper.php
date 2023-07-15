<?php
namespace App\Util\DBHelper;

class DBHelper 
{
  use SuscripcionesSystemTable,
  PermissionsHelp;
  
  public $messages = [];

  /**
   * Obtener listado de mensajes
   *
   * @return array
   */
  public function getMessages()
  {
    return $this->messages;
  }

  /**
   * Agregar un texto al listado de mensajes
   *
   * @param string $message
   * @return void
   */
  public function addMessage( $message )
  {
    $this->messages[] = $message;
  }

  /**
   * Agregar mensaje de creacion de tabla
   *
   * @param string $table
   * @return void
   */
  public function addMessageCreationTable( $table )
  {
    $message = "Se ha creado La tabla {$table}";      
    $this->addMessage($message);
  }


  /**
   * Agregar mensaje de eliminación de tabla
   *
   * @param string $table
   * @return void
   */
  public function addMessageDeleteTable( $table )
  {
    $message = "Se ha eliminado La tabla {$table}";      
    $this->addMessage($message);
  }

  /**
   * Agregar mensaje de creacion de tabla
   *
   * @param string $table
   * @param string $column
   * @return void
   */
  public function addMessageCreationColumn( $column, $tables )
  {
    // $message = "Se ha agregado la columna {$column} de la tabla {$table}";
    // $message = "Se ha agregado la columna {$column} de la tabla {$table}";

    $tables = (array) $tables;
    $plural_singular = count($tables) > 1 ? 's' : '';
    $message = sprintf("Se han agregado la% columna%s %s a la tabla %", $plural_singular, $plural_singular, $column, $tables) ;

    $this->addMessage($message);
  }

}