<?php 

namespace App\Models\Cotizacion\Traits;

use App\Cotizacion;

trait handleStates
{
	public function setPendienteState(){
		$this->saveState( Cotizacion::STATE_PENDIENTE );
	}
  
  public function stateName()
  {
    return Cotizacion::STATES_NAMES[$this->cotesta];
  }
  

	public function setCerradoState(){
		$this->saveState( Cotizacion::STATE_CERRADA );
	}

  public function setFacturadoState($vtaOper = null)
  {
    $this->saveState(Cotizacion::STATE_FACTURADO, $vtaOper);
  }

  public function setAnuladoState()
  {
    $this->saveState(Cotizacion::STATE_ANULADO);
  }

  public function isAnulado()
  {
    return $this->isState(Cotizacion::STATE_ANULADO);
  }

  public function isFacturado()
  {
    return $this->isState(Cotizacion::STATE_FACTURADO);
  }

  public function isPendiente()
  {
    return $this->isState(Cotizacion::STATE_PENDIENTE);
  }

  public function isState($state)
  {
    return $this->cotesta == $state;
  }

	protected function saveState( $state, $vtaOper = null ){
		$this->cotesta = $state;
    if( $vtaOper ){
      $this->VtaOper = $vtaOper;
    }
		$this->save();
	}

}