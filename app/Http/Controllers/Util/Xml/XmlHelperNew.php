<?php

namespace App\Http\Controllers\Util\Xml;
use App\GuiaSalida;
use App\Http\Controllers\Util\Xml\Util\XMLSecurityDSig_;
use App\Resumen;
use App\TipoDocumentoPago;
use Chumper\Zipper\Zipper;
use DOMDocument;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

class XmlHelperNew 
{
  // String del xml completo
  public $xml;
  public $igvs;
  public $fileHelper;
  public $name;   
  public $documento;
  public $empresa;  
  public $empresa_ruc;
  public $ruc_empresa;
  public $correlativo;
  public $is_guia;

  const TIPO_DOCUMENTO_LINE = [
    '01' => 'InvoiceLine' , 
    '03' => 'InvoiceLine',
    '07' => 'CreditNoteLine' , 
    '08' => 'DebitNoteLine',
  ];
  
  const TIPO_DOCUMENTO_QUANTITY = [
    '01' => 'InvoicedQuantity' , 
    '03' => 'InvoicedQuantity',
    '07' => 'CreditedQuantity' , 
    '08' => 'DebitedQuantity',
  ];

  const TAX_INFO = [    
    #
    'ISC' => [
      'TaxCategory_ID' => 'S',
      'TaxExemptionReasonCode' => 10,
      'TaxScheme_ID' => 2000,
      'TaxScheme_Name' => 'ISC',
      'TaxScheme_TaxTypeCode' => 'EXC',
    ],
    # ICBPER
    'ICBPER' => [
      'TaxCategory_ID' => 'Z', // No se usa
      'TaxExemptionReasonCode' => 31, // No se usa
      'TaxScheme_ID' => 7152,
      'TaxScheme_Name' => 'ICBPER',
      'TaxScheme_TaxTypeCode' => 'OTH',
    ], 
    #    
    'GRAVADA' => [
      'TaxCategory_ID' => 'S',
      'TaxExemptionReasonCode' => 10,
      'TaxScheme_ID' => 1000,
      'TaxScheme_Name' => 'IGV',
      'TaxScheme_TaxTypeCode' => 'VAT',
    ],
    #
    'EXONERADA' => [
      'TaxCategory_ID' => 'E',
      'TaxExemptionReasonCode' => 20,
      'TaxScheme_ID' => 9997,
      'TaxScheme_Name' => 'EXO',
      'TaxScheme_TaxTypeCode' => 'VAT',
    ],
    #
    'INAFECTA' => [
      'TaxCategory_ID' => 'O',
      'TaxExemptionReasonCode' => 30,
      'TaxScheme_ID' => 9998,
      'TaxScheme_Name' => 'INA',
      'TaxScheme_TaxTypeCode' => 'FRE',
    ],
    #
    'GRATUITA' => [
      'TaxCategory_ID' => 'Z',
      'TaxExemptionReasonCode' => 31,
      'TaxScheme_ID' => 9996,
      'TaxScheme_Name' => 'GRA',
      'TaxScheme_TaxTypeCode' => 'FRE',
    ],
   
  ];

  const MONEDA_ABREV = [
    'SOLES' => 'PEN',
    'DOLARES' => 'USD',
  ];

