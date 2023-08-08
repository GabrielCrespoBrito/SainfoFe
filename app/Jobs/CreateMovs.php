<?php

namespace App\Jobs;

use App\GuiaSalida;
use Illuminate\Bus\Queueable;
use App\Models\Guia\GuiaIngreso;
use App\Models\Produccion\Produccion;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\Guia\CreateDetalleFromTomaDetalle;
use App\Jobs\Produccion\CreateDetalleFromProduccionDetalle;
use App\Models\Produccion\ProduccionDetalle;

class CreateMovs
{
  public $produccion;
  public $tomaInventario;
  public $guia_ingreso = null;
  public $guia_salida = null;
  public $detalle_creator = null;

  public function __construct(Produccion $produccion)
  {
    $this->produccion = $produccion;
    $this->detalle_creator = new CreateDetalleFromProduccionDetalle();
  }

  public function createGuiaSalida()
  {
    $this->guia_salida = GuiaSalida::createFromProduccion($this->produccion);
    foreach( $this->produccion->items as $item ){
      $this->createGuiaSalidaDetalle($item);
    }
  }

  public function createGuiaIngreso()
  {
    $guia = GuiaIngreso::createFromProduccion($this->produccion);
    
    $this->createGuiaIngresoDetalle($guia);
  }

  public function getOrCreateGuiaIngreso()
  {
    $this->guia_ingreso = $this->createGuiaIngreso();
    $this->detalle_creator->setGuia($this->guia_ingreso);
    return $this->guia_ingreso;
  }

  public function getOrCreateGuiaSalida()
  {
    $this->guia_salida = $this->createGuiaSalida();
    $this->detalle_creator->setGuia($this->guia_salida);
    return $this->guia_salida;
  }

  public function createGuiaSalidaDetalle($detalle)
  {
    $this->detalle_creator->setCurrentDetalle($detalle);
    $this->detalle_creator->createItem();
  }

  public function createGuiaIngresoDetalle($detalle)
  {
    // $this->detalle_creator->setCurrentDetalle($detalle);
    // $this->detalle_creator->createItem();
  }

  public function registersDetalle()
  {
      $this->createGuiaIngresoDetalle();
      $this->createGuiaSalidaDetalle();
  }

  public function handle()
  {
    $this->createGuiaIngreso();
    $this->createGuiaSalida();

    (new CreateDetalleFromProduccionDetalle(
      $this->produccion,
      $this->guia_salida, 
      $this->guia_ingreso))->handle();
  }
}
