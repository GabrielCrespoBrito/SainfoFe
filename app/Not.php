<?php 

namespace App;

class Not {

	public static function mandar( $titulo = "", $mensaje = "", $tipo = "info" )
	{
		session()->flash( 'notificacion' , true );
		session()->flash( 'titulo'  		 , $titulo );
		session()->flash( 'mensaje' 		 , $mensaje );
		session()->flash( 'tipo'				 , $tipo );
	}

}
