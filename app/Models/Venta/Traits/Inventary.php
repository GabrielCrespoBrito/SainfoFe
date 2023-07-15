<?php 

namespace App\Models\Venta\Traits;

trait Inventary
{
	public function reduceInventary(){

		$pedido = $this->importedPedido();

		foreach ( $this->items as $item ) {
			$pedido ? $item->reduceReserved( $pedido ) : $item->reduceAlmacen();
		}

		if( $pedido ){
			$pedido->setCerradoState();
		}
	}


	public function returnToInventary(){

		$pedido = $this->importedPedido();

		foreach ( $this->items as $item ) {			
			$pedido ? $item->agregateReserved($pedido) : $item->agregateAlmacen();
		}

		if( $pedido ){
			$pedido->setPendienteState();
		}

	}

}
