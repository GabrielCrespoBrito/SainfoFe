<?php 

namespace App\Http\Controllers\Util\SummaryContigence;

use App\Venta;

class ProcessDocument 
{
  /**
   * Id del documento a trabajar
   *
   * @var string
   */
	protected $id;  

  /**
   * Documento
   *
   * @var Model
   */
  protected $documento;

  /**
   * Motivo de la contingencia para todos los documentos que la clase vaya a procesar
   *
   * @var string
   */
  protected $motivo = null;

  
  /**
   * Motivo de la contingencia del documento actual
   *
   * @var string
   */
  protected $current_motivo = null;

  /**
   * Motivos del a contingencia (anexo 11)
   *
   * @var array
   */
  const MOTIVOS = [1,2,3,4,5,6,7];


  /**
   * Todos los campos que lleva la linea
   *
   * @var array
   */
  const FIELDS = [ 
    [ 'name' => "motivo"], 
    [ 'name' => "fecha_emision"],
    [ 'name' => "tipo_documento"],
    [ 'name' => "serie_documento"],
    [ 'name' => "correlativo_documento"],
    [ 'name' => "numero_maquina_final"],
    [ 'name' => "tipodocumento_cliente"],
    [ 'name' => "documento_cliente"],
    [ 'name' => "razonsocial_cliente"],
    [ 'name' => "total_gravada"],
    [ 'name' => "total_exonerada"],
    [ 'name' => "total_inafecta"],
    [ 'name' => "total_isc"],
    [ 'name' => "total_igv"],
    [ 'name' => "otros_tributos"],
    [ 'name' => "total_importe"],
    [ 'name' => "serie_referencia"],
    [ 'name' => "tipodocumento_referencia"],
    [ 'name' => "numerodocumento_referencia"],
  ];

  public $fieldsCollect;

  /**
   * Formato de la linea ha devolver
   *
   * @var string
   */	
	protected $formatLine = "";


  /**
   * Id del documento a procesar
   *
   * @var string
   */	
	private $pathTxt;


  public function __construct( $motivo = null )
  {
    $this->motivo = $motivo;
    $this->fieldsCollect = collect(self::FIELDS);

  }

  /**
   * Dejar en blanco la linea cada nuevo elemento
   *
   * @var void
   */ 
  public function resetFormatLine()
  {
    $this->formatLine = "";
  }


  /**
   * 
   *
   * @var 
   */ 
  public function setFormatLine( String $format )
  {
    $this->formatLine = $format;
  }

  /**
   * Agregar párte a la linea
   *
   * @var void
   */ 
  public function addToFormatLine( String $str )
  {
    $this->formatLine .= $str;
  }

  public function setDocumento( $documento )
  {
    $this->documento = $documento;
  }

  public function getDocumento()
  {
    return $this->documento;
  }

  /**
   * Getter
   *
   * @var string
   */ 
  public function getFormatLine()
  {
    return $this->formatLine;
  }

  /**
   * Validar que integer de motivo sea valido
   *
   * @return $path
   */
  public function validateMotivo( $motivo = null )
  {
    if( is_null($motivo) && is_null($this->motivo)  ){
      throw new \Exception("Es necesario el motivo", 1);
    }

    $motivo = $motivo ?? $this->motivo;

    if ( ! in_array($motivo, self::MOTIVOS ) ) {
      throw new \Exception("Motivo erroneo de contingencia los motivos validos son:" . implode(",", self::MOTIVOS)  , 1);
    }

    return $this->current_motivo = $motivo;
  }

  /**
   * Generar linea del documento txt
   *
   * @return $path
   */
  public function load( $id , $motivo = null )
  {
    $documento = Venta::find($id);
    $this->setDocumento($documento);
    $this->validateMotivo($motivo);
    $this->resetFormatLine();
    $this->generateFormat();
  }

  public function transformValue($value)
  {
    return is_null($value) || strlen($value) == 0 ? '0.00' : fixedValue($value,2);
  }


  /**
   * Generar formato linea del documento txt
   *
   * @return $path
   */
  public function generateFormat()
  {
    $campos = $this->fieldsCollect->pluck('name');

    for( $i = 0; $i < count($campos); $i++ ) {

      $papeLineFirst = "|";
      $papeLineEnd = "";

      if( $i == 0  ){
        $papeLineFirst = ""; 
      }

      if( $i == count($campos)-1  ){
        $papeLineEnd = "|"; 
      }

      $campo = sprintf("%s[%s]%s", $papeLineFirst , $campos[$i] , $papeLineEnd);
      $this->addToFormatLine($campo);;
    }
  }

  /**
   * Generar linea del documento txt
   *
   * @return $path
   */
  public function process()
  {
    $document = $this->getDocumento();

    // Motivo
    $this->insertInFormat('motivo' , $this->current_motivo);

    // Fecha    
    $this->insertInFormat('fecha_emision' , $document->VtaFvtaReverse);

    // Tipo Documento
    $this->insertInFormat('tipo_documento' , $document->TidCodi);

    // serie_documento
    $this->insertInFormat('serie_documento' , $document->VtaSeri);

    // correlativo_documento
    $this->insertInFormat('correlativo_documento' , $document->VtaNumee);

    // numero_maquina
    $this->insertInFormat('numero_maquina_final' , '');

    // tipodocumento_cliente
    $this->insertInFormat('tipodocumento_cliente' , $document->cliente->TDocCodi  );

    // documento_cliente
    $this->insertInFormat('documento_cliente' , $document->cliente->PCDocu );

    // razonsocial_cliente
    $this->insertInFormat('razonsocial_cliente' , $document->cliente->PCNomb );

    // total_gravada
    $this->insertInFormat('total_gravada' , $document->Vtabase , true );

    // total_exonerada
    $this->insertInFormat('total_exonerada' , $document->VtaExon , true );

    // total_inafecta
    $this->insertInFormat('total_inafecta' , $document->VtaInaf , true);

    // total_isc
    $this->insertInFormat('total_isc' , $document->VtaISC , true);

    // total_igv
    $this->insertInFormat('total_igv' , $document->VtaIGVV , true);

    // otros_tributos
    $this->insertInFormat('otros_tributos' , null , true);

    // total_importe
    $this->insertInFormat('total_importe' , $document->VtaTota , true );

    // serie_referencia
    $this->insertInFormat('serie_referencia' , $document->VtaSeriR);

    // tipodocumento_referencia
    $this->insertInFormat('tipodocumento_referencia' , $document->VtaTDR);        

    // numerodocumento_referencia
    $this->insertInFormat('numerodocumento_referencia' , $document->VtaNumeR );    

    return $this->getFormatLine();
  }

  /**
   * Insertar en la plantilla en el lugar correspondiente
   * @param  string $part
   * @param  string|integer $value
   * @return void
   */
  public function insertInFormat( String $part, $value , $isNumeric = false )
  {
    if( ! $this->fieldsCollect->contains('name' , $part ) ){
      throw new \Exception("El campo {$part} no existe en el listado de campos por insertar", 1);
    }    

    $campo = "[" . $part . "]";

    $value = $isNumeric ? $this->transformValue($value) : $value;

    $newFormat = str_replace($campo, $value , $this->getFormatLine());
    $this->setFormatLine($newFormat);
  }


}