  // Nombre de los archivos a generar con extension xml y zip
  public $name_xml;
  public $name_zip; 
  public $path_data = "";
  public $path_envio_xml = "";
  public $path_envio_zip = "";
  public $extention_content_index = 1;
  public $aplly_dcto_global_items = false;

  
  public function __construct($documento)
  {  
    set_timezone();
    $this->documento = $documento;
    $this->empresa =  $documento->empresa;
    $ruc = $this->empresa->EmpLin1;
    $this->fileHelper = FileHelper( $ruc );
    $this->ruc_empresa = $ruc;
    $this->nombre_empresa = $this->empresa->EmpNomb;
    $this->nombrecorto_empresa = $this->empresa->EmpLin5;
    $this->correlativo = $documento->numero();
    $td = $this->tipo_documento = $documento->tipo_doc();
    $this->aplly_dcto_global_items = false;
    $this->igvs = $this->empresa->getIgvPorc();

    if( $documento instanceof Resumen ){      
      $this->name = $documento->isResumen() ? $documento->nameFile() : $this->ruc_empresa ."-" . $documento->DocNume;
      $this->extention_content_index = 0;
    }
    else if( $documento instanceof GuiaSalida  )
    {
      $this->is_guia = true;
      $this->correlativo = $documento->nameDocumento();      
      $this->name = $ruc .  sprintf('-%s-', $documento->getTipoDocumento()) . $documento->nameDocumento();    
    }
    else {

        $this->aplly_dcto_global_items = 
        ($td == TipoDocumentoPago::NOTA_DEBITO || $td == TipoDocumentoPago::NOTA_CREDITO) 
        && $this->documento->hasMontoDctoGlobal();


        
      $this->name = $ruc ."-" . $documento->TidCodi . "-" . $documento->numero();
      $this->isContado = $this->documento->forma_pago->isContado();
      $ruc ."-" . $documento->TidCodi . "-" . $documento->numero();
    }

    $this->items = $documento->items;
    $this->name_xml  = $this->name . '.xml';
    $this->name_zip = $this->name . '.zip';   


    $this->getDatasPartsXml();    
    $this->setDatasPartsXml();
  }



  

  public function getDatasPartsXml()
  { 
    ## Header
    $this->header_XMLPART = $this->headers[$this->tipo_documento];
    
    ## Información del documento
    if( !$this->is_guia )  {
      $this->documentInfo_XMLPART = ($this->tipo_documento == "07" || $this->tipo_documento == "08") ? $this->documentsInfo["0708"] : $this->documentsInfo["0103"];      
    }

    
    ## La información de la firma es igual para todos los tipos de documentos
    $this->firmaData_XMLPART;

    ## La información de la empresa Solo se cambia el tipo de documento del cliente
    $this->empresaData_XMLPART;

    // Total o Total en la nota de debito
     
    if( $this->tipo_documento == "08" || $this->tipo_documento == "07" ){
      $this->legalTotales_XMLPART = $this->legaltotales[$this->tipo_documento];
    }
    else {
      $this->legalTotales_XMLPART = $this->tipo_documento == "guia" ? "" : $this->legaltotales['010307'];
    }


    ## La información del cliente es igual a todos
    $this->clienteData_XMLPART;

    ## Iteración de los items para los documentos es el mismo solo cambiar el Nombre de las etiquetas
    $this->item_base;

    ## Pie del xml    
    $this->footer_XMLPART = $this->footers[$this->tipo_documento];
  }

  public function change_datas( $cambiar , &$arr , $change = true )
  {
    $arrCopy = $arr;
    
    foreach ( $cambiar as $valores  ) {
      $replace_text = "[" . $valores[0] . "]";
      $valor = $valores[1];     
      $res = str_replace( $replace_text , $valor , ( $change ? $arr : $arrCopy ) ); 
      
      if( $change ){
        $arr = $res;  
      }
      else {
        $arrCopy = $res;
      }
    }
    return $arrCopy;
  }

  public static function getValue( $prop , $content )
  {
    $values = [];

    foreach( (array) $prop as $p ){
      $position_response = strpos( $content , $p );      
      $respuesta = substr( $content , $position_response );
      $after_code = explode("</", $respuesta)[0];
      $valor = explode( ">" , $after_code)[1];
      array_push($values, $valor);
    }
    return $values;
  }

  public static function extract_from_zip( $content )
  {    
    $name_zip = str_random(5) . '.zip';    
    $path = FileHelper()->saveTemp( $content , $name_zip );
    $zipper = new Zipper;
    $zipper->zip($path);
  } 


