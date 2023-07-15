<?php

namespace App\Jobs\Admin;

use App\Admin\SystemStat\SystemStat;

class UpdateAllPendientes
{
  public function handle()
  {
    $system_stat_docs = (new UpdateEmpresasVentasPendientes(false,false))->handle();
    $system_stat_guias = (new UpdateEmpresasGuiasPendientes(false, false))->handle();
    $system_stat_resumenes = (new UpdateEmpresasResumenPendientes(false, false))->handle();  

    SystemStat::updateAccionesPendientes(true, $system_stat_docs, $system_stat_guias, $system_stat_resumenes );

    # Borrar Cache
    (new SystemStat())->repository()->clearCache('all');

  }
}