<?php

namespace App\Jobs\Produccion;

use App\GuiaSalida;
use App\GuiaSalidaItem;
use App\Models\TomaInventario\TomaInventarioDetalle;

class CreateDetalleFromProduccionDetalle
{
  public $produccion;
  public $guiaSalida;
  public $guiaIngreso;

  //
  public $index_ingreso = 1;
  public $index_salida = 1;
  //
  public function __construct($produccion, $guiaSalida, $guiaIngreso)
  {
    $this->produccion = $produccion;
    $this->guiaSalida = $guiaSalida;
    $this->guiaIngreso = $guiaIngreso;
  }

  public function getIndex()
  {
    // Obtener indice 
    $index = $this->is_ingreso ? $this->index_ingreso : $this->index_salida;

    // Aumentar el indice
    $this->is_ingreso ? $this->index_ingreso++ : $this->index_salida++;

    // Devolverlo en el formato correcto
    return $index  < 10 ? "0" .  $index : $index;
  }

  public function createItem( $guia, $producto, $cantidad )
  {
    $isIngreso = $guia->isIngreso();

    $cantidad_relativa = convertNegativeIfTrue($cantidad, !$isIngreso);
    $unidad = $producto->unidadPrincipal();
    $guiaItem = new GuiaSalidaItem;
    $guiaItem->DetItem = $this->getIndex();
    $guiaItem->GuiOper = $guia->GuiOper;
    $guiaItem->Linea   = agregar_ceros(GuiaSalidaItem::lastId(), 8, 1);
    $guiaItem->UniCodi =  $unidad->Unicodi();
    $guiaItem->DetNomb = $producto->ProNomb;
    $guiaItem->MarNomb = $producto->marca->MarNomb;
    $guiaItem->Detcant = $cantidad;
    $guiaItem->DetPrec = 0;
    $guiaItem->DetPeso = 0;
    $guiaItem->DetDct1 = null;
    $guiaItem->DetDct2 = null;
    $guiaItem->CpaVtaCant = $cantidad_relativa;
    $guiaItem->DetImpo = 0;
    $guiaItem->DetUnid = $producto->UniAbre;
    $guiaItem->DetCodi = $producto->ProCodi;
    $guiaItem->DetCSol = $producto->ProPUCS;
    $guiaItem->DetCDol = $producto->ProPUCD;
    $guiaItem->CCoCodi = null;
    $guiaItem->CpaVtaLine = NULL;
    $guiaItem->DetTipo = $isIngreso ? GuiaSalidaItem::TIPO_COMPRA : GuiaSalidaItem::TIPO_VENTA;
    $guiaItem->DetEsPe = "0";
    $guiaItem->DetFact = 1;
    $guiaItem->empcodi = $guia->EmpCodi;
    $guiaItem->DetIng  = null;
    $guiaItem->save();
    $guiaItem->updateStock($guiaItem->DetCodi, $guia->Loccodi);
  }

  public function createDetallesIngreso()
  {
    $producto = $this->produccion->producto;
    $this->createItem($this->guiaIngreso, $producto, $this->produccion->manCant);
  }

  public function createDetallesSalida()
  {
  }

  public function handle()
  {
    $this->createDetallesIngreso();
    $this->createDetallesSalida();
  }
}
