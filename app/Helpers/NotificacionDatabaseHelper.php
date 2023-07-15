<?php

namespace App\Helpers;

class NotificacionDatabaseHelper
{
  const EMPRESA_REGISTRO = "empresa-registro";
  const EMPRESA_SUSCRIPCION_POR_VENCER = "empresa-suscripcion-por-vencer";
  const EMPRESA_SUSCRIPCION_VENCIDA = "empresa-suscripcion-vencida";
  
  // Tipos
  const TIPO_SUCCESS = "success";
  const TIPO_DANGER = "danger";
  const TIPO_INFO = "info";
  const TIPO_WARNINIG = "warning";


  const ICON_DATA = [
    self::EMPRESA_REGISTRO => [
      'icon' => 'fa fa fa-circle-o',
      'color' => 'text-aqua',
    ],
    self::EMPRESA_SUSCRIPCION_POR_VENCER => [
      'icon' => 'fa fa fa-circle-o',
      'color' => 'text-yellow',
    ],
    self::EMPRESA_SUSCRIPCION_VENCIDA => [
      'icon' => 'fa fa-circle-o',
      'color' => 'text-red',
    ],
    self::EMPRESA_REGISTRO => [
      'icon' => 'fa fa-circle-o',
      'color' => 'text-aqua',
    ],            
  ];  
  


  public static function getFormat( $notificacion )
  {
    $data = $notificacion->data;
    $iconData = self::ICON_DATA[$data['code']];

    return [
      'id' => $notificacion->id,
      'iconData' => $iconData,
      'type' => $data['type'],
      'titulo' => $data['titulo'],
      'descripcion' => $data['descripcion'],
      'route' => route('admin.notificaciones.show', $notificacion->id ),
      'date' => $notificacion->created_at,
      'date_read' => $notificacion->read_at,
    ];
  }

}