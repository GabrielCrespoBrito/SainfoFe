<?php

namespace App\Http\Controllers\Util\Xml\dos_uno;

trait PartsGuiaXMLApi
{
  ###################### HEADER ######################
  public $headers = [
    'guia' =>
    '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
      <DespatchAdvice xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2">'
  ];
  // De todas maneras 
  public $header_XMLPART = "";

  ###################### DOCUMENTO ######################
  public $documentsInfo_XMLPART =
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
  <cbc:IssueTime>[tiempo]</cbc:IssueTime>
  <cbc:DespatchAdviceTypeCode>[guiaTypeCode]</cbc:DespatchAdviceTypeCode>
  [documentoRelacionado]';

  // <cbc:Note><![CDATA[[nota]]]></cbc:Note>
  
  public $documentoRelacionado =
  '<cac:AdditionalDocumentReference>
      <cbc:ID>[id]</cbc:ID>
      <cbc:DocumentTypeCode>[tipo]</cbc:DocumentTypeCode>
      <cbc:DocumentType>[tipo_nombre]</cbc:DocumentType>
      <cac:IssuerParty>
        <cac:PartyIdentification>
          <cbc:ID schemeID="6">[ruc]</cbc:ID>
        </cac:PartyIdentification>
      </cac:IssuerParty>
    </cac:AdditionalDocumentReference>';

// /DespatchAdvice/cac:AdditionalDocumentReference/cac:IssuerParty/cac:PartyIdentification/cbc:ID (Número de documento del emisor del documento)

// /DespatchAdvice/cac:AdditionalDocumentReference/cac:IssuerParty/cac:PartyIdentification/cbc:ID (Número de documento del emisor del documento)
// /DespatchAdvice/cac:AdditionalDocumentReference/cac:IssuerParty/cac:PartyIdentification/cbc:ID@schemeID (Tipo de documento de identidad del emisor del documento relacionado)
// @schemeName
// @schemeAgencyName
// @schemeURI


  ###################### FIRMA ######################

  public $firmaData_XMLPART =
  '
  <cac:Signature>
    <cbc:ID  schemeID="6">[ruc_empresa]</cbc:ID>
    <cac:SignatoryParty>
      <cac:PartyIdentification>
      <cbc:ID>[ruc_empresa]</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name><![CDATA[[nombre_empresa]]]></cbc:Name>
      </cac:PartyName>
    </cac:SignatoryParty>
  </cac:Signature>';
  ###################### /FIRMA ######################


  ###################### EMPRESA ######################
  public $empresaData_XMLPART =
  '
  <cac:DespatchSupplierParty>
    <cbc:CustomerAssignedAccountID schemeID="6">[ruc_empresa]</cbc:CustomerAssignedAccountID>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6">[ruc_empresa]</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName><![CDATA[[nombre_empresa]]]></cbc:RegistrationName>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:DespatchSupplierParty>';
  ###################### /EMPRESA ######################

  ###################### CLIENTE y DESCUENTO ######################
  
  public $clienteData_XMLPART =
  '
  <cac:DeliveryCustomerParty>
    <cbc:CustomerAssignedAccountID schemeID="[cliente_tipo_documento]">[ruc_cliente]</cbc:CustomerAssignedAccountID>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="[cliente_tipo_documento]">[ruc_cliente]</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName><![CDATA[[nombre_cliente]]]></cbc:RegistrationName>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:DeliveryCustomerParty>';
  ###################### CLIENTE y DESCUENTO ######################

  ##### INFORMACIÓN DEL PROVEEDOR CCUANDO ES UNA GUI DE COMPRA #####

  public $proveedorData_base =
  '
   <cac:SellerSupplierParty>
    <cbc:CustomerAssignedAccountID schemeID="[tipo_documento_proveedor]">[ruc_proveedor]</cbc:CustomerAssignedAccountID>
    <cac:Party>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName><![CDATA[[nombre_proveedor]]]></cbc:RegistrationName>
      </cac:PartyLegalEntity>
    </cac:Party>
   </cac:SellerSupplierParty>
    ';

  public $proveedorData_XMLPART = '';

