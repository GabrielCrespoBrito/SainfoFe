<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
	protected $table = "cargo";
	protected $primaryKey = "CarCodi";

	const MASTER 				= "MASTER";
	const ADMINISTRADOR = "ADMINISTRADOR";
	const VENTAS 				= "VENTAS";
	const CONTADOR 			= "CONTADOR";
	const ALMACEN 			= "ALMACEN";

	public function isMaster(){
		return $this->CarNomb === self::MASTER;
	}
	public function isAdmin(){
		return $this->CarNomb === self::ADMINISTRADOR;
	}

}
