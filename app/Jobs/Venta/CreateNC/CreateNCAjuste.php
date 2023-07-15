<?php

namespace App\Jobs\Venta\CreateNC;

use App\FormaPago;
use App\Models\MedioPago\MedioPago;
use App\TipoIgv;
use App\Producto;
use App\VentaItem;
use App\VentaCredito;
use App\TipoNotaCredito;

class CreateNCAjuste extends CreatorNCAbstract
{
  public function createItems()
  {
    $totales_item = [
      'precio_unitario' => 0,
      'valor_unitario'  => 0,
      'valor_noonorosa' => 0,
      'valor_venta_bruto' => 0,
      'valor_venta_por_item' => 0,
      'valor_venta_por_item_igv' => 0,
      'impuestos_totales' => 0,
    ];

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
    $it->DetNomb = TipoNotaCredito::DESCRIPCION_13_AJUSTES_FECHA_MONTO;
    $it->MarNomb = "SIN DEFINIR";
    $it->DetCant = "1";
    $it->DetPrec = 0;
    $it->DetPeso = 0;
    $it->DetEsta = "V";
    $it->DetEspe = 0;
    $it->lote = $totales_item;
    $it->DetCSol = 0;
    $it->DetCDol = 0;
    $it->DetVSol = 0;
    $it->DetVDol = 0;
    $it->DetSdCa = 0;
    $it->DetDcto = 0;
    $it->DetDctoV = 0;
    $it->Detfact = 0;
    $it->DetIGVV = get_igv();
    $it->DetIGVP = 0;
    $it->DetISC = 0;
    $it->DetISCP = 0;
    $it->icbper_value = 0;
    $it->icbper_unit = 0;
    $it->DetImpo = 0;
    $it->Estado  = $producto->TieCodi;
    $it->DetBase = $producto->BaseIGV;
    $it->incluye_igv  = $producto->incluye_igv;
    $it->DetPercP = 0;
    $it->TipoIGV = TipoIgv::DEFAULT_GRAVADA;
    $it->save();
  }

  public function getTotales()
  {
    return (object) [
      'igv' => 0,
      'descuento_total' => 0,
      'total_gravadas' => 0,
      'total_cobrado' => 0,
      'total_inafecta' => 0,
      'total_exonerada' => 0,
      'total_gratuita' => 0,
      'percepcion' => 0,
      'icbper' => 0,
      'isc' => 0,
      'total_cantidad' => 1,
      'total_base_percepcion' => 0,
      'total_base_isc' =>  0,
      'total_valor_bruto_venta' => 0,
      'total_valor_venta' => 0,
      'valor_venta_por_item_igv' => 0,
      'descuento_global' => 0,
      'retencion' => 0,
      'igv_porc' => get_igv(),
      'total_importe' => 0,
      'impuestos_totales' => 0,
      'total_peso' => 0
    ];
  }

  public function createDocumento()
  {
    $fechaVencimiento = end($this->data['cuotas']);

    $nc = $this->getDocumentoWithInitialData();
    $nc->ConCodi = FormaPago::CODIGO_CREDITO_GENERAL;
    $nc->TpgCodi = MedioPago::CODIGO_SINDEFINIR;
    $nc->VtaSdCa = 0;
    $nc->VtaFVen = $fechaVencimiento['fecha'];
    $nc->VtaSdCa = 0;
    $nc->VtaPedi = 0;
    $nc->VtaObse  = $this->data['motivo'];
    $nc->vtaadoc  =  TipoNotaCredito::CODE_13_AJUSTES_FECHA_MONTO;
    $nc->save();
    $this->nc = $nc;
  }

  public function createPagos()
  {
    $cuotas = $this->data['cuotas'];

    $index = 1;
    foreach ($cuotas as $cuota) {
      VentaCredito::create([
        'item' => agregar_ceros($index, 2, 1),
        'VtaOper' => $this->nc->VtaOper,
        'monto' => $cuota['monto'],
        'fecha_pago' => $cuota['fecha'],
        'forma_pago_id' => FormaPago::CODIGO_CREDITO_GENERAL,
        'MonCodi' => $this->nc->MonCodi,
      ]);
      $index++;
    }
  }
}
