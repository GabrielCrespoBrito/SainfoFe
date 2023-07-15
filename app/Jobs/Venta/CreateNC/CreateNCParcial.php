<?php

namespace App\Jobs\Venta\CreateNC;

use App\FormaPago;
use App\VentaItem;
use App\TipoNotaCredito;
use App\Models\MedioPago\MedioPago;
use App\Models\Venta\Traits\Calculator;
use App\Models\Venta\Traits\CalculatorTotal;

class CreateNCParcial extends CreatorNCAbstract
{
  public function createItems()
  {
    $items = $this->data['items'];
    $linea = 1;
    foreach ($items as $item) {
      $model = $item['model'];
      $total = $item['total'];
      $it = new VentaItem();
      $it->Linea = VentaItem::nextLinea();
      $it->DetItem = agregar_ceros($linea,2, 0);
      $it->VtaOper = $this->nc->VtaOper;
      $it->EmpCodi = $this->nc->EmpCodi;
      $it->DetUnid = $model->DetUnid;
      $it->UniCodi = $model->UniCodi;
      $it->DetCodi = $model->DetCodi;
      $it->DetNomb = $model->DetNomb;
      $it->MarNomb = $model->MarNomb;
      $it->DetCant = $item['cantidad'];
      $it->DetPrec = $model->DetPrec;
      $it->DetImpo = $total['total'];
      $it->DetPeso = 0;
      $it->DetEsta = "V";
      $it->DetEspe = 0;
      $it->lote = $total;
      // $totalItem['costo_soles'];
      // $totalItem['costo_dolares'];
      $producto = $model->producto;
      $unidad = $producto->unidadPrincipal();
      $factor = $unidad->getFactor();
      $costos = $unidad->getCostos($producto->ProCodi, $this->nc->VtaFvta, $this->nc->LocCodi, $item['cantidad'], $factor, $producto->incluyeIgv());

      $it->DetCSol = $costos->sol;
      $it->DetCDol = $costos->dolar;
      $it->DetVSol = $total['costo_soles'];
      $it->DetVDol = $total['costo_dolares'];


      $it->DetSdCa = 0;
      $it->DetDcto = 0;
      $it->DetDctoV = 0;
      $it->Detfact = $model->Detfact;
      $it->DetIGVV = $total['igv_unitario'];
      $it->DetIGVP = $total['igv_total'];
      $it->DetISC = 0;
      $it->DetISCP = 0;
      $it->icbper_value = $total['bolsa'];
      $it->icbper_unit = $total['bolsa_unit'];
      $it->Estado  = $model->Estado;
      $it->DetBase = $model->DetBase;
      $it->incluye_igv = $model->incluye_igv;
      $it->DetPercP = 0;
      $it->TipoIGV =  $model->TipoIGV;
      $it->save();
      $linea++;
    }
  }

  public function getTotales()
  {
    $calculator = new Calculator();
    $items = $this->data['items'];
    $totals = [];
    $items = $items->map((function ($item) use (&$totals, $calculator) {
      $model = $item['model'];
        $calculator->setValues(
        $model->DetPrec,
        $item['cantidad'],
        $model->incluye_igv,
        $model->DetBase,
        $model->DetDcto,
        convertBooleanNumber($model->icbper_value),
        $model->DetISCP,
        $model->Detfact,
        $this->documento->VtaTcam,
        $this->documento->isSol(),
        $model->DetPeso,
      );

      $calculator->calculate();
      $totals_item = $calculator->getCalculos();
      $totals[] = $totals_item;
      $item['total'] =  $totals_item;
      return $item;
    }));

    $this->data['items'] = $items;
    $calculator = new CalculatorTotal($totals);
    return $calculator->getTotal();
  }

  public function createDocumento()
  {
    $nc = $this->getDocumentoWithInitialData();
    $nc->ConCodi = FormaPago::CODIGO_CONTABLE_GENERAL;
    $nc->TpgCodi = MedioPago::CODIGO_SINDEFINIR;
    $nc->VtaSdCa = 0;
    $nc->VtaSdCa = 0;
    $nc->VtaPedi = 0;
    $nc->VtaObse = $this->data['motivo'];
    $nc->vtaadoc = TipoNotaCredito::CODE_07_DEVOLUCION_ITEM;
    $nc->save();
    $this->nc = $nc;
  }
}
