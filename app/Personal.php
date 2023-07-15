<?php

namespace App;

use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\TenantAwareConnection;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
  use 
  UsesTenantConnection,
  ModelEmpresaScope;

  protected $table       = 'rh_personal';  
  public $timestamps   = false;
  protected $primaryKey = 'RHPCodi';
  protected $keyType = 'string';
  
  const EMPRESA_CAMPO = "empcodi";
}