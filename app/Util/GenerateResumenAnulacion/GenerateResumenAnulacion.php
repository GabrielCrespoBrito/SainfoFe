<?php

namespace App\Util\GenerateResumenAnulacion;

use App\Venta;
use App\Resumen;
use App\ResumenDetalle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateResumenAnulacion
{
  protected $documento;
  protected $fechaDoc;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(Venta $documento)
  {
    $this->documento = $documento;
    $this->fechaDoc = $this->documento->VtaFvta;
  }

  public function getData()
  {
    return [
      "fecha_generacion" => $this->fechaDoc,
      "fecha_documento" => $this->fechaDoc
    ];
  }

  public function getIds()
  {
    return (array) $this->documento->VtaOper;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function generate() 
  {
    $ids = $this->getIds();
    $descripcion = "Documentos: " .  $this->documento->VtaNume;

    if ($this->documento->anularPorRC()) {
      $resumen =  Resumen::createResumen($this->getData(), $this->fechaDoc, true, false, "", $descripcion);
      ResumenDetalle::createDetalle($resumen, $this->getIds(), true);
    } else {
      $resumen = Resumen::createAnulacion($this->getData() ,  $descripcion );
      ResumenDetalle::createAnulacion($resumen, $this->getIds(), 'venta');
    }

    return $resumen;
  }
}
