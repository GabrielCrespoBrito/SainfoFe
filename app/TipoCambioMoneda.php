<?php

namespace App;

use App\Jobs\TipoCambio\CreateDefaultDayTipoCambio;
use App\Util\ModelUtil\ModelEmpresaScope;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class TipoCambioMoneda extends Model
{
  use
  UsesTenantConnection,
  ModelEmpresaScope;

	protected $table = 'tcmoneda';  
  protected $primaryKey = 'TipCodi';
  protected $keyType = 'string';
  public $timestamps = false;
  const DEFAULT_CAMBIO = 3.267;
  const DEFAULT_VENTA = 3.267;  
  const EMPRESA_CAMPO = "empcodi";  
  const DEFAULT_COMPRA   = 3.333;
  const FIELD_NAME_COMPRA = "TipComp";
  const FIELD_NAME_VENTA = "TipVent";  
  public $fillable = ['TipCodi', 'TipFech', 'TipComp', 'TipVent'];

  public static function getToday()
  {
    return $tc = self::where('TipFech', date('Y-m-d'))->first();
  }

  public static function getTodayInfo()
  {
    $tc = self::getToday();
    $needUpdated = false; 

    if( $tc == null ){
      $needUpdated = true;
      CreateDefaultDayTipoCambio::dispatchNow();
      $tc = self::getToday();
    }

    return [
      'needUpdated' => $needUpdated,
      'tc' => $tc,
    ];
  }


  public static function lastTC()
  {
    return TipoCambioMoneda::OrderByDesc('TipFech')->first();

  }

  public static function ultimo_cambio( $compra = true  )
  {
  	$tipo_cambio = TipoCambioMoneda::OrderByDesc('TipFech')->first();

  	if( $tipo_cambio ){
  		return $compra ? $tipo_cambio->TipComp : $tipo_cambio->TipVent;
  	}

  	return self::DEFAULT_CAMBIO;
  }


  public static function lastChange( $buy = true  )
  {
    $lastChange = TipoCambioMoneda::OrderByDesc('TipFech')->first();

    // return is_null($lastChange) ? 
    //   $buy ? self::DEFAULT_COMPRA : self::DEFAULT_VENTA : 
    //   $buy ? $lastChange->TipVent : $lastChange->TipComp;    

    if( is_null($lastChange) ){
      return $buy ? self::DEFAULT_COMPRA : self::DEFAULT_VENTA;
    }
    else {
      return $buy ? $lastChange->TipVent : $lastChange->TipComp;    

    }

    return self::DEFAULT_CAMBIO;
  }

  public static function lastChangeBuy(){
    return self::ultimo_cambio(true);
  }

  public static function lastChangeSale(){
    return self::ultimo_cambio(false);
  }


  public function getCodi()
  {
    $currentDate = explode("-", $this->TipFech);
    $currentDate[0] = substr($currentDate[0], -2);

    return implode($currentDate);
  }

  public function getFechaAttribute()
  {
    return $this->TipFech;
  }

  public function getCompraAttribute()
  {
    return $this->TipComp;
  }

  public function getVentaAttribute()
  {
    return $this->TipVent;
  }

  public function getIdAttribute()
  {
    return $this->TipCodi;
  }

  public static function TasaCambio()
  {
    $mes = 12;
    $year = 2020;
    // Se pone el mes y el año
    $mes = date('m');
    $ano = date('Y');

    
    $sUrl = "https://e-consulta.sunat.gob.pe/cl-at-ittipcam/tcS01Alias?mes=" . $mes . "&anho=" . $year . "";
    $sContent = file_get_contents($sUrl);

    // create new DOMDocument
    $doc = new \DOMDocument('1.0', 'UTF-8');

    // set error level
    $internalErrors = libxml_use_internal_errors(true);
    $doc->loadHTML($sContent);
    $xpath = new \DOMXPath($doc);
    $tablaTC = $xpath->query("//table[@class='class=\"form-table\"']"); //obtenemos la tabla TC
    $filas = [];
    foreach ($tablaTC as $fila) {
      $filas = $fila->getElementsByTagName("tr"); //obtiene todas las tr de la tabla de TC
    }
    $tcs = array(); //array de tcs, por dia como clave
    foreach ($filas as $fila) { //recorremos cada tr
      $tds = [];
      $tds = $fila->getElementsByTagName("td");
      $i = 0;
      $j = 0;
      $arr = [];
      $dia = "";
      foreach ($tds as $td) { //recorremos cada td
        if ($j == 3) {
          $j = 0;
          $arr = [];
        }
        if ($j == 0) {
          $dia = trim(preg_replace("/[\r\n]+/", " ", $td->nodeValue));
          $tcs[$dia] = [];
        }
        if ($j > 0 && $j < 3) {
          $tcs[$dia][] = trim(preg_replace("/[\r\n]+/", " ", $td->nodeValue));
        }
        $j++;
      }
    }

    // Ver resultado
    $count = 1;
    $dimension = sizeof($tcs);
    $final = key(array_reverse($tcs, true));

    for ($k = 1; $k < ($final + 1); $k++) {
      if (isset($tcs[$k])) {
        $tcss[$k] = $tcs[$k];
      } else {
        if (isset($tcs[($k - 1)])) {
          $tcss[$k] = $tcs[($k - 1)];
        } else {
          if (isset($tcs[($k - 2)])) {
            $tcss[$k] = $tcs[($k - 2)];
          } else {
            if (isset($tcs[($k - 3)])) {
              $tcss[$k] = $tcs[($k - 3)];
            } else {
              if (isset($tcs[($k - 4)])) {
                $tcss[$k] = $tcs[($k - 4)];
              } else {
                $tcss[$k] = $tcs[($k - 5)];
              }
            }
          }
        }
      }
    }
    return $tcss;
  }	
}