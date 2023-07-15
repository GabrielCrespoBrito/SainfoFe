<?php

namespace App\Models\Traits\InventaryHandler;

use App\Compra;
use App\CompraItem;
use App\Cotizacion;

class Reducer
{
	const INVENTARY_DEFAULT = "default";
	const INVENTARY_RESERVA = "reserved";

	protected $item;
	protected $quantity;

	public function __construct( $item , $quantity = false ){
		$this->item = $item;
		$this->quantity = $quantity;
		$this->getReduceSite();
	}

	public function setSiteWork( $site ){
		$this->site = $site;
	}


	public function getReduceSite(){
		if( $item instanceof CompraItem ){
			$reducer_site = self::INVENTARY_DEFAULT;
		}
		elseif( $item instanceof Cotizacion  ){
			$reducer_site = self::INVENTARY_RESERVA;			
		}
	}


	public function make( $item ){
	}

}
