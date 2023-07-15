<?php 

namespace App\Models\Venta;

use App\Models\Traits\InventaryHandler\InventaryHandler;

trait Inventary
{
	public function reduceInventary(){

		$pedido = $this->importedPedido();

		foreach ( $this->items as $item ) {
			$pedido ? $item->reduceReserved( $pedido ) : $item->reduceAlmacen();
		}
	}


	public function returnToInventary(){

		$pedido = $this->importedPedido();

		foreach ( $this->items as $item ) {
			$pedido ? $item->agregateReserved( $pedido ) : $item->agregateAlmacen();
		}
	}


}