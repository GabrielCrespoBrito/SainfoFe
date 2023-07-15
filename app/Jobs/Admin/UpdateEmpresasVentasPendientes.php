<?php

namespace App\Jobs\Admin;

use App\Venta;
use App\Empresa;
use App\Admin\SystemStat\SystemStat;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class UpdateEmpresasVentasPendientes extends UpdatePendiente
{
  public function __construct($cleanCache = true, $updateGeneralPendientes = true, $notifyToAdmin = false )
  {
    $systemStat = SystemStat::findByName(SystemStat::EMPRESAS_VENTAS_PENDIENTES);
    parent::__construct($cleanCache, $systemStat, $updateGeneralPendientes, $notifyToAdmin);
  }

  /**
   * searchInEmpresa
   * 
   * @return int
   */
  public function searchInEmpresa(Empresa $empresa)
  {
    $query = $empresa->ventas->where('VtaFMail', StatusCode::CODE_ERROR_0011);

    if( ! $empresa->envioBoleta() ){
      $query = $query->whereNotIn('TidCodi', [Venta::BOLETA]);
    }

    return $query->count();
  }  
}