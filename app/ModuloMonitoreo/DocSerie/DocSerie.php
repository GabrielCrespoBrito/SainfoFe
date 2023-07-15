<?php

namespace App\ModuloMonitoreo\DocSerie;

use App\TipoDocumentoPago;
use Illuminate\Database\Eloquent\Model;
use App\ModuloMonitoreo\Document\Document;

class DocSerie extends Model
{
	protected $table = "monitor_empresa_series";
	protected $fillable = ['tipo_documento', 'serie'];
	public $timestamps = false;

	public function tipodocumento()
	{
		return $this->belongsTo(TipoDocumentoPago::class, 'tipo_documento', 'TidCodi');
	}

	public function descripcionFull()
	{
		return $this->tipodocumento->TidNomb  . ' | ' . $this->serie;
	}

	public function getdFAttribute()
	{
		return $this->descripcionFull();
	}

	
	public function documents()
	{
		return $this->hasMany(Document::class, 'serie_id', 'id');
	}

}
