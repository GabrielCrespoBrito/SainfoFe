<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorSunat extends Model
{
	protected $table = 'fa_errores';
	protected $primaryKey = 'id';
	public $incrementing = false;
	public $timestamps = false;
	public $keyType = "string";

	public static function getErrorDescripcion($error_code){
		$errorCode = (int) $error_code;
		$error = self::find($error_code);
		return is_null($error) ? "" : $error->nombre;
	}

}

