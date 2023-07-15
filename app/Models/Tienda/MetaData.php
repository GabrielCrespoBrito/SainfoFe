<?php

namespace App\Models\Tienda;

use Illuminate\Database\Eloquent\Model;

class MetaData extends Model
{
  protected $connection = 'mysql_tienda';
	protected $table = 'wp_postmeta';
	protected $primaryKey = 'meta_id';
	public $timestamps = false;
	public $fillable = ['meta_key', 'meta_value'];	
	const COTIZACION_ID = 'docref_cotizacion_id';

}
