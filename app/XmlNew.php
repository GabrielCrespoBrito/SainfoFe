<?php

namespace App;

use App\HHCL;
use App\Resumen;
use DOMDocument;
use Illuminate\Database\Eloquent\Model;
use Chumper\Zipper\Zipper;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

class XmlNew 
{
  // String del xml completo
  public $xml;
  public $name;   
  public $documento;
  public $empresa;  
  public $empresa_ruc;
  public $correlativo;

  const MONEDA_ABREV = [
    'SOLES' => 'PEN',
    'DOLARES' => 'USD',
  ];

  public $header_XMLPART = 
  '<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
  xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
  xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
  xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';

  public $documentInfo_XMLPART = 
  '
  <ext:UBLExtensions>
  <ext:UBLExtension>
  <ext:ExtensionContent></ext:ExtensionContent>
  </ext:UBLExtension>
  </ext:UBLExtensions>  
  <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
  <cbc:CustomizationID>2.0</cbc:CustomizationID>
  <cbc:ID>[codigo]</cbc:ID>
  <cbc:IssueDate>[fecha]</cbc:IssueDate>
  <cbc:IssueTime>[hora]</cbc:IssueTime>
  <cbc:InvoiceTypeCode listID="0101" listAgencyName="PE:SUNAT" listName="SUNAT:Identificador de Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">01</cbc:InvoiceTypeCode>
  <cbc:Note languageLocaleID="1000">[cantidad_letras]</cbc:Note>
  <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">[moneda]</cbc:DocumentCurrencyCode>
  <cbc:LineCountNumeric>[cantidad_items]</cbc:LineCountNumeric>';

  public $firmaData_XMLPART = 
  '
  <cac:Signature>
    <cbc:ID>IDSignSP</cbc:ID>
    <cac:SignatoryParty>
      <cac:PartyIdentification>
      <cbc:ID>[ruc_empresa]</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>[nombre_empresa]</cbc:Name>
      </cac:PartyName>
    </cac:SignatoryParty>
    <cac:DigitalSignatureAttachment>
      <cac:ExternalReference>
      <cbc:URI>#SignatureSP</cbc:URI>
      </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
  </cac:Signature>';
  
  public $empresaData_XMLPART = 
  '
  <cac:AccountingSupplierParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad">[ruc_empresa]</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>[nombre_empresa]</cbc:Name>
      </cac:PartyName>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>[nombre_empresa]</cbc:RegistrationName>
        <cac:RegistrationAddress>
          <cbc:AddressTypeCode>0001</cbc:AddressTypeCode>
        </cac:RegistrationAddress>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingSupplierParty>';

  public $clienteData_XMLPART = 
  '
<cac:AccountingCustomerParty>
  <cac:Party>
    <cac:PartyIdentification>
      <cbc:ID schemeID="6">[ruc_cliente]</cbc:ID>
    </cac:PartyIdentification>
    <cac:PartyLegalEntity>
      <cbc:RegistrationName>[nombre_cliente]</cbc:RegistrationName>
    </cac:PartyLegalEntity>
  </cac:Party>
</cac:AccountingCustomerParty>';

  public $totales_XMLPART = 
  '  
  <cac:TaxTotal>
    <cbc:TaxAmount currencyID="[moneda]">7891.20</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxableAmount currencyID="[moneda]">43840.00</cbc:TaxableAmount>
      <cbc:TaxAmount currencyID="[moneda]">7891.20</cbc:TaxAmount>
      <cac:TaxCategory>
        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
        <cac:TaxScheme>
          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
          <cbc:Name>IGV</cbc:Name>
          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
        </cac:TaxScheme>
      </cac:TaxCategory>
    </cac:TaxSubtotal>
  </cac:TaxTotal>
  ';
   
  public $legalTotales_XMLPART = 
  '
  <cac:LegalMonetaryTotal>
    <cbc:PayableAmount currencyID="[moneda]">51731.20</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>';

