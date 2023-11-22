<?php

namespace App\Jobs\Guia;

use Exception;
use App\GuiaSalida;
use App\GuiaSalidaItem;
use App\Models\TomaInventario\TomaInventarioDetalle;

class CreateDetalleFromTomaDetalle
{
  public $guia_ingreso;
  public $guia_salida;

  public $detalle;
  public $is_ingreso;

  public $index_ingreso = 1;
  public $index_salida = 1;

  public function __construct()
  {
  }

  public function setGuia( GuiaSalida $guia )
  {
    if( $guia->isIngreso()){
      $this->guia_ingreso = $guia;
    }
    else {
      $this->guia_salida = $guia;
    }
  }


  public function setCurrentDetalle( TomaInventarioDetalle $detalle)
  {
    $this->detalle = $detalle;
    $this->is_ingreso = $detalle->isFromIngreso();
  }

  public function getCurrentDetalle()
  {
    return $this->detalle;
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


  public function getCorrectGuia()
  {
    $guia = $this->is_ingreso ? $this->guia_ingreso : $this->guia_salida;

    if( $guia == null ){
      $nombre = $this->is_ingreso ? 'ingreso' :  'salida';
      throw new Exception("No se ha cargado la guia de {$nombre}", 1);
    }

    return $guia;
  }

  public function createItem()
  {
    $detalle = $this->getCurrentDetalle();
    $guia = $this->getCorrectGuia();
    $isIngreso = $guia->isIngreso();
    $cantidad_relativa = $detalle->getDiff();
    $cantidad_absoluta = convertNegativeIfTrue( $cantidad_relativa, $isIngreso == false );
    
    $guiaItem = new GuiaSalidaItem;
    $guiaItem->DetItem = $this->getIndex();
    $guiaItem->GuiOper = $guia->GuiOper;
    $guiaItem->Linea   = agregar_ceros(GuiaSalidaItem::lastId(),8,1);
    $guiaItem->UniCodi = $detalle->getUnidadCodigo();
    $guiaItem->DetNomb = $detalle->proNomb;
    $guiaItem->MarNomb = $detalle->ProMarc;
    $guiaItem->Detcant = $cantidad_absoluta;
    $guiaItem->DetPrec = 0;
    $guiaItem->DetPeso = 0;
    $guiaItem->DetDct1 = null;
    $guiaItem->DetDct2 = null;
    $guiaItem->CpaVtaCant = $cantidad_relativa;
    $guiaItem->DetImpo = $detalle->getImporte();
    $guiaItem->DetUnid = $detalle->UnpCodi;
    $guiaItem->DetCodi = $detalle->ProCodi;
    $guiaItem->DetCSol = $detalle->ProPUCS;
    $guiaItem->DetCDol = 0;
    $guiaItem->CCoCodi = null;
    $guiaItem->CpaVtaLine = NULL;
    $guiaItem->DetTipo = $isIngreso ? GuiaSalidaItem::TIPO_COMPRA : GuiaSalidaItem::TIPO_VENTA;
    $guiaItem->DetEsPe = "0";
    $guiaItem->DetFact = 1;
    $guiaItem->empcodi = $guia->EmpCodi;
    $guiaItem->DetIng  = null;
    $guiaItem->save();
    $guiaItem->updateStock2($guiaItem->DetCodi );
  }
}