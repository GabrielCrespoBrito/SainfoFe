<?php 

namespace App\Models\Venta\Relationship;

use App\Zona;
use App\Venta;
use App\Cotizacion;
use App\Detraccion;
use App\VentaCanje;
use App\VentaCredito;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\Models\Contingencia\ContingenciaDetalle;

trait VentaRelationship
{
	public function importacion()
	{
	  return Cotizacion::findByNume($this->VtaPedi, $this->EmpCodi);
	}
  
  public function canje()
  {
    return $this->hasOne(
      VentaCanje::class,
      'VtaOperCanje',
      'VtaOper'
    );
  }

	public function contingenciaDetalle()
	{
		return $this->belongsTo( ContingenciaDetalle::class , 'VtaOper' , 'vtaoper' )->where('empcodi' , $this->EmpCodi);
	}

	public function detraccion()
	{
		return $this->belongsTo( Detraccion::class, 'VtaDetrCode' ,'cod_sunat' );
	}

	/**
	 * Documento referencia, relacionado
	 */
	public function docReferencia()
	{
		return $this->belongsTo(Venta::class)->where('empcodi', $this->EmpCodi);
	}

	/**
	 * Creditos
	 */
	public function creditos()
	{
		return $this->hasMany( VentaCredito::class, 'VtaOper', 'VtaOper ');
	}

  public function zona()
  {
    return $this->belongsTo(Zona::class, 'ZonCodi', 'ZonCodi ');
  }

  public function getZona()
  {
    return optional(Zona::find($this->ZonCodi));
  }

	public function getCreditos()
	{ 
		return VentaCredito::where('VtaOper', $this->VtaOper)->get();
	}

	public function statusSunat()
	{
		return $this->belongsTo( StatusCode::class, 'VtaFMail' , 'status_code' );
	}
}