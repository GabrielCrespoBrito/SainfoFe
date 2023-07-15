<?php

namespace App;
use App\HHCL;
use App\Http\Controllers\Util\Xml\Util\XMLSecurityDSig_;
use App\Resumen;
use Chumper\Zipper\Zipper;
use DOMDocument;
use Illuminate\Database\Eloquent\Model;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

class XmlHelper 
{
  public $xml;
  public $fileHelper;  
  public $name;   
  public $documento;
  public $empresa;  
  public $empresa_ruc;
  public $correlativo = "00001";

  // Nombre de los archivos a generar con extension xml y zip
  public $name_xml;
  public $name_zip; 

  public $path_cert = "";
  public $path_data = "";
  public $path_envio_xml = "";
  public $path_envio_zip = "";
  public $extention_content_index = 1;
  const MONEDA_ABREV = [
    'SOLES' => 'PEN',
    'DOLARES' => 'USD',   
  ]; 

  const GRAVADA_SYSTEM = "GRAVADA";
  const GRATUITA_SYSTEM = "GRATUITA";
  const EXONERADA_SYSTEM = "EXONERADA";
  const INAFECTA_SYSTEM = "INAFECTA";
  
  const TaxTypeCode = [
    self::GRAVADA_SYSTEM => 'VAT',
    self::INAFECTA_SYSTEM  => 'FRE',
    self::GRATUITA_SYSTEM => 'FRE'
  ];

  const TaxTypeName = [
    self::GRAVADA_SYSTEM  => 'IGV',
    self::INAFECTA_SYSTEM => "INAFECTO",
    self::GRATUITA_SYSTEM => "GRATUITO",      
    self::EXONERADA_SYSTEM => "EXONERADO",
  ];

  const TaxTypeID = [
    self::GRAVADA_SYSTEM  => 1000,
    self::GRATUITA_SYSTEM => 9996,
    self::EXONERADA_SYSTEM => 9997,
    self::INAFECTA_SYSTEM => 9998,
  ];

  const GRATUITA = "GRATUITO";
  const INAFECTA = "INAFECTO";  
  const EXONERADA = "EXONERADA";
  const GRAVADA = "IGV";    


  public function __construct($documento , $empcodi = null )
  {  
    set_timezone(); 

    $this->empresa = is_null($empcodi) ? get_empresa() : Empresa::find($empcodi);
    $this->empresa_ruc = $this->empresa->EmpLin1;
    $this->fileHelper = FileHelper($this->empresa_ruc);
    if( $documento instanceof Resumen  ){
      $this->name = $documento->isResumen() ? $documento->nameFile() : $this->empresa_ruc ."-" . $documento->DocNume;
      $this->extention_content_index = 0;
    }

    else {
      $this->name = $this->empresa_ruc ."-" . $documento->TidCodi . "-" . $documento->VtaNume;    
    }

    $this->documento = $documento;
    $this->name_xml  = $this->name . '.xml';
    $this->name_zip = $this->name . '.zip';   

    $this->path_cert = env('SAINFO_CERT') . $this->empresa_ruc;
    $this->path_data = env('SAINFO_DATA') . $this->name_xml;
    $this->path_envio_xml = env('SAINFO_ENVIO') . $this->name_xml;
    $this->path_envio_zip = env('SAINFO_ENVIO') . $this->name_zip;
    $this->setDatasPartsXml();
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

  public static function extract_value( $prop , $path , $xml_name , $comprimido = false) 
  {
    $values = [];

    if( !file_exists($path)  )  {
      $rpta = "No existen ningun archivo en la ruta " . $path;
      throw new \Exception($rpta);
    }

    if($comprimido){
      $zipper = new Zipper;
      $zipper->make($path);
      $filePath = $xml_name;    
      $content = $zipper->getFileContent($filePath);
    }

    else {      
      $content = file_get_contents($path);
    }

    foreach( (array) $prop as $p ){
      $position_response = strpos( $content , $p );      
      $respuesta = substr( $content , $position_response );
      $after_code = explode("</", $respuesta)[0];
      $valor = explode( ">" , $after_code)[1];
      array_push($values, $valor);
    }

    return $values;
  }

  public function generar_sinfirma()
  {
    foreach (get_object_vars($this) as $name => $val) {     
      if( strpos( $name , "part_xml" ) !== FALSE ){
        $this->xml .= $val;
      }
    }

    $abbre_moneda = self::MONEDA_ABREV[$this->documento->moneda->monnomb];

    $this->change_datas([
      ["moneda_abbre", $abbre_moneda ],
      ["numero_serie_venta" , $this->documento->VtaNume ],
    ] , $this->xml);
  }

  // nuevo
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
    $objDSig = new XMLSecurityDSig_();
    $doc->loadXML( $this->xml );
    $objDSig->setCanonicalMethod(XMLSecurityDSig_::C14N);
    
    $objDSig->addReference( 
      $doc, 
      XMLSecurityDSig::SHA1,
      ['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],
      ['force_uri' => true ]
    );
    $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1,array('type'=>'private')); 
    $objKey->loadKey($privateKey);
    
    $objDSig->sign($objKey,$doc->getElementsByTagName($ReferenceNodeName)->item(0));
    $options = ["subjectName" => false];
    $objDSig->add509Cert($publicKey, true, false, $options );
    $objDSig->appendSignature($doc->getElementsByTagName($ReferenceNodeName)->item(0));

    return $doc->saveXML();
  }


  // nuevo
  public function guardar($generar_sinfirma = true)
  {
    $this->generar_sinfirma();

    $this->fileHelper->save_data( $this->name_xml , $this->xml );
    $xml_firmado = $this->generar_confirma(); 
    $tempName = str_random(15) . ".zip";
    $pathTemp = getTempPath($tempName);
    
    $zipper = new Zipper;
    $zipper
    ->make($pathTemp)
    ->addString( $this->name_xml , $xml_firmado )
    ->close();
    
    $content = file_get_contents($pathTemp);

    $this->fileHelper->save_envio( $this->name_xml , $xml_firmado );
    $this->fileHelper->save_envio( $this->name_zip , $content );
    
    $firma = extraer_values_string( "DigestValue" , $xml_firmado  )[0];

    return [
      'path' => $pathTemp,
      'firma' => $firma,
      'contentZip' => $content,
    ];
  }
}