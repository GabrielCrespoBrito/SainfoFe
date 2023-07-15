<?php

namespace App\Jobs\Banco;

use App\BancoEmpresa;
use App\Caja;
use App\CajaDetalle;
use App\MotivoIngreso;

class RegisterIngreso
{
  public $data = [];
  public $cajaDetalle;
  public $isFromBanco;
  public $banco;
  public $montos;

  public function __construct(CajaDetalle $cajaDetalle, $bancoId = null)
  {
    $this->cajaDetalle = $cajaDetalle;
    $this->isFromBanco = $this->cajaDetalle->isTipoBanco();
    $this->banco = BancoEmpresa::find($bancoId);

    $this->setMontos();
  }

  public function setMontos()
  {
    $bancoIsDolar = $this->banco->isDolar();
    $montoInicialSoles = (float) $this->cajaDetalle->CANEGRS;
    $montoInicialDolares = (float) $this->cajaDetalle->CANEGRD;
    $tc = (float) $this->cajaDetalle->TIPCAMB;

    # Convertir los montos
    if($montoInicialSoles){
      $montoInicialDolares = $montoInicialSoles / $tc;
    }
    else {
      $montoInicialSoles = $montoInicialDolares * $tc;
    }
    
    $this->montos = (object) [
      'dolares' => $bancoIsDolar ? $montoInicialDolares : 0,
      'soles' => $bancoIsDolar ? 0 : $montoInicialSoles,
    ];

  }

  public function getNombre()
  {
    $caja = $this->cajaDetalle->caja;

    $nombre = $this->isFromBanco ? 'BANCO' : 'CAJA';

    if ($this->isFromBanco) {
      $bancoFrom = $caja->bancoCuenta;
      $id = sprintf("(%s) - %s", $bancoFrom->banco->bannomb,  $bancoFrom->CueNume);
    } 

    else {
      $id = $caja->CajNume;
    }

    
    return sprintf("TRANSF. DESDE %s %s", $nombre, $id );
  }

  public function handle()
  {
    $data = $this->cajaDetalle->toArray();
    $data["Id"] = (new CajaDetalle())->getLastIncrement('Id');
    $data["CueCodi"] = $this->banco->CueCodi;
    $data["CajNume"] = $this->banco->cajaAperturada()->CajNume;
    $data["CANINGS"] = $this->montos->soles;
    $data["CANINGD"] = $this->montos->dolares;
    $data["CANEGRS"] = 0;
    $data["CANEGRD"] = 0;
    $data["ANULADO"] = CajaDetalle::TIPO_INGRESO;
    $data["TIPMOV"]  = 'INGRESO';
    $data["MOTIVO"]  = $this->getNombre();
    $data["EgrIng"]  = MotivoIngreso::SIN_DEFINIR;
    $data["MocNomb"] = $this->getNombre();
    $cajaDetalle = CajaDetalle::create($data);
    $cajaDetalle->setMocCorrelative();
  }
}
