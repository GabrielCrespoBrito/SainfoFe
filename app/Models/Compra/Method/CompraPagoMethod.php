<?php

namespace App\Models\Compra\Method;

use App\Caja;
use App\Moneda;
use App\CajaDetalle;
use App\BancoEmpresa;
use App\Repositories\TipoPagoRepository;
use App\TipoPago;
use Illuminate\Support\Facades\Request;

trait CompraPagoMethod
{
  public function getDataFormat()
  {
    $cajaAperturada = (int) $this->caja->isAperturada();
    return
    [
      "id"      => $this->PagOper,
      "fecha"   => $this->PagFech,
      'tipopago'=> $this->tipo_pago->descripcion,
      "moneda"  => $this->moneda->monabre,
      "tcambio" => $this->PagTCam,
      "importe" => decimal($this->PagImpo),
      "modify"  => $cajaAperturada,
      "delete"  => $cajaAperturada,
    ];
  }

  public function isBancario()
  {
    return (bool) $this->Bannomb;
  }

  public function getDocumento()
  {
    return $this->compra;
  }

  public function removeMovimiento()
  {
    $cajMov = $this->caja_movimiento;
    
    if ( is_null($cajMov) ) return;
    
    $caja = $cajMov->caja;

    if( $caja->isAperturada() ){
      $cajMov->delete();
    }

    else {
      $cajMov->deleteState();
      $caja = Caja::currentCaja();
      CajaDetalle::eliminarCompraPago($this, $caja);
    }

    $caja->calculateSaldo();
  }

  /**
   * Si la moneda del padre del padre es la misma
   * 
   * @return bool
   */
  public function isSameMonedaParent(): bool
  {
    return $this->MonCodi === $this->compra->moncodi;
  }

  /**
   * Obtener el importe total expresado en la moneda del padre
   * 
   * @return float
   */
  public function getRealImporte()
  {
    if ( $this->isSameMonedaParent()) {
      return $this->PagImpo;
    }

    return $this->isDolar() ? ($this->PagImpo * $this->PagTCam) : ($this->PagImpo / $this->PagTCam);
  }

  /**
   * Actualizar la información del pago
   *
   * @param Illuminate\Support\Facades\Request $request
   * @return void
   */ 
  public function updateInfo($request)
  {
    $compra = $this->compra;
    
    // Información de update      
    $dataUpdate = [
      'PagTCam' => $request->tipocambio,
      'MonCodi' => $request->moneda,
      'PagImpo' => $request->VtaImpo,
      'PagTCam' => $request->tipocambio,
      'User_FModi' => date('Y-m-d'),
      'User_Modi' => auth()->user()->usucodi
    ];

    // Actualizar si es bancario
    if( $this->isBancario() ){
      $banco_cuenta = BancoEmpresa::find($request->cuenta_id);
      $dataUpdate['BanCodi'] = $banco_cuenta->BanCodi;
      $dataUpdate['Bannomb'] = $banco_cuenta->banco->bannomb;
      $dataUpdate['VtaNume'] = $request->baucher;
    } else if ($this->isNotaCredito()) {
      $nota_credito_old = $this->nota_credito;
      $dataUpdate['CpaNCre'] = $request->nota_credito_id;
    }


    $this->fill($dataUpdate);

    if ($isUpdate = $this->isDirty(['PagTCam', 'MonCodi', 'PagImpo', 'BanCodi'])) {

      # Actualizar
      $this->removeMovimiento();
      $this->save();
      CajaDetalle::storeCompra($this, $request, $compra->isNotaCredito());
      $this->compra->isNotaCredito() ? $this->compra->updateDeudaByPagoNotaCredito() : $this->compra->updateSaldo();
    }


    if ($this->isNotaCredito()) {
      $modifyNC = $nota_credito_old->CpaOper != $this->CpaNCre;

      if ($modifyNC && !$isUpdate) {
        $this->save();
      }

      if ($modifyNC) {
        $nota_credito_old->updateDeudaByPagoNotaCredito();
        $this->refresh()->nota_credito->updateDeudaByPagoNotaCredito();
      } else if ($isUpdate) {
        $this->nota_credito->updateDeudaByPagoNotaCredito();
      }
    }
  }

  
  public function montoValidActualizacion( $valor , $moneda_id, $tCambio = null )
  {
    $valorReal =  0;

    // Si el pago de la misma moneda
		if( $moneda_id == $this->MonCodi ){
      if( $valor > $this->PagImpo ){
        $valorReal = $valor - $this->PagImpo; 
        // return $this->getDocumento()->montoPagoValid(  $valorReal, $moneda_id, $tCambio);
      }
    }

    else {
      if( $moneda_id == Moneda::DOLAR_ID ){
      }
      else {
      }
    }

    return $this->getDocumento()->montoPagoValid( $valorReal, $moneda_id, $tCambio);

    return true;
  }


  public function docMismaCaja()
  {
    return $this->compra->CajNume == $this->CajNume;
  }

  public function isNotaCredito()
  {
    return $this->TpgCodi == TipoPago::NOTACREDITO;
  }

  /**
   * Actualizar el saldo de la nota de credito
   */

  public function updateNotaCredito()
  {
    $this->nota_credito->updateDeudaByPagoNotaCredito();
  }
}