  public static function extract_from_content( $content , $name_file , $props )
  {
    $name_zip = str_random(5) . '.zip';
    $path = getTempPath( $name_zip , $content );
    return self::extract_value( $props , $path , $name_file, true );
  } 


  public static function extract_value( $prop , $path , $xml_name , $comprimido = false) 
  {
    $values = [];

    // $realName = getFilenameFromZip()

    if( !file_exists($path)  )  {
      $rpta = "No existen ningun archivo en la ruta " . $path;
      throw new \Exception($rpta);
    }

    # Buscar el nombre real en el archivo xml
    $xml_name = getFilenameFromZip(file_get_contents($path));

    if($comprimido){
      $zipper = new Zipper;
      $zipper->make($path);
      $content = $zipper->getFileContent($xml_name);
    }

    else {      
      $content = file_get_contents($path);
    }

    return self::getValue( $prop , $content );
  }

  public function generar_sinfirma()
  {
    // documentInfo_XMLPART
    foreach (get_object_vars($this) as $name => $val) {     
      if( strpos($name , "XMLPART") !== false ){
        $this->xml .= $val;
      }
    }

    // Reemplazo global
    $abbre_moneda = self::MONEDA_ABREV[$this->documento->moneda->monnomb];
    $this->change_datas([
      ["moneda", $abbre_moneda ],
    ] , $this->xml);
  }

  public function generar_confirma()
  {
    if(!$this->fileHelper->certExist('.key')) {
      $pathCert = $this->fileHelper->getCertPath(".key");
      throw new \Exception('No se encuentra la LLAVE PRIVADA ' . $pathCert);
    }
    
    if(!$this->fileHelper->certExist('.cer')){
      $pathCert = $this->fileHelper->getCertPath(".cert");
      throw new \Exception('No se encuentra la LLAVE PUBLICA ' . $pathCert);
    }

    $privateKey = $this->fileHelper->getCert('.key');
    $publicKey = $this->fileHelper->getCert('.cer');

    $ReferenceNodeName = 'ExtensionContent';

    // Load the XML to be signed
    $doc = new DOMDocument();
    $objDSig = new XMLSecurityDSig_( $this->is_guia );

    logger($this->xml);
    $doc->loadXML( $this->xml );
    $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);      
    $objDSig->addReference( 
      $doc, 
      XMLSecurityDSig::SHA1,
      ['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],
      ['force_uri' => true ]
    );

    $objKey = new XMLSecurityKey( XMLSecurityKey::RSA_SHA1 , array('type'=>'private'));   
    $objKey->loadKey($privateKey);
    
    $objDSig->sign($objKey,$doc->getElementsByTagName($ReferenceNodeName)->item(0));
    $options = ["subjectName" => true];
    $objDSig->add509Cert($publicKey, true, false, $options );
    $objDSig->appendSignature($doc->getElementsByTagName($ReferenceNodeName)->item(0));

    return $doc->saveXML();
  }

  public function guardar()
  {
    $this->generar_sinfirma();
    $xml_firmado = $this->generar_confirma(); 
    $this->fileHelper->save_data( $this->name_xml , $this->xml );

    $tempName = str_random(15) . ".zip";    
    $pathTemp = getTempPath($tempName);    
    
    $zipper = new Zipper;
    $zipper
    ->make($pathTemp)
    ->addString($this->name_xml , $xml_firmado);

    $zipper->close();

    $contentZip = file_get_contents($pathTemp);
    $this->fileHelper->save_envio($this->name_xml, $xml_firmado);
    $this->fileHelper->save_envio($this->name_zip, $contentZip );

    $firma = extraer_values_string( "DigestValue" , $xml_firmado )[0];


    return [
      'path' => $pathTemp,
      'name_zip' => $this->name_zip,
      'name_xml' => $this->name_xml,
      'contentZip' => $contentZip,
      'firma' => $firma
    ];
  }
  
}