<?php

namespace App\Jobs\Venta\CreateND;

use App\Venta;
use App\TipoIgv;
use App\Producto;
use App\FormaPago;
use App\VentaItem;
use App\TipoNotaDebito;
use App\TipoDocumentoPago;
use App\TipoCambioPrincipal;
use Illuminate\Support\Facades\DB;
use App\Models\MedioPago\MedioPago;
use App\Models\Venta\Traits\Calculator;
use App\Models\Venta\Traits\CalculatorTotal;
use App\Jobs\Venta\CreateNota\CreatorNotaAbstract;

class CreateND extends CreatorNotaAbstract
{
  public function __construct(Venta $documento, array $data)
  {
    parent::__construct($documento, $data, TipoDocumentoPago::NOTA_DEBITO);
  }

  public $totalItem = null;
  public $baseImponible = "";

  public function createItems()
  {
    $totalItem = $this->totalItem;
    $producto = Producto::first();
    $unidad = $producto->unidadPrincipal();
    $it = new VentaItem();
    $it->Linea = VentaItem::nextLinea();
    $it->DetItem = "01";
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
    $it->DetIGVV =  TipoIgv::isTipoGravada($this->data['tipoIgv']) ? get_igv() : 0;
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
    $it->TipoIGV = $this->data['tipoIgv'];
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
      return_value('ISC', 0 ),
      return_value('Factor', 1 ),
      return_value('Tipo de Cambio', TipoCambioPrincipal::ultimo_cambio(false)),
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
    // HabitosAtomicos
    $nc = $this->getDocumentoWithInitialData();
    $nc->ConCodi = FormaPago::CODIGO_CONTABLE_GENERAL;
    $nc->TpgCodi = MedioPago::CODIGO_SINDEFINIR;
    $nc->VtaSdCa = 0;
    $nc->VtaSdCa = 0;
    $nc->VtaPedi = 0;
    $nc->VtaObse = TipoNotaDebito::DESCRIPCION_03_PENALIDADES_OTROS_CONCEPTOS;
    $nc->vtaadoc = TipoNotaDebito::CODE_03_PENALIDADES_OTROS_CONCEPTOS;
    $nc->save();
    $this->nc = $nc;
  }


  public function handle()
  {
    DB::connection('tenant')->beginTransaction();            
    try {
      $this->createDocumento();
      $this->saveSerie();
      $this->createItems();
      $this->createDataAssociate();
      $this->sendSunat();
      DB::connection('tenant')->commit();
    } catch (\Throwable $th) {
      DB::connection('tenant')->rollBack();
      return ['success' => false,  'errors' => $th->getMessage(), 'error' => $th->getMessage()];
    }
    
    $this->sendSunat();
    return ['success' => true, 'error' => null];
  }
  
}
