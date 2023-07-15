<?php

namespace App\Jobs\Venta\CreateNC;

use App\TipoIgv;
use App\Producto;
use App\FormaPago;
use App\VentaItem;
use App\VentaCredito;
use App\TipoNotaCredito;
use App\Models\MedioPago\MedioPago;

class CreateNCTotal extends CreatorNCAbstract
{
  public function createItems()
  {
    $items = $this->documento->items;

    $index = 1; 
    foreach ( $items as $item ) {
      $totales_item = $item->calculos();
      $it = new VentaItem();
      $it->Linea = VentaItem::nextLinea();
      $it->DetItem = math()->addCero($index,2);
      $it->VtaOper = $this->nc->VtaOper;
      $it->EmpCodi = $this->nc->EmpCodi;
      $it->DetUnid = $item->DetUnid;
      $it->UniCodi = $item->UniCodi;      
      $it->DetCodi = $item->DetCodi;
      $it->DetNomb = $item->DetNomb;
      $it->MarNomb = $item->MarNomb;
      $it->DetCant = $item->DetCant;
      $it->DetPrec = $item->DetPrec;
      $it->DetImpo = $item->DetImpo;
      $it->DetPeso = $item->DetPeso;
      $it->DetEsta = "V";
      $it->DetEspe = 0;
      $it->lote = $totales_item;
      $it->DetCSol = $item->DetCSol;
      $it->DetCDol = $item->DetCDol;
      $it->DetVSol = $item->DetVSol;
      $it->DetVDol = $item->DetVDol;
      $it->DetSdCa = $item->DetSdCa;
      // $it->DetDcto = 0;
      // $it->DetDctoV = 0;
      $it->DetDcto = $item->DetDcto;
      $it->DetDctoV = $item->DetDctoV;      
      $it->Detfact = $item->Detfact;
      $it->DetIGVV = $item->DetIGVV;
      $it->DetIGVP = $item->DetIGVP;
      $it->DetISC = $item->DetISC;
      $it->DetISCP = $item->DetISCP;
      $it->icbper_value = $item->icbper_value;
      $it->icbper_unit = $item->icbper_unit;
      $it->Estado  = $item->Estado;
      $it->DetBase = $item->DetBase;
      $it->incluye_igv = $item->incluye_igv;
      $it->DetPercP = $item->DetPercP;
      $it->TipoIGV =  $item->TipoIGV;
      $index++;
      $it->save();
    }
  }

  public function getTotales()
  {
    $totales = $this->documento->totales_documento;

    return (object) [
      'igv' => $this->documento->VtaIGVV,
      // 'descuento_total' => 0,
      'descuento_total' => $this->documento->descuento_total,
      'total_gravadas' => $this->documento->Vtabase,
      'total_cobrado' => $this->documento->VtaImpo,
      'total_inafecta' => $this->documento->VtaInaf,
      'total_exonerada' => $this->documento->VtaExon,
      'total_gratuita' => $this->documento->VtaGrat,
      'total_base_percepcion' => $totales->total_base_percepcion,
      'percepcion_porc' => $this->documento->VtaSPer,
      'percepcion' => $totales->percepcion,
      'icbper' => $this->documento->icbper,
      'isc' => $this->documento->VtaISC,
      'total_cantidad' => $this->documento->Vtacant,
      'total_valor_bruto_venta' => $totales->total_valor_bruto_venta,
      'total_base_isc' => $totales->total_base_isc,
      'total_valor_venta' => $totales->total_valor_venta,
      'valor_venta_por_item_igv' => $totales->valor_venta_por_item_igv,
      'descuento_global' => $totales->descuento_global,
      'retencion' => $totales->retencion,
      'igv_porc' => get_igv(),
      'total_importe' => $totales->total_importe,
      'impuestos_totales' => $totales->impuestos_totales,
      // 'total_peso' => $totales->total_peso
      'total_peso' => 0
    ];
  }

  public function createDocumento()
  {
    $nc = $this->getDocumentoWithInitialData();
    $nc->ConCodi = FormaPago::CODIGO_CONTABLE_GENERAL;
    $nc->TpgCodi = MedioPago::CODIGO_SINDEFINIR;
    $nc->VtaSdCa = 0;
    $nc->VtaPedi = 0;
    $nc->VtaSPer = $this->documento->VtaSPer;
    $nc->VtaPPer = $this->documento->VtaPPer;
    $nc->VtaObse = $this->data['motivo'];
    $nc->vtaadoc = TipoNotaCredito::CODE_01_ANULACION_OPERACION;
    $nc->save();
    $this->nc = $nc;
  }
  
  public function createPagos()
  {
    VentaCredito::create([
      'item' => "01",
      'VtaOper' => $this->nc->VtaOper,
      'monto' => $this->nc->VtaImpo,
      'fecha_pago' => $this->nc->VtaFvta,
      'forma_pago_id' => FormaPago::CODIGO_CONTABLE_GENERAL,
      'MonCodi' => $this->nc->MonCodi,
    ]);
  }
}