  public $shipment_XMLPART =
  '
  <cac:Shipment>
    <cbc:ID>1</cbc:ID>
    [motivo_traslado_codigo]    
    <cbc:GrossWeightMeasure unitCode="KGM">[peso]</cbc:GrossWeightMeasure>
    <cac:ShipmentStage>
      <cbc:ID>1</cbc:ID>
      [modalidad_transporte]
      <cac:TransitPeriod>
        <cbc:StartDate>[fecha_transporte]</cbc:StartDate>
      </cac:TransitPeriod>
      [empresa_transporte_data]
      [transportista_data]
      </cac:ShipmentStage>
      <cac:Delivery>
    <cac:DeliveryAddress>
      <cbc:ID>[ubigeo_llegada]</cbc:ID>
      [establecimiento_anexo_llegada]
      <cac:AddressLine>
      <cbc:Line><![CDATA[[direccion_llegada]]]></cbc:Line>
      </cac:AddressLine>
      </cac:DeliveryAddress>
      <cac:Despatch>
      <cac:DespatchAddress>
      <cbc:ID>[ubigeo_partida]</cbc:ID>
      [establecimiento_anexo_partida]
      <cac:AddressLine>
      <cbc:Line><![CDATA[[direccion_partida]]]></cbc:Line>
      </cac:AddressLine>
      </cac:DespatchAddress>
      [info_remitente]
      </cac:Despatch>
      </cac:Delivery>
    [vehiculo_data]     
  </cac:Shipment>';
  ###################### TAX_TOTALES  ######################

  public $motivoTransporte_base = "<cbc:HandlingCode>[motivo_traslado_codigo]</cbc:HandlingCode> ";

  public $modalidadTransporte_base = "<cbc:TransportModeCode>[modalidad_transporte]</cbc:TransportModeCode>";

  public $constanciaInscripcion_base =
  "<cac:ApplicableTransportMeans>
    <cbc:RegistrationNationalityID>[constancia]</cbc:RegistrationNationalityID>
  </cac:ApplicableTransportMeans>";

  // --------------------------------------------

  public $item_base =
  '<cac:DespatchLine>
    <cbc:ID>[id_item_guia]</cbc:ID>
    <cbc:DeliveredQuantity unitCode="[unidad]">[cantidad]</cbc:DeliveredQuantity>
    <cac:OrderLineReference>
      <cbc:LineID>[id_item_documento]</cbc:LineID>
    </cac:OrderLineReference>
    <cac:Item>
      <cbc:Description><![CDATA[[nombre_producto]]]></cbc:Description>
      <cac:SellersItemIdentification>
        <cbc:ID>[id_producto]</cbc:ID>
      </cac:SellersItemIdentification>
    </cac:Item>
  </cac:DespatchLine>';

  public $despatch_base =
  '<cac:DespatchParty>
    <cac:PartyIdentification>
      <cbc:ID schemeID="[tipo_documento]" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">[documento]</cbc:ID>
    </cac:PartyIdentification>
    <cac:PartyLegalEntity>
      <cbc:RegistrationName><![CDATA[[nombre]]]></cbc:RegistrationName>
    </cac:PartyLegalEntity>
  </cac:DespatchParty>';

  // Establecimientos Anexos
  public $e_anexo_base =
  '
  <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos" listID="[ruc]">[codigo]</cbc:AddressTypeCode>';

  public $empresaTransporteXML =
  '<cac:CarrierParty>
    <cac:PartyIdentification>
      <cbc:ID schemeID="6">[documento]</cbc:ID>
    </cac:PartyIdentification>
    <cac:PartyLegalEntity>
      <cbc:RegistrationName><![CDATA[[nombre]]]></cbc:RegistrationName>
      <cbc:CompanyID><![CDATA[[mtc]]]></cbc:CompanyID>
    </cac:PartyLegalEntity>
  </cac:CarrierParty>';

  public $transportistaXML =
  '<cac:DriverPerson>
      <cbc:ID schemeID="[tipo_documento]">[documento]</cbc:ID>
      <cbc:FirstName>[nombres]</cbc:FirstName>
      <cbc:FamilyName>[apellidos]</cbc:FamilyName>
      <cbc:JobTitle>Principal</cbc:JobTitle>
      <cac:IdentityDocumentReference>
        <cbc:ID>[licencia]</cbc:ID>
      </cac:IdentityDocumentReference>
    </cac:DriverPerson>
  ';

  public $vehiculoXML =
  '<cac:TransportHandlingUnit>
			<cac:TransportEquipment>
				<cbc:ID>[placa]</cbc:ID>
        [constancia_inscripcion]
			</cac:TransportEquipment>
		</cac:TransportHandlingUnit>	
  ';

  public $items_XMLPART = '';

  public $footers = [
    'guia' => "</DespatchAdvice>"
  ];

  public $footer_XMLPART = "";
  ###################### FOOTER ######################
}