<?php

namespace App;

use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\TenantAwareConnection;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class ListaPrecio extends Model
{
  use
  UsesTenantConnection,
  ModelEmpresaScope;

  const EMPRESA_CAMPO = "empcodi";

  const LIMIT_PRECIO_MINIMO_TRUE = 1;
  const LIMIT_PRECIO_MINIMO_FALSE = 0;

  protected $table       = 'lista_precio';
  public $timestamps   = false;
  protected $primaryKey = 'LisCodi';
  public $keyType     = 'string';
  protected $guarded = [];

  public function getLisCodiAttribute($value)
  {
    return is_null($value)  ? agregar_ceros(self::where('empcodi', empcodi())->max('LisCodi'), 2) : $value;
  }

  public static function createDefault($empcodi)
  {
    $lisCodi = '001';
    $locCodi = '001';

    self::create([
      'LocCodi' => $locCodi ,
      'LisCodi' => $lisCodi,
      'LisNomb' => 'PUBLICO',
      'empcodi' => $empcodi,
    ]);

    return $lisCodi;
  }

  public function local()
  {
    return $this->belongsTo(Local::class, 'LocCodi', 'LocCodi');
  }


  public static function getId()
  {
    $lastId = self::max('LisCodi');
    $ceroLength = strlen($lastId);
    return agregar_ceros(self::max('LisCodi'), $ceroLength );
  }

  public function limitPrecioMinimo()
  {
    return $this->limit_dism_precio == self::LIMIT_PRECIO_MINIMO_TRUE;
  }

  public function toggleLimit()
  {
    $this->update([
      'limit_dism_precio' => $this->limitPrecioMinimo() ?
      self::LIMIT_PRECIO_MINIMO_FALSE :
      self::LIMIT_PRECIO_MINIMO_TRUE
    ]);
  }

  public static function removeLimitAll()
  {
      ListaPrecio::query()->update([
        'limit_dism_precio' => ListaPrecio::LIMIT_PRECIO_MINIMO_FALSE
      ]);
  }
  
}