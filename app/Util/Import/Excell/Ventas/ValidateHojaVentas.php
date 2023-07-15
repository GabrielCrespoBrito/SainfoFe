<?php

namespace App\Util\Import\Excell\Ventas;

use Maatwebsite\Excel\Collections\RowCollection;

class ValidateHojaVentas
{
  public $message = "";
  public $hoja;
  public $headersHoja;

  const HEADERS_PRINCIPALES = [
    "tipodocumento",	
    "serie",	
    "fecha",
    "cliente_documento",
    "cliente_nombre",
    "cliente_tipodocumento",
    "observacion",
    "tipo_cambio",
  ];

  const HEADERS_PRODUCTO_BASIC = [
    "producto",
    "base",
    "nombre",
    "precio",
    "cantidad",
    "incluye_igv",
  ];

  public function __construct( RowCollection $hoja )
  {
    $this->hoja = $hoja;
    $this->headersHoja = $hoja->getHeading();
  }

  /**
   * Hacer evaluación 
   *
   * @return bool
   */
  public function passes()
  {
    if(!$this->validateHeadersDocumento()){
      return false;
    }

    if (!$this->validateHeadersProductos()) {
      return false;
    }

    return true;
  }
  
  /**
   * Comparar arrays de cabeceras y devolver información de faltantes 
   * @param array $headerPrincipal  
   * @param array $headerCompare
   * @return array
   */

  public function compareHeader(array $headerPrincipal, array $headerCompare)
  {
    $headerFaltante = array_diff($headerPrincipal, $headerCompare);
    $headerFaltanteLen = count($headerFaltante);
    $headerFaltanteStr = "";

    # Validar que no falten campos de la cabecera
    if ($headerFaltanteLen) {
      $headerFaltanteStr = implode(",", array_values($headerFaltante));
    }

    return [
      'headerFaltanteLen' => $headerFaltanteLen,
      'headerFaltanteArr' => $headerFaltante,
      'headerFaltanteStr' => $headerFaltanteStr,
    ];
  }

  /**
   * Validar campos del documento en la cabecera
   *
   * @return bool
   */
  public function validateHeadersDocumento()
  {
    $dif = $this->compareHeader(self::HEADERS_PRINCIPALES, $this->headersHoja);

    # Validar que no falten campos de la cabecera
    if ( $dif['headerFaltanteLen'] ) {

      $pluralSingular = ($dif['headerFaltanteLen'] == 1 ?  "falta el campo: " : "faltan los campos: ");
      $message = "En la cabecera de la hoja, " . $pluralSingular .  $dif['headerFaltanteStr'];

      $this->setMessage($message);
      return false;
    }

    return true;
  }

  /**
   * Validar campos para los productos
   *
   * @return bool
   */
  public function validateHeadersProductos()
  {
    # Evaluar las cabecera del item principal que siempre se supone que tiene que estar
    if( !$this->validateHeadersProductoIndividual(1)){
        return false;
    }

    # Evaluar siguientes productos en caso de existir 2,3,4,5,6,7...
    $limitItems = config('app.limit_items_ventas_import', 10);
    
    for ( $i = 2; $i <= $limitItems ; $i++) { 
      
      # Si existe el siguiente indice principal para ver si hay un item, realizar la evaluación
      if( in_array( "producto{$i}", $this->headersHoja ) ){        
        if(!$this->validateHeadersProductoIndividual($i) ){
          return false;
        }
      }
      # Si no existe bloquear la iteración
      else {
        break;
      }
    }   
    
    return true;
  }


  /**
   * Obtener un array|string pasado por parametro, con un numero al final
   *
   * @return array|string
   */
  public function getHeaderProductoWithIndex( $headers , int $index )
  {
    if( is_string($headers) ){
      return $headers . $index;
    }

    else {
      $headerWithIndex = [];

      foreach ($headers as $header ) {
        $headerWithIndex[] = $header . $index;
      }

      return $headerWithIndex;
    }

  }

  /**
   * Validar campos para un item especifico
   *
   * @return bool
   */
  public function validateHeadersProductoIndividual($index)
  {
    $headersPrincipales = $this->getHeaderProductoWithIndex( self::HEADERS_PRODUCTO_BASIC , $index);
    $dif = $this->compareHeader( $headersPrincipales, $this->headersHoja);

    # Validar que no falten campos de la cabecera
    if ($dif['headerFaltanteLen']) {

      $pluralSingular = ($dif['headerFaltanteLen'] == 1 ?  "falta el campo: " : "faltan los campos: ");
      $message = "En la cabecera de la hoja, para el ingresar el producto ({$index}) " . $pluralSingular .  $dif['headerFaltanteStr'];
      $this->setMessage($message);
      return false;
    }

    // @TODO Evaluar campo ICBPER, ISC, etc, etc;

    return true;
  }

  /**
   * Get the value of message
   */ 
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * Set the value of message
   *
   * @return  self
   */ 
  public function setMessage($message)
  {
    $this->message = $message;

    return $this;
  }
}