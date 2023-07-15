<?php

namespace App\Models\Compra;

use Illuminate\Database\Eloquent\Model;
use App\Util\ModelUtil\ModelEmpresaScope;
use App\Models\Compra\Method\CompraPagoMethod;
use App\Models\Compra\Traits\CompraPagoRepository;
use App\Models\Compra\Attribute\CompraPagoAttribute;
use App\Models\Compra\Relationship\CompraPagoRelationship;
use App\Models\Traits\InteractWithMoneda;
use App\Util\ModelUtil\ModelUtil;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class CompraPago extends Model
{
  use 
  InteractWithMoneda,
  ModelUtil,
  UsesTenantConnection,
  CompraPagoMethod,
  ModelEmpresaScope,
  CompraPagoAttribute,
  CompraPagoRepository,
  CompraPagoRelationship;
  
  protected $table = "compras_pago";
  protected $primaryKey = "PagOper";
  protected $keyType = "string";
  public $incrementing = false;
  const EMPRESA_CAMPO = 'EmpCodi';
  public $timestamps = false;
  public $PagOperCero = 6; 
  protected $fillable = [
    'PagOper', 'CpaOper', 'TpgCodi', 'PagFech', 'PagTCam', 'MonCodi', 'PagImpo', 'BanCodi', 'Bannomb', 'CpaNume', 'CpaFcpa', 'CpaFVen', 'PagBoch', 'usufech', 'usuhora', 'usucodi', 'cajnume', 'ChePT', 'CpaNCre', 'EmpCodi', 'PanAno', 'PanAno', 'User_Crea','User_ECrea',
  ];
}