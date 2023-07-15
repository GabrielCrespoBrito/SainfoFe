<?php

namespace App\Models\GuiaVenta;

use App\GuiaSalida;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class GuiaVenta extends Model
{
	use UsesTenantConnection;

	protected $table = "guias_ventas";
	protected $fillable = ['VtaOper', 'GuiOper'];


  public function getRouteEdit()
  {
    return route('guia.edit', $this->GuiOper);
  }

  
	public static function associate($vtaoper,  $guiaoper)
	{
		self::create([
			'VtaOper' => $vtaoper,
      'GuiOper' => $guiaoper,
		]);
	}

	public function guia()
	{
		return $this->belongsTo( GuiaSalida::class, 'GuiOper', 'GuiOper' );
	}

}
