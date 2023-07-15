<?php

namespace App\Admin\SystemStat;

use Exception;

trait  SystemStatMethod 
{
  /**
   * Obtener unos campos especificos
   * 
   * @return Collection
   */
  public function getField( array $fields, bool $excludeCero = false )
  {
    if ($this->data === null) {
      $this->data =  $this->repository()->all();
    }

    $collection = $this->data->whereIn( 'name' , $fields )->mapWithKeys(function ($item) {
      return [$item->name => $item];
    });

    if( $excludeCero ){
      return $collection->where('value' , '!=' , 0 )->all();
    }

    return $collection;
  }

  /**
   * Actualizar acción
   * 
   * @return bool
   */
  public static function updateAccion($accion, $data)
  {
    // Actualizar el registro de acciones pendientes
    $systemStatAccionesPendiente = SystemStat::findByName(SystemStat::ACCIONES_PENDIENTES);
    $data_accion = json_decode(json_encode($systemStatAccionesPendiente->value), true);    
    $data_accion['documentos_pendientes']['cant'] = $data['total_docs'];
    $data_accion['documentos_pendientes']['updated_at'] = $systemStatAccionesPendiente->updated_at;
    $systemStatAccionesPendiente->value = $data_accion;    
    $systemStatAccionesPendiente->updated_at = now();
    $systemStatAccionesPendiente->save();
  }
  

  
  /**
   * Obtener las empresas con documentos pendientes
   */
  public function getEmpresasVentasPendientes()
  {
    $pendientes = $this->getField([ SystemStat::EMPRESAS_VENTAS_PENDIENTES ])->first();
    
    return (object) [
      'data' => $pendientes->value,
      'updated_at' => $pendientes->updated_at,
    ];
  }

  /**
   * Obtener las empresas con documentos pendientes
   */
  public function getEmpresasGuiasPendientes()
  {
    $pendientes = $this->getField([SystemStat::EMPRESAS_GUIAS_PENDIENTES])->first();

    return (object) [
      'data' => $pendientes->value,
      'updated_at' => $pendientes->updated_at,
    ];
  }

  /**
   * Obtener las empresas con documentos pendientes
   */
  public function getEmpresasResumenesPendientes()
  {
    $pendientes = $this->getField([SystemStat::EMPRESAS_RESUMENES_PENDIENTES])->first();

    return (object) [
      'data' => $pendientes->value,
      'updated_at' => $pendientes->updated_at,
    ];
  }

  
  public function getRoute( $routeComplete = false )
  {
    // const EMPRESAS_VENTAS_PENDIENTES = "empresas_ventas_pendientes";
    // const EMPRESAS_GUIAS_PENDIENTES = "empresas_guias_pendientes";
    // const EMPRESAS_RESUMENES_PENDIENTES = "empresas_resumenes_pendientes";

    switch ($this->name) {      
      case SystemStat::VENTAS_TOTALES:
        $route = "#";
        break;
      case SystemStat::COMPRAS_TOTALES:
        $route = "#";
        break;
      case SystemStat::USUARIOS_ACTIVOS:
        $route = "#";
        break;               
      case SystemStat::EMPRESAS_ACTIVAS:
        $route = "#";
      break;
      case SystemStat::EMPRESAS_VENTAS_PENDIENTES:
        $route = 'admin.documentos.pending';
        break;
      case SystemStat::EMPRESAS_GUIAS_PENDIENTES:
        $route = 'admin.guias.pending';
        break;
      case SystemStat::EMPRESAS_RESUMENES_PENDIENTES:
        $route = 'admin.resumenes.pending';
        break;
      case SystemStat::RESUMENES_POR_VALIDAR:
        $route = "#";
        break;
        default:
        throw new Exception("{$this->name} Route Don't Exists ", 1);        
    }

    return $routeComplete ? route($route) : $route;
  }
  
  public function getStatsHome()
  {
    return SystemStat::findByName(SystemStat::SYSTEM_STADISTICAS);
  }

  public function getAcciones()
  {
    return $this->findByName(SystemStat::ACCIONES_PENDIENTES);
  }


  public static function updateAccionesPendientes($updateTimestamp = false , ...$system_pendings  )
  {
    $systemStatAccionesPendiente = SystemStat::findByName(SystemStat::ACCIONES_PENDIENTES);
    $datas = json_decode(json_encode($systemStatAccionesPendiente->value), true);

    foreach( $system_pendings as $system_pending ){
      
      $name = $system_pending->name;
      
      if( $system_pending->value->result ){

        if( ! array_key_exists($name, $datas) ){
          $datas[$name] = [];          
        }

        $datas[$name]['cant'] = $system_pending->value->total_docs;
        $datas[$name]['updated_at'] =  $system_pending->updated_at->format('Y-m-d H:i:s');
        $datas[$name]['route'] =  $system_pending->getRoute();
      }
      else {
        unset($datas[$name]);
      }
    }
    
    $systemStatAccionesPendiente->value = $datas;

    if($updateTimestamp){
      $systemStatAccionesPendiente->updated_at = now();
    }

    $systemStatAccionesPendiente->save();    
  }

}