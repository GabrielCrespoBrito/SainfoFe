<?php

namespace App\Models\Compra\Traits;

use App\Caja;
use App\BancoEmpresa;
use App\TipoPago;

trait CompraPagoRepository
{
  public static function create_( $data , $compra )
  {
    set_timezone();
    $cajNume = Caja::currentCaja(null, get_empresa()->isTipoCajaLocal())->CajNume;;
    $bancodi = "";
    $bannomb = "";
    $tpgCodi = $data['tipopago'];
    $moncodi = $data['moncodi'] ?? $data['moneda'] ?? '01';
    $otrodoc = '';
    $nota_credito_id = '';

    if ( in_array($data['tipopago'] , TipoPago::TYPE_BANCO )) {
      $banco_cuenta = BancoEmpresa::find($data['cuenta_id']);
      $bancodi = $banco_cuenta->BanCodi;
      $bannomb = $banco_cuenta->banco->bannomb;
      $otrodoc = $data['baucher'];
      $caja = Caja::where('CueCodi', $data['cuenta_id'])->firstOrfail();
      $cajNume = $caja->CajNume;
    }

    if ($data['tipopago'] == TipoPago::NOTACREDITO){
      $nota_credito_id = $data['nota_credito_id'];
    }
    
    $pago = new self;
    $data['PagOper'] =  $pago->getLastIncrement('PagOper');
    $data['CpaOper'] = $compra->CpaOper;
    $data['TpgCodi'] = $tpgCodi;
    $data['PagFech'] = date('Y-m-d');
    $data['PagTCam'] = $data['tipocambio'];
    $data['MonCodi'] = $moncodi;
    $data['PagImpo'] = $data['VtaImpo'];
    $data['BanCodi'] = $bancodi;
    $data['Bannomb'] = $bannomb;
    $data['CpaNume'] = $compra->CpaNume;
    $data['CpaFcpa'] = $compra->CpaFCpa;
    $data['CpaFVen'] = $compra->CpaFven;
    $data['PagBoch'] = '';
    $data['usufech'] = date('Y-m-d');
    $data['usuhora'] = date('H:m:i');
    $data['usucodi'] = auth()->user()->usucodi;
    $data['cajnume'] = $cajNume;        
    $data['ChePT'] = null;
    $data['ChePT'] = null;
    $data['CpaNCre'] = $nota_credito_id;    
    $data['EmpCodi'] = empcodi();
    $data['PanAno'] = date('Y');
    $data['PanPeri'] = date('Ym');
    $data['OTRODOC'] = $otrodoc;    
    $data['User_Crea'] = auth()->user()->usunomb;
    $data['User_ECrea'] = gethostname();
    return parent::create($data);
  }

  
}