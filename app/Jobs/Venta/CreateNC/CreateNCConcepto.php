<?php

namespace App\Jobs\Venta\CreateNC;

use App\TipoIgv;
use App\Producto;
use App\FormaPago;
use App\VentaItem;
use App\VentaCredito;
use App\TipoNotaCredito;
use App\Models\MedioPago\MedioPago;
use App\Models\Venta\Traits\Calculator;
use App\Models\Venta\Traits\CalculatorTotal;

class CreateNCConcepto extends CreatorNCAbstract
{
  public $totalItem = null;
  public $baseImponible = "";

  public function createItems()
  {
    $totalItem = $this->totalItem;

    $producto = Producto::first();
    $unidad = $producto->unidadPrincipal();
    $it = new VentaItem();
    $it->Linea = VentaItem::nextLinea();
    $it->DetItem = 1;
    $it->VtaOper = $this->nc->VtaOper;
    $it->EmpCodi = $this->nc->EmpCodi;
    $it->DetUnid = $unidad->UniAbre;
    $it->UniCodi = $unidad->Unicodi;
    $it->DetCodi = $producto->ProCodi;        
    $it->DetNomb = strtoupper($this->data['concepto']);
    $it->MarNomb = "SIN DEFINIR";
    $it->DetCant = $totalItem['cantidad'];
    $it->DetPrec = $totalItem['precio'];
    $it->DetPeso = 0;
    $it->DetEsta = "V";
    $it->DetEspe = 0;
    $it->lote = $totalItem;
    $it->DetCSol = 0;
    $it->DetCDol = 0;
    $it->DetVSol = $totalItem['costo_soles'];
    $it->DetVDol = $totalItem['costo_dolares'];
    $it->DetSdCa = 0;
    $it->DetDcto = 0;
    $it->DetDctoV = 0;
    $it->Detfact = 0;
    $it->DetIGVV = get_igv();            
    $it->DetIGVP = $totalItem['igv_total'];
    $it->DetISC = 0;
    $it->DetISCP = 0;
    $it->icbper_value = 0;
    $it->icbper_unit = 0;
    $it->DetImpo = $totalItem['total'];
    $it->Estado  = $producto->TieCodi;
    $it->DetBase = $this->baseImponible;
    $it->incluye_igv = 1;
    $it->DetPercP = 0;
    $it->TipoIGV = TipoIgv::DEFAULT_GRAVADA;
    $it->save();
  }

  public function getTotales()
  {
    // Calcular el item del producto, deacuerdo a su base
    $calculator = new Calculator();
    $this->baseImponible = TipoIgv::getRealBase($this->data['tipoIgv']);
    
    $calculator->setValues(
      $this->data['importe'],
      return_value('Cantidad', 1),
      return_value('IncluyeIGV', true),
      $this->baseImponible,
      return_value('Descuento Porc', 0),
      return_value('Bolsa', false),
      return_value('ISC', 0),
      return_value('Factor', 0),            
      return_value('Tipo de Cambio', get_empresa()->opcion->tipo_cambio_publico),      
      $this->documento->isSol(),
      return_value('Peso', 0),
    );

    $calculator->calculate();
    $this->totalItem = $calculator->getCalculos();
    $calculator = new CalculatorTotal([$this->totalItem]);
    
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
    $nc->VtaObse = TipoNotaCredito::DESCRIPCION_10_OTROS_CONCEPTOS;
    $nc->vtaadoc =  TipoNotaCredito::CODE_10_OTROS_CONCEPTOS;
    $nc->save();
    $this->nc = $nc;
  }
}
