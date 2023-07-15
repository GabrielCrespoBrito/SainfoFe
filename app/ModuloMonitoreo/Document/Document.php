<?php

namespace App\ModuloMonitoreo\Document;

use App\ModuloMonitoreo\DocSerie\DocSerie;
use Illuminate\Database\Eloquent\Model;
use App\ModuloMonitoreo\DocStatus\DocStatus;

class Document extends Model
{
	protected $table = "monitor_empresa_documentos";
	protected $guarded = [];

	public function scopeEmpresa($value)
	{
		return $this->where('empresa_id', $value);
	}

	public function serie()
	{
		return $this->belongsTo( DocSerie::class, 'serie_id', 'id' );
	}

	public function updateEstadoBusqueda($state)
	{
		$this->update(['buscado_sunat' => (int) $state]);
	}

	public function status()
	{
		return $this->hasOne( DocStatus::class, 'documento_id', 'id' )->withDefault();
	}

	public function registerStatus($code)
	{
		$status = $this->status;
		$status->status_id = cacheHelper('docstatus.all')->where('status_code' , $code)->first()->id;
		$status->save();
	}
	public function buscadoEnSunat()
	{
		
		return (bool) $this->buscado_sunat;
	}

}
