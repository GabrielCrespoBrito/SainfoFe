<?php

namespace App\Jobs\TomaInventario;

use App\GuiaSalida;
use App\Jobs\Guia\CreateDetalleFromTomaDetalle;
use App\Models\Guia\Guia;
use App\Models\Guia\GuiaIngreso;
use App\Models\TomaInventario\TomaInventario;
use App\Models\TomaInventario\TomaInventarioDetalle;

class CreateGuias
{
  public $tomaInventario;
  public $guia_ingreso = null;
  public $guia_salida = null;
  public $detalle_creator = null;

  public function __construct(TomaInventario $tomaInventario)
  {
    $this->tomaInventario = $tomaInventario;
    $this->detalle_creator = new CreateDetalleFromTomaDetalle();
  }

  public function createGuiaSalida()
  {
    return GuiaSalida::createFromToma($this->tomaInventario);
  }

  public function createGuiaIngreso()
  {
    return GuiaIngreso::createFromToma($this->tomaInventario);
  }

  public function getOrCreateGuiaIngreso()
  {
    if ($this->guia_ingreso) {
      return $this->guia_ingreso;
    }

    $this->guia_ingreso = $this->createGuiaIngreso();

    $this->detalle_creator->setGuia( $this->guia_ingreso );

    return $this->guia_ingreso;
  }

  public function getOrCreateGuiaSalida()
  {
    if( $this->guia_salida){
      return $this->guia_salida;
    }
    
    $this->guia_salida = $this->createGuiaSalida();

    $this->detalle_creator->setGuia($this->guia_salida);

    return $this->guia_salida;
  }

  public function createGuiaSalidaDetalle( $detalle )
  {
    $this->getOrCreateGuiaSalida();
    $this->detalle_creator->setCurrentDetalle($detalle);
    $this->detalle_creator->createItem();
  }
  
  public function createGuiaIngresoDetalle( $detalle )
  {
    $this->getOrCreateGuiaIngreso();
    $this->detalle_creator->setCurrentDetalle($detalle);
    $this->detalle_creator->createItem();
    
  }

  public function registerDetalle(TomaInventarioDetalle $detalle)
  {
    if ($detalle->isFromIngreso()) {
      $this->createGuiaIngresoDetalle($detalle);
    } 
    
    else {
      $this->createGuiaSalidaDetalle($detalle);
    }
  }

  /**
   * Ejecutar Creación
   *
   * @return void
   */
  public function handle()
  {
    $detalles_chunk = $this->tomaInventario->detalles->chunk(50);
    foreach ($detalles_chunk as $detalles) {
      foreach ($detalles as $detalle) {
        $this->registerDetalle($detalle);
      }
    }
  }
}
