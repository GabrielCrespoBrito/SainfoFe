<?php

namespace App;

use App\Repositories\TipoPagoRepository;
use App\Util\ModelUtil\ModelUtil;
use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
  use ModelUtil;
  protected $table       = 'tipo_pago';
  protected $connection = 'mysql';  
  protected $primaryKey   = "TpgCodi";  
  protected $keyType   = "string";
  public    $timestamps = false;
  protected $descripcionKey = "TpgNomb";
  public    $guarded = [];
  
  const EFECTIVO = "EF";
  const TYPE_EFECTIVE = ["00", "01"];
  const TYPE_BANCO = ["02", "03", "04", "05"];
  const TYPE_NOTACREDITO = ["06"];
  const NOTACREDITO = "06";
  const IS_BANCARIO = "1";
  const NO_BANCARIO = "0";
  
  public function repository()
  {
    return new TipoPagoRepository($this);
  }

  public static function getTipoBanco()
  {
    return self::where( 'TdoBanc' , self::IS_BANCARIO )->pluck('TpgCodi')->toArray();
  }

  public static function isTipoBanco( $tipo )
  {
    return in_array($tipo, self::getTipoBanco());
  }

  public function getNombre()
  {
    return "{$this->TpgCodi}  {$this->TpgNomb}"; 
  }

  public function isBancario()
  {
    return $this->TdoBanc ===  self::IS_BANCARIO;
  }


  public function isEfectivo()
  {
  	return $this->TdoCont === self::EFECTIVO;
  }

  public static function idEfectivo()
  {
  	return self::where('TdoCont', self::EFECTIVO )->first()->TpgCodi;
  }

  public static function isCash()
  {
    return $this->TpgCodi == self::TYPE_EFECTIVE;
  }

  public static function isNota()
  {
    return $this->TpgCodi == self::TYPE_NOTACREDITO;
  }

}
