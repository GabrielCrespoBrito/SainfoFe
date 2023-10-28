<?php

namespace App\Http\Controllers;

use App\Venta;
use App\Moneda;
use App\TipoPago;
use App\VentaPago;
use App\GuiaSalida;
use App\CajaDetalle;
use App\PDFPlantilla;
use Illuminate\Http\Request;
use App\Http\Requests\Pago\PagoUpdateRequest;
use App\Http\Requests\VentaPago\VentaPagoStoreRequest;
use App\Http\Requests\VentaPago\VentaPagoDestroyRequest;

class VentasPagosController extends Controller
{
  function __construct()
  {
    // $this->middleware(p_midd('A_INDEX', 'R_PAGO'))->only('pagos');
    // $this->middleware(p_midd('A_SHOW', 'R_PAGO'))->only('dataPago');
    // $this->middleware(p_midd('A_EDIT', 'R_PAGO'))->only('update');
  }

  /**
   * Ver los pagos que se han hecho a una venta
   * 
   * @return array
   */
  public function pagos(Request $request)
  {
    $this->authorize(p_name('A_INDEX', 'R_PAGO', 'R_VENTA'));

    $venta = Venta::find($request->id_factura);
    $data = $venta->getDataPayments();
    return response()->json($data);
  }

  public function paymentStatus(Request $request)
  {
    $this->authorize(p_name('A_CREATE', 'R_PAGO', 'R_VENTA'));

    $venta = Venta::find($request->id_factura);
    $data = $venta->getDataPayment();
    return $data;
  }

  public function show(Request $request, $id)
  {
    $this->authorize(p_name('A_SHOW', 'R_PAGO', 'R_VENTA'));

    $pago = VentaPago::with('nota_credito.cliente_with')->find($id);
    $data = $pago->toArray();
    $venta = $pago->venta;

    if ($nota_credito = $data['nota_credito']) {

      $id = $nota_credito['VtaOper'];

      $monto = $data['nota_credito']['VtaImpo'];
      $documento_cliente = $data['nota_credito']['cliente_with']['PCRucc'];
      $nombre_cliente = $data['nota_credito']['cliente_with']['PCNomb'];

      $text = sprintf(
        '%s (%s) (%s %s) %s',
        $data['nota_credito']['VtaNume'],
        $monto,
        $documento_cliente,
        $nombre_cliente,
        $pago['nota_credito']['VtaFvta']
      );

      $data['nota_credito'] = [];
      $data['nota_credito']['id'] = $id;
      $data['nota_credito']['text'] = $text;
    }

    $data['correlative'] = $data['PagBoch'];
    $data['fecha'] = $venta->VtaFvta;
    $data['saldo'] = $venta->VtaSald;
    $data['fecha_pago'] = $data['PagFech'];
    $data['moncodi'] = $data['MonCodi'];
    $data['tipocambio'] = $data['PagTCam'];
    $data['importe'] = $data['PagImpo'];

    /* fecha_pago */
    $data['id'] = $data['VtaOper'];
    $data['moneda'] = Moneda::getAbrev($data['MonCodi']);
    $data['por_pagar'] = $venta->VtaSald;
    $data['total'] = $venta->VtaImpo;
    return $data;
  }

  public function dataPago(Request $request)
  {
    $this->authorize(p_name('A_SHOW', 'R_PAGO', 'R_VENTA'));

    $pago = VentaPago::find($request->id_pago);
    $pago_data = $pago->toArray();
    $pago_data['VtaImpo'] = $pago->venta->VtaImpo;
    return $pago;
  }

  public function checkPago(Request $request)
  {
    $venta = Venta::find($request->id_factura);
    $data['venta'] = $venta;
    $data['pago'] = $venta->needPago();
    if ($data['pago']) {
      $data['PagOper'] = VentaPago::lastId();
      $data['moneda'] = $venta->moneda->monnomb;
      $data['is_efectivo'] = $venta->isFactura();
      $data['guia_data'] = GuiaSalida::getDataCreacion($venta);
    }
    return $data;
  }

  public function update(PagoUpdateRequest $request, $id)
  {
    $this->authorize(p_name('A_EDIT', 'R_PAGO', 'R_VENTA'));
    $pago = VentaPago::findOrfail($id);
    $pago->updateInfo($request);
    return response()->json(['message' => 'Pago modificado exitosamente']);
  }


  public function savePago(VentaPagoStoreRequest $request)
  {
    $this->authorize(p_name('A_CREATE', 'R_PAGO', 'R_VENTA'));
    $venta = Venta::find($request->VtaOper);
    $venta_pago = VentaPago::createPago($request);
    $venta_data = $venta_pago->toArray();
    $venta_data['moneda_abbre'] = $venta_pago->moneda_abbre();
    if (!$venta_pago->isTipoCredito()) {
      $tipoIngreso = in_array($request->tipopago,  TipoPago::getTipoBanco()) ? 'banco' : 'venta';
      $tipo_mov = $venta->isNotaCredito() ? 'S' : 'I';
      CajaDetalle::registrarIngreso($venta_pago, $tipoIngreso, $venta_pago['CajNume'], $request->all(), $tipo_mov);
    }

    $empresa = get_empresa();
    if( $empresa->hasVentaRapida() && $request->input('create_pdf', false) ){
      $formato = $request->input('formato_impresion', 'a4');
      $save = $formato == PDFPlantilla::FORMATO_A4;
      $serie = $venta->getSerie();
      $venta->generatePDF($formato, $save, true, $serie->impresion_directa);
      // $venta->update(['VtaEsta' => 'P']);
    }

    return  [
      'guia_data' => GuiaSalida::getDataCreacion($venta),
      'por_pagar' => $venta_pago->venta->VtaSald,
      'pago' => $venta_data,
    ];
  }

  public function removePago(VentaPagoDestroyRequest $request)
  {
    $this->authorize(p_name('A_DELETE', 'R_PAGO', 'R_VENTA'));
    $venta_pago = VentaPago::findOrfail($request->id_pago);
    $venta = $venta_pago->venta;
    $nota_credito = $venta_pago->nota_credito;
    $venta_pago->removeMovimiento();
    $venta_pago->delete();
    optional($nota_credito)->updateDeudaByPagoNotaCredito();

    return $venta
      ->updatedDeuda()
      ->saldo;
  }
}
