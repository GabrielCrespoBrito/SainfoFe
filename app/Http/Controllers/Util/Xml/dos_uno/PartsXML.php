<?php

namespace App\Http\Controllers\Util\Xml\dos_uno;

trait PartsXML
{

  ###################### HEADER ######################
  public $headers = [    
    '01' =>
'<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" 
xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">',
  '03' => 
'<?xml version="1.0" encoding="UTF-8"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
xmlns:ccts="urn:un:unece:uncefact:documentation:2"
xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">',
  '07' =>
'<?xml version="1.0" encoding="UTF-8"?>
<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2"
xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
xmlns:ccts="urn:un:unece:uncefact:documentation:2"
xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"
xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">',
  '08' =>
'<?xml version="1.0" encoding="UTF-8"?>
<DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2"
xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
xmlns:ccts="urn:un:unece:uncefact:documentation:2"
xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"
xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'];
  
  // 
  public $header_XMLPART = '';

  ###################### /HEADER ######################

  ###################### DOCUMENTO ######################
  public $documentsInfo = 
  [ '0103' =>' 
    <ext:UBLExtensions>
    <ext:UBLExtension>
    <ext:ExtensionContent></ext:ExtensionContent>
    </ext:UBLExtension>
    </ext:UBLExtensions>  
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>2.0</cbc:CustomizationID>
    <cbc:ProfileID>[tipo_operacion]</cbc:ProfileID>
    <cbc:ID>[codigo]</cbc:ID>
    <cbc:IssueDate>[fecha]</cbc:IssueDate>
    <cbc:IssueTime>[hora]</cbc:IssueTime>
    <cbc:InvoiceTypeCode listID="[tipo_operacion]">[tipo_documento]</cbc:InvoiceTypeCode>[notas]
    <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha">[moneda]</cbc:DocumentCurrencyCode>
    <cbc:LineCountNumeric>[cantidad_items]</cbc:LineCountNumeric>
    [orden_compra][guia_referencia][docref_anticipo]',
  
    '0708' =>
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
    <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha">[moneda]</cbc:DocumentCurrencyCode>
    <cac:DiscrepancyResponse>
      <cbc:ReferenceID>[codigo_referencia]</cbc:ReferenceID>
      <cbc:ResponseCode>[razon_envio]</cbc:ResponseCode>
      <cbc:Description>[descripcion]</cbc:Description>
    </cac:DiscrepancyResponse>
    <cac:BillingReference>
      <cac:InvoiceDocumentReference>
        <cbc:ID>[codigo_referencia]</cbc:ID>
        <cbc:DocumentTypeCode>[tipo_documento_refererencia]</cbc:DocumentTypeCode>
      </cac:InvoiceDocumentReference>
    </cac:BillingReference>'
  ];

  public $documentInfo_XMLPART = '';
  ###################### /DOCUMENTO ######################

  public $anticipo_referencia_base =
  '<cac:AdditionalDocumentReference>
    <cbc:ID>[correlativo]</cbc:ID>
    <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Documento Relacionado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo12">[tipo_documento]</cbc:DocumentTypeCode>
    <cbc:DocumentStatusCode listName="Anticipo" listAgencyName="PE:SUNAT">[documento_status]</cbc:DocumentStatusCode>
    <cac:IssuerParty>
      <cac:PartyIdentification>
        <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="[tipo_documento_identidad]" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">[documento_identidad]</cbc:ID>
      </cac:PartyIdentification>
    </cac:IssuerParty>
  </cac:AdditionalDocumentReference>
  ';


  ###################### FIRMA ######################
  public $firmaData_XMLPART = 
  '
  <cac:Signature>
    <cbc:ID>IDSignSP</cbc:ID>
    <cac:SignatoryParty>
      <cac:PartyIdentification>
      <cbc:ID>[ruc_empresa]</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name><![CDATA[[nombre_empresa]]]></cbc:Name>
      </cac:PartyName>
    </cac:SignatoryParty>
    <cac:DigitalSignatureAttachment>
      <cac:ExternalReference>
      <cbc:URI>#SignatureSP</cbc:URI>
      </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
  </cac:Signature>';
  ###################### /FIRMA ######################


  ###################### EMPRESA ######################
  public $empresaData_XMLPART = 
  '<cac:AccountingSupplierParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6">[ruc_empresa]</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name><![CDATA[[nombre_empresa]]]></cbc:Name>
      </cac:PartyName>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName><![CDATA[[nombre_empresa]]]></cbc:RegistrationName>
        <cac:RegistrationAddress>
          <cbc:AddressTypeCode>[codigo_local]</cbc:AddressTypeCode>
        </cac:RegistrationAddress>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingSupplierParty>';
  ###################### /EMPRESA ######################


  ###################### CLIENTE y DESCUENTO ######################
  public $clienteData_XMLPART = 
  '
<cac:AccountingCustomerParty>
  <cac:Party>
    <cac:PartyIdentification>
      <cbc:ID schemeID="[cliente_tipo_documento]">[ruc_cliente]</cbc:ID>
    </cac:PartyIdentification>
    <cac:PartyLegalEntity>
      <cbc:RegistrationName><![CDATA[[nombre_cliente]]]></cbc:RegistrationName>
    </cac:PartyLegalEntity>
  </cac:Party>
</cac:AccountingCustomerParty>';
  ###################### CLIENTE y DESCUENTO ######################

  ###################### DETRACCIÓN ######################
  public $detraccion_XMLPART = "[detraccion]";

  ###################### FORMAS DE PAGO  ######################
  public $formaPagoData_XMLPART ="
  [forma_pago]";

 ###################### ANTICIPOS ######################

  public $anticipos_XMLPART = "[anticipo]";

  public $anticipo_base = '  
  <cac:PrepaidPayment>
    <cbc:ID schemeName="Anticipo" schemeAgencyName="PE:SUNAT">[anticipo_code]</cbc:ID>
    <cbc:PaidAmount currencyID="PEN">[total_anticipo]</cbc:PaidAmount>
  </cac:PrepaidPayment>';

  ###################### ANTICIPOS ######################




  public $detraccionData_base = '
    <cac:PaymentMeans>
    <cbc:ID>Detraccion</cbc:ID>
    <cbc:PaymentMeansCode listAgencyName="PE:SUNAT" listName="Medio de pago" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo59">[tipo_pago]</cbc:PaymentMeansCode>
    <cac:PayeeFinancialAccount>
      <cbc:ID>[banco_de_la_nacion]</cbc:ID>
    </cac:PayeeFinancialAccount>
  </cac:PaymentMeans>
  <cac:PaymentTerms>
    <cbc:ID>Detraccion</cbc:ID>
    <cbc:PaymentMeansID schemeAgencyName="PE:SUNAT" schemeName="Codigo de detraccion" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo54">[cod_detraccion]</cbc:PaymentMeansID>
    <cbc:PaymentPercent>[porc_detraccion]</cbc:PaymentPercent>
    <cbc:Amount currencyID="[moneda]">[total_detraccion]</cbc:Amount>
  </cac:PaymentTerms>';
  ###################### DETRACCIÓN ######################

  public $descuentoGlobal_XMLPART = 
  '[descuento_global]';
  ###################### CLIENTE y DESCUENTO ######################

  ###################### TAX_TOTALES  ######################
  public $totales_XMLPART = 
  '  
  <cac:TaxTotal>
    <cbc:TaxAmount currencyID="[moneda]">[total_tax]</cbc:TaxAmount>[subtotales]
  </cac:TaxTotal>';
  ###################### TAX_TOTALES  ######################
   

  ###################### LEGALMONETARY_TOTAL  ######################
  public $legalTotales_XMLPART = "";

  public $legaltotales = [

      '010307' => '
    <cac:LegalMonetaryTotal>
      <cbc:LineExtensionAmount currencyID="[moneda]">[LineExtensionAmount]</cbc:LineExtensionAmount>
      <cbc:TaxInclusiveAmount currencyID="[moneda]">[TaxInclusiveAmount]</cbc:TaxInclusiveAmount>'. 
      '
      <cbc:AllowanceTotalAmount currencyID="[moneda]">[AllowanceTotalAmount]</cbc:AllowanceTotalAmount>'.
      '
      <cbc:ChargeTotalAmount currencyID="[moneda]">[ChargeTotalAmount]</cbc:ChargeTotalAmount>
      <cbc:PrepaidAmount currencyID="[moneda]">[PrepaidAmount]</cbc:PrepaidAmount>   
      <cbc:PayableAmount currencyID="[moneda]">[PayableAmount]</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    ',

    '07' => '
  <cac:LegalMonetaryTotal>
    <cbc:PayableAmount currencyID="[moneda]">[PayableAmount]</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>
  ',     
    '08' => '
     <cac:RequestedMonetaryTotal>
      <cbc:PayableAmount currencyID="[moneda]">[PayableAmount]</cbc:PayableAmount>
    </cac:RequestedMonetaryTotal>
    '
  ];

  ###################### LEGALMONETARY_TOTAL  ######################
      // <cbc:Description><![CDATA[ [descripcion]]]></cbc:Description>


  ###################### ITEM ######################
  public $item_base =
  '
  <cac:[tipoDocumentoLine]>
    <cbc:ID>[orden]</cbc:ID>
    <cbc:[tipoDocumentoQuantity] unitCode="NIU">[cantidad]</cbc:[tipoDocumentoQuantity]>
    <cbc:LineExtensionAmount currencyID="[moneda]">[valorVentaItem]</cbc:LineExtensionAmount>    
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="[moneda]">[precio_item]</cbc:PriceAmount>
        <cbc:PriceTypeCode>[tipo_precio]</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>[descuento][tax_total]
    <cac:Item>
      <cbc:Description><![CDATA[[descripcion]]]></cbc:Description>[item_info]
    </cac:Item>[codproductosunat]
    <cac:Price>
      <cbc:PriceAmount currencyID="[moneda]">[valorUnitarioPorItem]</cbc:PriceAmount>
    </cac:Price>
  </cac:[tipoDocumentoLine]>';

  public $item_taxtotal_base = 
  '
  <cac:TaxTotal>
    <cbc:TaxAmount currencyID="[moneda]">[igv_total]</cbc:TaxAmount>[subtotal]    
  </cac:TaxTotal>';

  public $taxSubtotal_base =
  '
  <cac:TaxSubtotal>[taxableammount]
    <cbc:TaxAmount currencyID="[moneda]">[igv_total]</cbc:TaxAmount>
    <cac:TaxCategory>[TaxCategory_ID][porcentaje][TaxExemptionReasonCode][tipoISC]
      <cac:TaxScheme>
        <cbc:ID>[TaxScheme_ID]</cbc:ID>
        <cbc:Name>[TaxScheme_Name]</cbc:Name>
        <cbc:TaxTypeCode>[TaxScheme_TaxTypeCode]</cbc:TaxTypeCode>
      </cac:TaxScheme>
    </cac:TaxCategory>
  </cac:TaxSubtotal>';

  // totales_subtotales
  public $subtotales_arr = [
    'GRAVADA'   => [ 'exist' => false , 'valorVentaItem' => "0.00" , "igv_total" => "0.00" ],
    'INAFECTA'  => [ 'exist' => false , 'valorVentaItem' => "0.00" , "igv_total" => "0.00" ],
    'EXONERADA' => [ 'exist' => false , 'valorVentaItem' => "0.00" , "igv_total" => "0.00" ],
    'GRATUITA'  => [ 'exist' => false , 'valorVentaItem' => "0.00",  "igv_total" => "0.00" ],
    'ISC'       => [ 'exist' => false , 'valorVentaItem' => "0.00" , "igv_total" => "0.00" ],
    'ICBPER'    => [ 'exist' => false , 'valorVentaItem' => "0.00" , "igv_total" => "0.00" ],
  ];

  public $taxSubtotales = '';

  public $descuento_base =     
  '
  <cac:AllowanceCharge>
    <cbc:ChargeIndicator>[indicador]</cbc:ChargeIndicator>
    <cbc:AllowanceChargeReasonCode>[razon_code]</cbc:AllowanceChargeReasonCode>
    <cbc:MultiplierFactorNumeric>[porcentaje]</cbc:MultiplierFactorNumeric>
    <cbc:Amount currencyID="[moneda]">[total_descuento]</cbc:Amount>
    <cbc:BaseAmount currencyID="[moneda]">[cifra_a_descontar]</cbc:BaseAmount>
  </cac:AllowanceCharge>';

  public $items_XMLPART = '';
  ###################### /ITEM ######################


  ###################### FOOTER ######################
  public $footers = [
    '01' => 
    '
</Invoice>' , 
    '03' => '
</Invoice>' , 
    '07' => '
</CreditNote>', 
    '08' => '
</DebitNote>'
];

  public $footer_XMLPART = "";
  ###################### FOOTER ######################

}
