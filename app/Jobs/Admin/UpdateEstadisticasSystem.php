<?php

namespace App\Jobs\Admin;

use App\Empresa;
use App\Admin\SystemStat\SystemStat;

class UpdateEstadisticasSystem
{
  protected $empresas;
  protected $stat;

  public function __construct()
  {
    $this->empresas = Empresa::activas()->ambienteProduccion();
    $this->stat = SystemStat::findByName(SystemStat::SYSTEM_STADISTICAS);
  }

  public function getCantidadEmpresasActivas()
  {
    return $this->empresas->count();
  }

  public function getVentasRealizadas()
  {
    return 0;
  }

  public function getComprasRealizadas()
  {
    return 0;
  }

  public function getGuiasRealizadas()
  {
    return 0;
  }

  public function getProductosRegistrados()
  {
    return 0;
  }  

  public function handle()
  {
    $stat = $this->stat;
    $data_stadisticas = json_decode(json_encode($stat->value),true);
    $data_stadisticas['empresas_activas'] = $this->getCantidadEmpresasActivas();
    $data_stadisticas['ventas_realizadas'] = $this->getVentasRealizadas();
    $data_stadisticas['compras_realizadas'] = $this->getComprasRealizadas();
    $data_stadisticas['guias_realizadas'] = $this->getGuiasRealizadas();
    $data_stadisticas['productos_registrados'] = $this->getProductosRegistrados();

    $stat->value = $data_stadisticas;
    $stat->updated_at = now();
    $stat->save();
  }
}