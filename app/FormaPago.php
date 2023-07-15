<?php

namespace App;

use App\Jobs\FormaPago\Create;
use App\Util\ModelUtil\ModelUtil;
use Database\Seeds\FormaPagoSeed;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\FormaPagoRepository;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class FormaPago extends Model
{
  use
  UsesTenantConnection,
  ModelEmpresaScope,
  ModelUtil;

  protected $table = 'condicion';  
  protected $keyType = 'string';
  protected $primaryKey = "conCodi";
  protected $fillable = ['connomb','condias'];
  public $timestamps   = false;
  const EMPRESA_CAMPO = "empcodi";
  /**
   * @TODO- cuando se cree, cambiar a 001
   */

  const SYSTEM_TYPE = 1;
  const CODIGO_CREDITO_GENERAL = "011";
  const CODIGO_CONTABLE_GENERAL = "01"; 
  const TIPO_CONTADO = "C";
  const TIPO_CREDITO = "D";
  const TIPO_DIFERIDO = "D";

  //
  const FORMA_PAGO_CONTADO_NOMBRE = "contado";
  const FORMA_PAGO_CREDITO_NOMBRE = "credito";

  const DATA_FORMA_PAGO =
  [
    [ 'conCodi' => '01', 'connomb' => 'CONTADO', 'condias' => '0', 'contipo' => 'C' ],
    [ 'conCodi' => '02', 'connomb' => 'CREDITO 7 DIAS', 'condias' => '7', 'contipo' => 'D' ],
    [ 'conCodi' => '03', 'connomb' => 'CREDITO 15 DIAS', 'condias' => '15', 'contipo' => 'D' ],
    [ 'conCodi' => '04', 'connomb' => 'CREDITO 30 DIAS', 'condias' => '30', 'contipo' => 'D' ],
    [ 'conCodi' => '05', 'connomb' => 'CREDITO 45 DIAS', 'condias' => '45', 'contipo' => 'D' ],
    [ 'conCodi' => '06', 'connomb' => 'CREDITO 60 DIAS', 'condias' => '60', 'contipo' => 'D' ],
    [ 'conCodi' => '07', 'connomb' => 'LETRA 15-30-60', 'condias' => '60', 'contipo' => 'D' ],
    [ 'conCodi' => '09', 'connomb' => 'CHEQUE', 'condias' => '0', 'contipo' => 'C' ],
    [ 'conCodi' => '10', 'connomb' => 'TARJETA', 'condias' => '0', 'contipo' => 'C' ],
    [ 'conCodi' => '11', 'connomb' => 'LETRA 30 - 45', 'condias' => '45', 'contipo' => 'D' ],
  ];

  const DIAS_FORMA_PAGO =
  [
    [ 'PgoCodi' => '01', 'PgoDias' => 0,  'conCodi' => '01' ],
    [ 'PgoCodi' => '02', 'PgoDias' => 7,  'conCodi' => '02' ],
    [ 'PgoCodi' => '03', 'PgoDias' => 15, 'conCodi' => '03' ],
    [ 'PgoCodi' => '04', 'PgoDias' => 30, 'conCodi' => '04' ],
    [ 'PgoCodi' => '05', 'PgoDias' => 45, 'conCodi' => '05' ],
    [ 'PgoCodi' => '06', 'PgoDias' => 60, 'conCodi' => '06' ],
    [ 'PgoCodi' => '07', 'PgoDias' => 15, 'conCodi' => '07' ],
    [ 'PgoCodi' => '08', 'PgoDias' => 30, 'conCodi' => '07' ],
    [ 'PgoCodi' => '09', 'PgoDias' => 60, 'conCodi' => '07' ],
    [ 'PgoCodi' => '10', 'PgoDias' => 0,  'conCodi' => '09' ],
    [ 'PgoCodi' => '11', 'PgoDias' => 0,  'conCodi' => '10' ],
    [ 'PgoCodi' => '12', 'PgoDias' => 30, 'conCodi' => '11' ],
    [ 'PgoCodi' => '13', 'PgoDias' => 45, 'conCodi' => '11' ],
  ];


  public static function getCreditoGeneral() 
  {

  }


  public static function createDefault($empcodi)
  {
    self::unguard();

    $formas_pagos = self::DATA_FORMA_PAGO;
    $formas_pagos_dias = self::DIAS_FORMA_PAGO;


    // Guardar la Forma de Pago
    foreach ($formas_pagos as $forma_pago) {

      $forma_pago['empcodi'] = $empcodi;
      self::create($forma_pago);
    }
    // Strub
    // Guardar el dia de la Forma de Pago
    foreach ($formas_pagos_dias as $forma_pago_dia) {
      $forma_pago_dia['empcodi'] = $empcodi;
      CondicionDias::forceCreate($forma_pago_dia);
    }

  }


  public function getIdAttribute()
  {
    return $this->conCodi;
  }

  public function getDescripcionAttribute()
  {
    return $this->connomb;
  }
  
  public function setConnombAttribute($value)
  {
    $this->attributes['connomb'] = strtoupper($value);
  }

  public function ventas()
  {
    return $this->hasMany( Venta::class, 'ConCodi', 'conCodi' );
  }


  public function isContado()
  {
    return $this->contipo == self::TIPO_CONTADO;
  }

  public function isCredito()
  {
    return $this->contipo == self::TIPO_DIFERIDO;
  }


  public function hasMultipleDias()
  {
    return $this->dias->count() > 1;
  }


  public function dias()
  {
    return $this->hasMany( CondicionDias::class, 'ConCodi' , 'conCodi' );
  }

  public function repository()
  {
    return new FormaPagoRepository($this);
  }


  public function saveDias($items, $create = true, $code = null )
  {
    $code = $code ?? $this->conCodi;

    # Crear los dias
    $currentCode = null;
    foreach ( $items as $dia ) {

      $currentDiasIsNew = $create ?
        true :
        is_null($dia['PgoCodi']);

      $condicion_dias = $currentDiasIsNew ?  new CondicionDias() : CondicionDias::find($dia['PgoCodi']);
      $condicion_dias->PgoDias = $dia['PgoDias'];

      if( $currentDiasIsNew ){

        $currentCode = is_null($currentCode) ? 
        agregar_ceros( CondicionDias::max('PgoCodi'), 2, 1) : 
        agregar_ceros($currentCode, 2, 1);

        $condicion_dias->PgoCodi = $currentCode;
        $condicion_dias->ConCodi = $code;
        $condicion_dias->empcodi = $this->empcodi;
      }

      $condicion_dias->save();
    }
  }

  public function isGeneralCredito()
  {
    return $this->conCodi == FormaPago::CODIGO_CREDITO_GENERAL;
  }

  public function isSystem()
  {
    return $this->system == FormaPago::SYSTEM_TYPE;
  }
  
  public function deleteDias( $items = null )
  {
    $dias = $this->dias;

    if( $items ){
      $dias = $dias->whereNotIn('PgoCodi' , $items->pluck('PgoCodi')->toArray())->all();
    }

    foreach( $dias as $dia ){
      $dia->delete();
    }
  }

}