  public $items_XMLPART = 
  '
  <cac:InvoiceLine>
    <cbc:ID>1</cbc:ID>
    <cbc:InvoicedQuantity unitCode="BX" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission forEurope">2000</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID="[moneda]">43840.00</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="[moneda]">38.00</cbc:PriceAmount>
        <cbc:PriceTypeCode listName="SUNAT:Indicador de Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="[moneda]">7891.20</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="[moneda]">43840.00</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID="[moneda]">7891.20</cbc:TaxAmount>
        <cac:TaxCategory>
          <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
          <cbc:Percent>18.00</cbc:Percent>
          <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="SUNAT:Codigo de Tipo de Afectación del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">10</cbc:TaxExemptionReasonCode>
          <cac:TaxScheme>
            <cbc:ID schemeID="UN/ECE 5153" schemeName="Tax Scheme Identifier" schemeAgencyName="United Nations Economic Commission for Europe">1000</cbc:ID>
            <cbc:Name>IGV</cbc:Name>
            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:Item>
      <cbc:Description>Cerveza Clásica x 12 bot. 620 ml.</cbc:Description>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="[moneda]">21.92</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>
</Invoice>';

  // Nombre de los archivos a generar con extension xml y zip
  public $name_xml;
  public $name_zip; 
  public $path_cert = "";
  public $path_data = "";
  public $path_envio_xml = "";
  public $path_envio_zip = "";
  public $extention_content_index = 1;

  public function __construct($documento)
  {  
    set_timezone();
    $this->empresa = get_empresa();
    $this->ruc_empresa = $this->empresa->EmpLin1;
    $this->nombre_empresa = $this->empresa->EmpNomb;
    $this->nombrecorto_empresa = $this->empresa->EmpLin5;
    $this->correlativo = $documento->VtaNume;

    if( $documento instanceof Resumen  ){
      $this->name = $documento->isResumen() ? $documento->nameFile() : $this->ruc_empresa ."-" . $documento->DocNume;
      $this->extention_content_index = 0;
    }

    else {
      $this->name = $this->ruc_empresa ."-" . $documento->TidCodi . "-" . $documento->VtaNume;    
    }

    $this->documento = $documento;
    $this->name_xml  = $this->name . '.xml';
    $this->name_zip = $this->name . '.zip';   

    // Rutas donde guardar los documentos
    $this->path_data = env('SAINFO_DATA') . $this->name_xml;
    $this->path_cert = env('SAINFO_CERT') . $this->ruc_empresa;
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

  public function setDatasPartsXml()
  {

    $this->change_datas([   
      ["codigo", $this->correlativo ],  
      ["fecha" , $this->documento->VtaFvta ],
      ["hora" , $this->documento->VtaHora ],
      ["cantidad_items" , '1' ],
      ["cantidad_letras" , 'CUATROCIENTOS VEINTITRES MIL DOSCIENTOS VEINTICINCO Y 00/100' ], //fx
    ],$this->documentInfo_XMLPART);

    $this->change_datas([   
      ["ruc_empresa",  $this->ruc_empresa ],
      ["nombre_empresa" , $this->nombre_empresa ],
    ],$this->firmaData_XMLPART);

    $this->change_datas([   
      ["nombrecorto_empresa",  $this->nombrecorto_empresa ],
      ["nombre_empresa" , $this->nombre_empresa ],
      ["ruc_empresa",  $this->ruc_empresa ],
    ],$this->empresaData_XMLPART);

    $this->change_datas([   
      ["nombre_cliente" , $this->documento->cliente->PCNomb ],
      ["ruc_cliente",  $this->documento->cliente->PCRucc ],
      ],$this->clienteData_XMLPART);
    

    $this->change_datas([   
    ],$this->totales_XMLPART);


    $this->change_datas([   
    ],$this->totales_XMLPART);

    return;
  }
///// setDatasPartsXml

  public function generar_confirma()
  { 
    $privateKey = $this->path_cert  . '.key';
    $publicKey  = $this->path_cert  . '.cer'; 
    
    if (!file_exists($privateKey))
    throw new \Exception('No se encuentra la LLAVE PRIVADA ' . $privateKey );

    if (!file_exists($publicKey))
    throw new \Exception('No se encuentra la LLAVE PUBLICA ' . $publicKey  );

    $ReferenceNodeName = 'ExtensionContent';

    // Load the XML to be signed
    $doc = new DOMDocument();
    $doc->load($this->path_data);
    $objDSig = new XMLSecurityDSig();
    $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
      
    $objDSig->addReference( 
      $doc, 
      XMLSecurityDSig::SHA1,
      ['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],
      ['force_uri' => true ]
    );

    $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));   
    $objKey->loadKey($privateKey, TRUE);
    $objDSig->sign($objKey,$doc->getElementsByTagName($ReferenceNodeName)->item(0));
    $options = ["subjectName" => true];
    $objDSig->add509Cert(file_get_contents($publicKey), true, false, $options );
    $objDSig->appendSignature($doc->getElementsByTagName($ReferenceNodeName)->item(0));
    return $doc;
  }


