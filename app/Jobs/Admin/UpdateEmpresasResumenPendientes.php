<?php

namespace App\Jobs\Admin;

use App\Empresa;
use App\Admin\SystemStat\SystemStat;
use App\Resumen;

class UpdateEmpresasResumenPendientes extends UpdatePendiente
{
  public function __construct($cleanCache = true, $updateGeneralPendientes = true)
  {
    $systemStat = SystemStat::findByName(SystemStat::EMPRESAS_RESUMENES_PENDIENTES);
    parent::__construct($cleanCache, $systemStat, $updateGeneralPendientes);
  }

  public function searchInEmpresa(Empresa $empresa)
  {
    return $empresa->resumenes->where('UDelete' ,  Resumen::POR_PROCESAR_STATE )->count();
  }

}