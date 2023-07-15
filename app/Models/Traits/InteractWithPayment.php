<?php

namespace App\Models\Traits;

trait InteractWithPayment
{

  public function getDataPayments()
  {
    $payments = [];

    /**
     * Deuda Saldada
     * 
     */
    foreach( $this->pagos as $pago ){
      array_push( $payments , $pago->getDataFormat() );
    }

 


    $data = $this->getDataPayment();
    $data['payments'] = $payments;

    return $data;
  }

  public function getDataPayment()
  {
    $moneda = $this->moneda;

    return [
      'create_new' => (int)  !$this->deudaSaldada(),
      'correlative' => $this->correlative,
      'id' => $this->id,
      'total' => decimal($this->total),
      'saldo' => decimal($this->saldo),
      'moneda' => $moneda->monnomb,
      'moncodi' => $moneda->moncodi,
      'fecha' => $this->fecha,
      'tipocambio' => $this->tipocambio,
    ];

  }



  




}

