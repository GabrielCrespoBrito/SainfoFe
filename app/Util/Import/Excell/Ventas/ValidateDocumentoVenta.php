<?php 

namespace App\Util\Import\Excell\Ventas;

use App\Empresa;
use App\SerieDocumento;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Collections\RowCollection;

class ValidateDocumentoVenta
{
  const TIPOS_DOCUMENTOS_PERMITIDOS = [
    "01", # Facturas
    "03", # Boletas
  ];

  /**
   * Mensaje de error
   *
   * @var string
   */
  public $message = [];
  /**
   * Documentos a validar
   *
   * @var array
   */
  public $documentos = [];

  /**
   * Tipo de documentos que estan permitidos
   *
   * @var array
   */
  public $tipoDocumentos = [];

  /**
   * Empresa a donde vamos a subir los documentos
   *
   * @var $empresa
   */
  public $empresa = [];

  public function __construct( array $documentos, Empresa $empresa)
  {
    $this->documentos = $documentos;
    $this->empresa = $empresa;
    $this->setDocumentoTipoData();
  }

  /**
   * Obtener la linea en la que esta el documento que se esta evaluando actualmente
   *
   * @param int $line
   * @return void
   */
  public function setCurrentLine( int $line)
  {
    $this->line = $line;
  }

  /**
   * Obtener la linea en la que esta el documento que se esta evaluando actualmente
   *
   * @return int
   */

  public function getCurrentLine()
  {
    return $this->line;
  }

  public function setDocumentoTipoData()
  {
    $tiposDocumentos = $this->empresa->documentos
    ->whereIn('tidcodi', self::TIPOS_DOCUMENTOS_PERMITIDOS)
    // ->unique('sercodi')
    ->groupBy('tidcodi');

    foreach(  $tiposDocumentos as $tipo => $series ){
      $this->tipoDocumentos[$tipo] = $series->pluck('sercodi')->unique()->toArray();
    }

  }

  public function passes()
  {
    $passes = true;

    $index = 2;
    $this->setCurrentLine($index);

    foreach( $this->documentos as $documento ){
      
      $this->setCurrentLine($index++);


      if( ! $documento['tipodocumento']){
        break;
      }

      if( ! $this->validateItem($documento) ){
        $passes = false;
      }
      
    }

    return $passes;
  }

  /**
   * Validar toda la información del item
   *
   * @return bool
   */
  public function validateItem( array $documento )
  {
    # Validar el cliente
    if(!$this->validateCliente($documento)){
      return false;
    }

    # Validar el tipo de documento
    if (!$this->validateTipoDocumento($documento)) {
      return  false;
    }

    # Validar productos
    if (!$this->validateProductos( $documento ) ) {
      return  false;
    }

    return true;
  }

  /**
   * 
   *
   * @param 
   * @return array
   */
  public function prepareErrorMessage($errors)
  {
    $errors = (array) $errors;
    $errors_format = [];

    foreach ($errors as $error) {
      $errors_format[] = "Linea {$this->getCurrentLine()} : "  . $error;
    }

    return $errors_format;
  }

  /**
   * Validar tipo de documento del item
   * 
   * @return bool
   */
  public function validateTipoDocumento( array $documento)
  {
    $tiposDocumentosStr = implode(",", array_keys($this->tipoDocumentos));


    $validateTipoDocumento = Validator::make($documento, [
      'tipodocumento' => 'in:' . $tiposDocumentosStr
    ]);

    if ($validateTipoDocumento->fails() ) {
      $error =  $this->prepareErrorMessage("Los tipos de documentos permitidos son {$tiposDocumentosStr}");
      $this->setMessage($error);
      return false;
    }
    
    $tipodocumento = agregar_ceros($documento['tipodocumento'] , 2,0);
    $seriesStr = implode(",", $this->tipoDocumentos[$tipodocumento]);
    
    $validateSerie = Validator::make($documento, [
      'serie' => 'in:' . $seriesStr
    ]);

    if ($validateSerie->fails()) {
      $error = $this->prepareErrorMessage("Las series permitidas para el tipo de documento {$tipodocumento} son: {$seriesStr}");
      $this->setMessage($error);
      return false;
    }

    return true;
  }

  /**
   * Validar cliente del documento
   * 
   * @return bool
   */
  public function validateCliente( $documento )
  {
    $validator = new ValidateCliente($documento);

    // ------------------------------------------------------------------------------------------------------
    // dump( $this->getCurrentLine() , $documento, $validator->passes(), $validator->getMessage() , "-----" );
    // ------------------------------------------------------------------------------------------------------

    if( ! $validator->passes() ){
      $this->setMessage($this->prepareErrorMessage($validator->getMessage()) );
      return false;
    }

    return true;
  }

  /**
   * Validar producto del item
   * 
   * @return bool
   */
  public function validateProductos($documento)
  {
    // @TODO validar toda la información de los productos del documento 
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
   * Get the value of message
   */
  public function getErrors()
  {
    return $this->getMessage();
  }

  /**
   * Set the value of message
   *
   * @return  self
   */
  public function setMessage( $message)
  {
    $message = (array) $message;    
    $this->message[] = $message;
    return $this;
  }
}