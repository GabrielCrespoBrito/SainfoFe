<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActividadCliente extends Model
{
	public $table = 'actividad_clientes';
	public $timestamps = false;

	const SALIR = [ 'Salir del sistema'];
	const ACCESO = ['Acceso al sistema'];
	const DESCARGA = ['Descarga'];

	const INFO_ACTIONS = [
		"SALIR" => [ 
			'nombre' => 'Salir del sistema' ,
			'descripcion' => 'El Cliente ha salido del sistema del sistema'
		],

		"ACCESO" => ['Acceso al sistema'],
		"DESCARGA" => ['Descarga'],
	];

	

	public function cliente()
	{
		return $this->belongsTo( ClienteProveedor::class, 'PCCodi', 'PCCodi' );
	}

	public static function RegistrarDescarga( $archivos , $id_venta  )
	{
		set_timezone();		
		$actividad = new self;
		$actividad->PCCodi = session()->get('PCCodi');
		$actividad->Nombre = "Descarga";
		$archivos = implode(",", $archivos);
		$actividad->Descripcion = "Ha descargado el documento los archivos $archivos";
		$actividad->Fecha = date('Y:m:d H:i:s');
		$actividad->Model_id = $id_venta;
		$actividad->Model_name = "App\Venta";
		$actividad->save();
	}

	public static function accesoSistema()
	{
		set_timezone();		
		$actividad = new self;
		$actividad->PCCodi = session()->get('PCCodi');
		$actividad->Nombre = "Acceso al sistema";
		$actividad->Descripcion = "El Cliente ha ingresado a las sistema";
		$actividad->Fecha = date('Y:m:d H:i:s');
		$actividad->save();
	}


	public static function salirSistema()
	{
		set_timezone();		
		$actividad = new self;
		$actividad->PCCodi = session()->get('PCCodi');
		$actividad->Nombre = "Salir del sistema";
		$actividad->Descripcion = "El Cliente ha salido del sistema del sistema";
		$actividad->Fecha = date('Y:m:d H:i:s');
		$actividad->save();
	}


}
