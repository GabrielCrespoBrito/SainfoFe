<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class NotificacionHelper
{
  protected $options = [];

  /**
   * Tipos de notificaciones
   */
  const TIPO_SUCCESS = "success";
  const TIPO_ERROR = "error";
  const TIPO_WARNING = "warning";
  const TIPO_INFO = "info";
   
  /**
   * Nombre de propiedades
   * 
   */
  const HIDE_AFTER = 'N_hideAfter';
  const SHOWHIDETRANSITION  = 'N_showHideTransition';

  /**
   * Tiempo para desaparecer notificacion, si es falso no desaparecera
   */
  const N_HIDEAFTER = false;

  /**
   * Tipos de acción de la transición
   */
  const N_SHOWHIDETRANSITION_FADE = "fade";
  const N_SHOWHIDETRANSITION_SLICE = "slice";

  /**
   * Opciones por defecto
   * 
   * @param array
   */
  const OPTIONS_DEFAULT = [
    'tipo' => self::TIPO_INFO,
    'N_hideAfter' => self::N_HIDEAFTER,
    'N_showHideTransition' => self::N_SHOWHIDETRANSITION_FADE,
  ];


  public function __construct( array $options = [])
  {
    $this->setOptions(array_merge( self::OPTIONS_DEFAULT , $options ));
  }


  public function getOptions()
  {
    return $this->options; 
  }

  public function setOptions( array $options )
  {
    $this->options = $options;

    return $this;
  }

  /**
   * Devolver las opciones con las propiedades de titulo y mensaje
   * 
   * @param mixed $titulo
   * @param mixed $mensaje
   * @return array
   */
  public function getOptionsWith($titulo = 'titulo', $mensaje = '', string $tipo )
  {
    $data_messages = [
      'titulo' => $titulo,
      'mensaje' => $mensaje,
      'tipo' => $tipo,
    ];

    return array_merge( $this->options, $data_messages);
  }

  /**
   * Notificacion Mensaje de Exito
   * 
   * @return void
   */
  public function success( $titulo, $descripcion = '' )
  {
    $this->send($this->getOptionsWith( $titulo, $descripcion , self::TIPO_SUCCESS ) );
  }

  /**
   * Notificacion Mensaje de Exito
   * 
   * @return void
   */
  public function error($titulo, $descripcion = '')
  {
    $this->send($this->getOptionsWith($titulo,$descripcion, self::TIPO_ERROR));
  }

  /**
   * Notificacion Mensaje de Exito
   * 
   * @return void
   */
  public function warning($titulo, $descripcion = '')
  {
    $this->send($this->getOptionsWith($titulo,$descripcion, self::TIPO_WARNING));
  }

  /**
   * Notificacion Mensaje de Exito
   * 
   * @return void
   */
  public function info($titulo, $descripcion = '')
  {
    $this->send($this->getOptionsWith($titulo, $descripcion, self::TIPO_INFO));
  }

  public function send( $options )
  {
    session()->flash( 'notificacion', true );
    foreach( $options as $optionName => $optionValue ) {
      session()->flash( $optionName ,  $optionValue );
    }
  }
}