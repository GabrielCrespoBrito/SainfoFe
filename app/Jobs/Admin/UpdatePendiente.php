<?php

namespace App\Jobs\Admin;

use App\Empresa;
use App\Admin\SystemStat\SystemStat;
use App\Notifications\EmpresaRegister;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

abstract class UpdatePendiente 
{
  public $cleanCache;
  public $systemStat;
  public $updateGeneralPendientes;
  public $notifiyToAdmin;
  
  public $data = [
      'result' => 0,
      'empresas' => [],
      'total_empresas' => 0,
      'total_docs' => 0,
  ];

  public function __construct($cleanCache = true, SystemStat $systemStat, $updateGeneralPendientes = true, $notifiyToAdmin = false)
  {
    ini_set('memory_limit',  -1 );

    $this->cleanCache = $cleanCache;
    $this->systemStat = $systemStat;
    $this->updateGeneralPendientes = $updateGeneralPendientes;
    $this->notifiyToAdmin = $notifiyToAdmin;
  }

  /**
   * Hacer la busqueda pertinente, correspondiente
   */
  public function searchInEmpresa( Empresa $empresa )
  {
    return 0;
  }

  public function getEmpresas()
  {
    return Empresa::activas()->ambienteProduccion()->get();
  }
  
  public function updateStat()
  {
    $this->systemStat->value = $this->data;
    $this->systemStat->updated_at = now();
    $this->systemStat->save();
  }

  public function addToData($empresa_id, $cantidad)
  {
    $this->data['result'] = 1;
    $this->data['empresas'][$empresa_id] = [
      'id' => $empresa_id,
      'cant' => $cantidad
    ];
    $this->data['total_empresas'] += 1;
    $this->data['total_docs'] +=  $cantidad;
  }

  public function processEmpresas()
  {
    $empresas = $this->getEmpresas();

    foreach ($empresas as $empresa) {

      try {
        # Activar coneccion a bd de empresa
        (new ActiveEmpresaTenant($empresa))->handle();
        $cant_pendiente =  $this->searchInEmpresa($empresa);

        if ($cant_pendiente) {
          $this->addToData($empresa->id(), $cant_pendiente);
        }
        //code...
      } catch (\Throwable $th) {
        logger(['@ERROR en UpdatePendiente', 'empresa' => $empresa->id(), 'error' => $th->getMessage()]);
      }


    }
  }

  /**
   * Borrar cache
   * 
   */
  public function cleanCache()
  {
    if ($this->cleanCache) {
      (new SystemStat)->repository()->clearCache('all');
    }    
  }

  public function handle()
  {
    $this->processEmpresas();
    $this->updateStat();
    $this->cleanCache();
    $this->updateAccionesPendiente();
    return $this->systemStat;
  }

  public function updateAccionesPendiente()
  {
    if( $this->updateGeneralPendientes ){
      SystemStat::updateAccionesPendientes(true, $this->systemStat);
    }
  }
}