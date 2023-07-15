<?php

namespace App\Http\Controllers\Compra;

use App\Venta;
use App\Compra;
use App\Moneda;
use App\VentaPago;
use App\GuiaSalida;
use App\CajaDetalle;
use Illuminate\Http\Request;
use App\Models\Compra\CompraPago;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CajasController;
use App\Http\Requests\Pago\PagoUpdateRequest;
use App\Http\Requests\CompraPago\CompraPagoStoreRequest;
use App\Http\Requests\CompraPago\CompraPagoDeleteRequest;

class CompraPagoController extends Controller
{
  public function __construct()
  {
  }
  /**
   * Ver los pagos que se han hecho a una compra
   * 
   * @return array
   */
  public function payments($id)
  {
    $this->authorize(p_name('A_INDEX', 'R_PAGO', 'R_COMPRA'));

    $compra = Compra::find($id);
    $data = $compra->getDataPayments();
    return response()->json($data);
  }
  
  public function paymentStatus(Request $request)
  {
    $this->authorize(p_name('A_CREATE', 'R_PAGO', 'R_COMPRA'));

    $compra = Compra::find($request->id_factura);
    $data = $compra->getDataPayment();
    return $data;
  }


  public function paymentsx(Request $request)
  {
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


  public function show(Request $request, $id)
  {
    $this->authorize(p_name('A_SHOW', 'R_PAGO', 'R_COMPRA'));

    $pago = CompraPago::find($id);
    $data = $pago->toArray();
    $compra = $pago->compra;
    $data['correlative'] = $data['CpaNume'];
    $data['create_new'] = 1;
    $data['fecha'] = $compra->CpaFCon;
    $data['id'] = $data['CpaOper'];
    $data['moncodi'] = $data['MonCodi'];
    $data['moneda'] = Moneda::getAbrev($data['MonCodi']);
    $data['importe'] = math()->addDecimal($data['PagImpo'], 2);
    $data['saldo'] = math()->addDecimal($compra->CpaSald, 2);
    $data['tipocambio'] = math()->addDecimal($data['PagTCam'], 2);
    $data['total'] = math()->addDecimal($compra->CpaImpo, 2);
    return $data;
  }

  public function store(CompraPagoStoreRequest $request)
  {
    $this->authorize(p_name('A_CREATE', 'R_PAGO', 'R_COMPRA'));

    $compra = Compra::find($request->VtaOper);
    $compra_pago = CompraPago::create_($request->all(), $compra);
    $compra->isNotaCredito() ? $compra->updateDeudaByPagoNotaCredito() : $compra->updateSaldo();

    if ($compra_pago->isNotaCredito()) {
      $compra_pago->updateNotaCredito();
    } else {
      CajaDetalle::storeCompra($compra_pago, $request, $compra->isNotaCredito());
    }

    return response()->json(['message' => 'Pago creado satisfactoriamente']);
  }

  public function update(PagoUpdateRequest $request, $id)
  {
    $this->authorize(p_name('A_EDIT', 'R_PAGO', 'R_COMPRA'));

    $pago = CompraPago::findOrfail($id);
    $pago->updateInfo($request);
    return "success";
  }


  public function delete(CompraPagoDeleteRequest $request)
  {
    // $this->authorize(p_name('A_DELETE', 'R_PAGO', 'R_COMPRA'));

    $compra_pago = CompraPago::find($request->id_pago);
    $compra = $compra_pago->compra;    
    $compra_pago->removeMovimiento();
    $compra_pago->delete();

    // updateDeudaByPagoNotaCredito

    $compra->isNotaCredito() ? $compra->updateDeudaByPagoNotaCredito() : $compra->updateSaldo();

    if ($compra_pago->isNotaCredito()) {
      $compra_pago->updateNotaCredito();
    }

    return $compra->saldo;
  }
}
