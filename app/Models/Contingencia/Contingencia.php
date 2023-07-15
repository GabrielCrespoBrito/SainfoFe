<?php

namespace App\Models\Contingencia;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contingencia\Scope\ContingenciaScope;
use App\Models\Contingencia\Method\ContingenciaMethod;
use App\Models\Contingencia\Attribute\ContingenciaAttribute;
use App\Models\Contingencia\Relationship\ContingenciaRelationship;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Contingencia extends Model
{
	use ContingenciaAttribute,
	ContingenciaRelationship,
	UsesTenantConnection,
	ContingenciaMethod,
	ModelTrait,
	ContingenciaScope;

	const IDENTIFICACION = "RF";
	protected $table = "contingencias_cab";	
	public $timestamps = false;
	protected $guarded = [];	

}
