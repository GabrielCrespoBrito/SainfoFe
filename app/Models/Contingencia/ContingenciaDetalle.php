<?php

namespace App\Models\Contingencia;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Contingencia\Method\ContingenciaDetalleMethod;
use App\Models\Contingencia\Relationship\ContingenciaDetalleRelationship;

class ContingenciaDetalle extends Model
{
	use
		UsesTenantConnection,
		ContingenciaDetalleMethod,
		ContingenciaDetalleRelationship;

	protected $table = "contingencias_detalles";
	protected $guarded = [];
	public $timestamps = false;
}