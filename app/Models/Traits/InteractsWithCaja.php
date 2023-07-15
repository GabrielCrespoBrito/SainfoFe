<?php

namespace App\Models\Traits;

use App\VentaPago;

trait InteractsWithCaja
{

  public function getCajaMovimiento()
  {
    return $this->caja_detalle;
  }

  public function updateValues()
  {
    $this->cleanMontos();

    // Venta Pago
    if( $this instanceof VentaPago ){
      $this->setIngreso();
    }
    // Compra
    else {
      $this->setEgreso();
    }
  }

  public function cleanMontos()
  {
    $this->getCajaMovimiento()->cleanMontos();
  }

  public function getMonto()
  {
    // Venta Pago
    if ($this instanceof VentaPago) {
      $this->PagImpo;
    } 
    // Compra
    else {
      return $this->Cpatota;
    }    
    ;
  }

  public function setIngreso()
  {    
    $this->isSol() ? $this->setIngresoSol($this->getMonto()) : $this->setIngresoDolar($this->getMonto());
  }

  public function setEgreso()
  {
    $this->isSol() ? $this->setEgresoSol($this->getMonto()) : $this->setEgresoDolar($this->getMonto());
  }

  public function setIngresoSol($val)
  {
    $this->getCajaMovimiento()->update(['CANINGS' => $val ]);
  }

  public function setIngresoDolar($val)
  {
    $this->getCajaMovimiento()->update(['CANINGD' => $val ]);
  }

  public function setEgresoSol($val)
  {
    $this->getCajaMovimiento()->update([ 'CANEGRS' => $val ]);
  }		

  public function setEgresoDolar($val)
  {
    $this->getCajaMovimiento()->update(['CANEGRD' => $val]);
  }

}