  public function guardar()
  {
    // --- Guardar en XMLData ---
    $this->generar_sinfirma(); 
    \File::put( $this->path_data , $this->xml);
    // --- Guardar en XMLEnvio ---
      // xml
      $this->generar_confirma()->save( $this->path_envio_xml );            

      // zip
      $zipper = new Zipper;
      $zipper
      ->make($this->path_envio_zip)
      ->add($this->path_envio_xml)
      ->close();

    $firma = self::extract_value( "DigestValue" , $this->path_envio_xml , $this->name_xml )[0];

    dar_permisos($this->path_data, $this->path_envio_xml, $this->path_envio_zip);

    return ['path' => $this->path_envio_zip , 'firma' => $firma ];
  }

}


/*
<?php

namespace App;

use App\HHCL;
use App\Resumen;
use DOMDocument;
use Illuminate\Database\Eloquent\Model;
use Chumper\Zipper\Zipper;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

class XmlNew 
{
  // String del xml completo
  public $xml;
  public $name;   
  public $documento;
  public $empresa;  
  public $empresa_ruc;
  public $correlativo;

  const MONEDA_ABREV = [
    'SOLES' => 'PEN',
    'DOLARES' => 'USD',   
  ]; 


  public $header_XMLPART = 
  '<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
         xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
         xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
         xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';

  public $documentInfo_XMLPART = 
  '
  <ext:UBLExtensions>
  <ext:UBLExtension>
  <ext:ExtensionContent></ext:ExtensionContent>
  </ext:UBLExtension>
  </ext:UBLExtensions>  
  <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
  <cbc:CustomizationID>2.0</cbc:CustomizationID>
  <cbc:ID>[codigo]</cbc:ID>
  <cbc:IssueDate>[fecha]</cbc:IssueDate>
  <cbc:IssueTime>[hora]</cbc:IssueTime>
  <cbc:InvoiceTypeCode listID="0101" listAgencyName="PE:SUNAT" listName="SUNAT:Identificador de Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">01</cbc:InvoiceTypeCode>
  <cbc:Note languageLocaleID="1000">[cantidad_letras]</cbc:Note>
  <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">[moneda]</cbc:DocumentCurrencyCode>
  <cbc:LineCountNumeric>[cantidad_items]</cbc:LineCountNumeric>';

  public $firmaData_XMLPART = 
  '
  <cac:Signature>
    <cbc:ID>IDSignSP</cbc:ID>
    <cac:SignatoryParty>
      <cac:PartyIdentification>
      <cbc:ID>[ruc_empresa]</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>[nombre_empresa]</cbc:Name>
      </cac:PartyName>
    </cac:SignatoryParty>
    <cac:DigitalSignatureAttachment>
      <cac:ExternalReference>
      <cbc:URI>#SignatureSP</cbc:URI>
      </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
  </cac:Signature>';
  
  public $empresaData_XMLPART = 
  '
  <cac:AccountingSupplierParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad">[ruc_empresa]</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name>[nombre_empresa]</cbc:Name>
      </cac:PartyName>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName>[nombre_empresa]</cbc:RegistrationName>
        <cac:RegistrationAddress>
          <cbc:AddressTypeCode>0001</cbc:AddressTypeCode>
        </cac:RegistrationAddress>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingSupplierParty>';

  public $clienteData_XMLPART = 
  '
<cac:AccountingCustomerParty>
  <cac:Party>
    <cac:PartyIdentification>
      <cbc:ID schemeID="6">[ruc_cliente]</cbc:ID>
    </cac:PartyIdentification>
    <cac:PartyLegalEntity>
      <cbc:RegistrationName>[nombre_cliente]</cbc:RegistrationName>
    </cac:PartyLegalEntity>
  </cac:Party>
</cac:AccountingCustomerParty>';

  public $totales_XMLPART = 
  '  
  <cac:TaxTotal>
    <cbc:TaxAmount currencyID="[moneda]">7891.20</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxableAmount currencyID="[moneda]">43840.00</cbc:TaxableAmount>
      <cbc:TaxAmount currencyID="[moneda]">7891.20</cbc:TaxAmount>
      <cac:TaxCategory>
        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
        <cac:TaxScheme>
          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
          <cbc:Name>IGV</cbc:Name>
          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
        </cac:TaxScheme>
      </cac:TaxCategory>
    </cac:TaxSubtotal>
  </cac:TaxTotal>
  ';
   
  public $legalTotales_XMLPART = 
  '
  <cac:LegalMonetaryTotal>
    <cbc:PayableAmount currencyID="[moneda]">51731.20</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>';

  public $items_XMLPART = 
  '
  <cac:InvoiceLine>
    <cbc:ID>1</cbc:ID>
    <cbc:InvoicedQuantity unitCode="BX" unitCodeListID="UN/ECE rec 20" unitCodeListAgencyName="United Nations Economic Commission forEurope">2000</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID="[moneda]">43840.00</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="[moneda]">38.00</cbc:PriceAmount>
        <cbc:PriceTypeCode listName="SUNAT:Indicador de Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="[moneda]">7891.20</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="[moneda]">43840.00</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID="[moneda]">7891.20</cbc:TaxAmount>
        <cac:TaxCategory>
          <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
          <cbc:Percent>18.00</cbc:Percent>
          <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="SUNAT:Codigo de Tipo de Afectación del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">10</cbc:TaxExemptionReasonCode>
          <cac:TaxScheme>
            <cbc:ID schemeID="UN/ECE 5153" schemeName="Tax Scheme Identifier" schemeAgencyName="United Nations Economic Commission for Europe">1000</cbc:ID>
            <cbc:Name>IGV</cbc:Name>
            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:Item>
      <cbc:Description>Cerveza Clásica x 12 bot. 620 ml.</cbc:Description>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="[moneda]">21.92</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>
</Invoice>';

  // Nombre de los archivos a generar con extension xml y zip
  public $name_xml;
  public $name_zip; 
  public $path_cert = "";
  public $path_data = "";
  public $path_envio_xml = "";
  public $path_envio_zip = "";
  public $extention_content_index = 1;

  public function __construct($documento)
  {  
    set_timezone();
    $this->empresa = get_empresa();
    $this->ruc_empresa = $this->empresa->EmpLin1;
    $this->nombre_empresa = $this->empresa->EmpNomb;
    $this->nombrecorto_empresa = $this->empresa->EmpLin5;
    $this->correlativo = $documento->VtaNume;

    if( $documento instanceof Resumen  ){
      $this->name = $documento->isResumen() ? $documento->nameFile() : $this->ruc_empresa ."-" . $documento->DocNume;
      $this->extention_content_index = 0;
    }

    else {
      $this->name = $this->ruc_empresa ."-" . $documento->TidCodi . "-" . $documento->VtaNume;    
    }

    $this->documento = $documento;
    $this->name_xml  = $this->name . '.xml';
    $this->name_zip = $this->name . '.zip';   

    // Rutas donde guardar los documentos
    $this->path_data = env('SAINFO_DATA') . $this->name_xml;
    $this->path_cert = env('SAINFO_CERT') . $this->ruc_empresa;
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

  public function setDatasPartsXml()
  {

    $this->change_datas([   
      ["codigo", $this->correlativo ],  
      ["fecha" , $this->documento->VtaFvta ],
      ["hora" , $this->documento->VtaHora ],
      ["cantidad_items" , '1' ],
      ["cantidad_letras" , 'CUATROCIENTOS VEINTITRES MIL DOSCIENTOS VEINTICINCO Y 00/100' ], //fx
    ],$this->documentInfo_XMLPART);

    $this->change_datas([   
      ["ruc_empresa",  $this->ruc_empresa ],
      ["nombre_empresa" , $this->nombre_empresa ],
    ],$this->firmaData_XMLPART);

    $this->change_datas([   
      ["nombrecorto_empresa",  $this->nombrecorto_empresa ],
      ["nombre_empresa" , $this->nombre_empresa ],
      ["ruc_empresa",  $this->ruc_empresa ],
    ],$this->empresaData_XMLPART);

    $this->change_datas([   
      ["nombre_cliente" , $this->documento->cliente->PCNomb ],
      ["ruc_cliente",  $this->documento->cliente->PCRucc ],
      ],$this->clienteData_XMLPART);
    

    $this->change_datas([   
    ],$this->totales_XMLPART);


    $this->change_datas([   
    ],$this->totales_XMLPART);

    return;
  }
///// setDatasPartsXml

  public function generar_confirma()
  { 
    $privateKey = $this->path_cert  . '.key';
    $publicKey  = $this->path_cert  . '.cer'; 
    
    if (!file_exists($privateKey))
    throw new \Exception('No se encuentra la LLAVE PRIVADA ' . $privateKey );

    if (!file_exists($publicKey))
    throw new \Exception('No se encuentra la LLAVE PUBLICA ' . $publicKey  );

    $ReferenceNodeName = 'ExtensionContent';

    // Load the XML to be signed
    $doc = new DOMDocument();
    $doc->load($this->path_data);
    $objDSig = new XMLSecurityDSig();
    $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
      
    $objDSig->addReference( 
      $doc, 
      XMLSecurityDSig::SHA1,
      ['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],
      ['force_uri' => true ]
    );

    $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));   
    $objKey->loadKey($privateKey, TRUE);
    $objDSig->sign($objKey,$doc->getElementsByTagName($ReferenceNodeName)->item(0));
    $options = ["subjectName" => true];
    $objDSig->add509Cert(file_get_contents($publicKey), true, false, $options );
    $objDSig->appendSignature($doc->getElementsByTagName($ReferenceNodeName)->item(0));
    return $doc;
  }


  public function guardar()
  {
    // --- Guardar en XMLData ---
    $this->generar_sinfirma(); 
    \File::put( $this->path_data , $this->xml);
    // --- Guardar en XMLEnvio ---
      // xml
      $this->generar_confirma()->save( $this->path_envio_xml );            

      // zip
      $zipper = new Zipper;
      $zipper
      ->make($this->path_envio_zip)
      ->add($this->path_envio_xml)
      ->close();

    $firma = self::extract_value( "DigestValue" , $this->path_envio_xml , $this->name_xml )[0];

    dar_permisos($this->path_data, $this->path_envio_xml, $this->path_envio_zip);

    return ['path' => $this->path_envio_zip , 'firma' => $firma ];
  }

}
*/
