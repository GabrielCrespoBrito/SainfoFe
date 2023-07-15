<?php

namespace App;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class TipoDocumentoPago extends Model
{
  use UsesSystemConnection;
  
  protected $table = 'tipo_documento_pago';
  protected $keyType = 'string';
  protected $primaryKey = 'TidCodi';
  public $timestamps = false;

  const FACTURA = "01";
  const BOLETA = "03";
  const NOTA_CREDITO = "07";
  const NOTA_DEBITO = "08";
  const GUIA_SALIDA = "09";
  const PROFORMA = "50";
  const COTIZACION = "50";
  const NOTA_VENTA = "52";
  const PREVENTA = "53";
  const NOTA_VENTA_ = "53";
  const ORDEN_PAGO = "98";
  const ORDEN_COMPRA = "99";
  
  const VALID_DOCUMENTOS = [
    'NOTA DE CREDITO',  
    'NOTA DE DEBITO',   
    'BOLETA DE VENTA', 
    'NOTA DE VENTA', 
    'FACTURA',
  ];
  // 
  const VALID = [
    "01", 
    "03", 
    "07", 
    "08", 
    "09", 
    "10", 
    "50", 
    "52", 
    "53",
    "98",
    "99"
  ];


  const VALID_TIPO_VENTAS = [
    "01",
    "03",
    "07",
    "08",
    "50",
    "52",
  ];

  const VALID_TIPO_VENTAS_WITHOUT_NOTAS = [
    "01",
    "03",
    "50",
    "52",
  ];


  public static function getTiposVentas()
  {
    return [
      self::FACTURA => 'FACTURA',
      self::BOLETA  => 'BOLETA DE VENTA',
      self::NOTA_CREDITO => 'NOTA DE CREDITO',
      self::NOTA_DEBITO  => 'NOTA DE DEBITO',
      self::NOTA_VENTA   => 'NOTA DE VENTA',
    ];
  }

  public static function isTipoVentas( $tipo )
  {
    return in_array( $tipo ,  [
      self::FACTURA,
      self::BOLETA,
      self::NOTA_CREDITO,
      self::NOTA_DEBITO
    ]);
  }


  public static function validDocumentos( array $valids = null )
  {
    $documentos = [];

    $valids = $valids ?? self::VALID;

    foreach( self::all() as $documento ) {
      if( in_array ($documento->TidCodi , $valids) ){
        array_push( $documentos , $documento);    
      }
    }

    return $documentos;
  }

  /**
   * Obtener tipos de documentos que se utilizan que se enviar a la sunat
   * 
   *  @return array
   */
  public static function getValidTipoSunat()
  {
    return self::validDocumentos(["01","03","07","08","09"]);
  }

  public static function getNombreDocumento($codigo , $searchDatabase = false){
    switch ($codigo) {
      case '01':
        return 'FACTURA';
        break;
      case '03':
        return 'BOLETA DE VENTA';
        break;
      case '07':
        return 'NOTA DE CREDITO';
        break;
      case '08':
        return 'NOTA DE DEBITO';
        break;
      case '09':
        return 'GUIA DE REMISIÒN';
        break;        
      case '50':
        return 'PROFORMA';
        break;          
      case '52':
      case '53':
        return 'NOTA DE VENTA';
        break;
        case '98':
          return 'ORDEN PAGO';
        break;
      case '99':
        return 'ORDEN DE COMPRA';
        break;                       
        default;
        return 'UNDEFINED';
        break;          
    }
  }


  public static function notaCreditoId()
  {
  	return TipoDocumentoPago::where('TidNomb' , 'NOTA DE CREDITO')->first()->TidCodi;
  } 

  public static function notaDebitoId()
  {
    return TipoDocumentoPago::where('TidNomb' , 'NOTA DE DEBITO')->first()->TidCodi;
  }

  public static function boletaId()
  {
  	return TipoDocumentoPago::where('TidNomb' , 'BOLETA DE VENTA')->first()->TidCodi;
  } 
  public static function facturaId()
  {
    return TipoDocumentoPago::where('TidNomb' , 'FACTURA')->first()->TidCodi;
  }  
  public static function guiaremisionId()
  {
    return TipoDocumentoPago::where('TidNomb' , 'FACTURA')->first()->TidCodi;
  }

  public function getNombreForPlantilla()
  {
    return self::getNombreTipoForPlantilla($this->TidCodi);
  }

    
  public static function getNombreTipoForPlantilla($tipo)
  {
    switch ($tipo) {
      case self::FACTURA:
      case self::BOLETA:
      case self::NOTA_CREDITO:
      case self::NOTA_VENTA:
      case self::NOTA_DEBITO:
        return 'ventas';
        break;
      case self::GUIA_SALIDA:
        return 'guias';
        break;
      case self::COTIZACION:
      case self::ORDEN_PAGO:
      case self::PREVENTA:
      case self::ORDEN_COMPRA:
        return 'cotizaciones';
        break;
      default :
        return null;
      return;
    }
  }


}