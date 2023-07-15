<?php

namespace App\Jobs\Admin;

use App\Empresa;
use App\GuiaSalida;
use Illuminate\Support\Facades\Log;
use App\Admin\SystemStat\SystemStat;
use Illuminate\Support\Facades\DB;

class UpdateEmpresasGuiasPendientes extends UpdatePendiente
{
  public function __construct($cleanCache = true, $updateGeneralPendientes = true, $notifyToAdmin = false)
  {
    $systemStat = SystemStat::findByName(SystemStat::EMPRESAS_GUIAS_PENDIENTES);
    parent::__construct($cleanCache, $systemStat, $updateGeneralPendientes, $notifyToAdmin);
  }

  public function searchInEmpresa(Empresa $empresa)
  {
    $cant = $empresa->guias
    ->where('GuiEFor', GuiaSalida::CON_FORMATO)
    ->where('EntSal', GuiaSalida::SALIDA )
    ->whereIn('fe_rpta', [9, 99, 98])
    ->count();

    return $cant;
  }
}
