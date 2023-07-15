<?php

namespace App\Http\Controllers\Util\Xml\dos_uno;

trait PartsGuiaXML
{
  ###################### HEADER ######################
  public $headers = [        
    'guia' =>
    '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
      <DespatchAdvice xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2">'
  ];

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
  <cbc:CustomizationID>1.0</cbc:CustomizationID>
  <cbc:ID>[codigo]</cbc:ID>
  <cbc:IssueDate>[fecha]</cbc:IssueDate>
  <cbc:DespatchAdviceTypeCode>[guiaTypeCode]</cbc:DespatchAdviceTypeCode>    
  <cbc:Note><![CDATA[[nota]]]></cbc:Note>';

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
      <cbc:URI>[ruc_empresa]</cbc:URI>
      </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
  </cac:Signature>';
  ###################### /FIRMA ######################


  ###################### EMPRESA ######################
  public $empresaData_XMLPART = 
  '
  <cac:DespatchSupplierParty>
    <cbc:CustomerAssignedAccountID schemeID="6">[ruc_empresa]</cbc:CustomerAssignedAccountID>
    <cac:Party>
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
      <cac:PartyLegalEntity>
        <cbc:RegistrationName><![CDATA[[nombre_cliente]]]></cbc:RegistrationName>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:DeliveryCustomerParty>';
  ###################### CLIENTE y DESCUENTO ######################


  ###################### INFORMACIÓN DEL PROVEEDOR CCUANDO ES UNA GUI DE COMPRA ######################

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
  ###################### INFORMACIÓN DEL PROVEEDOR CCUANDO ES UNA GUI DE COMPRA ######################




  ###################### TAX_TOTALES  ######################
    // <cbc:TotalTransportHandlingUnitQuantity>[cantidad]</cbc:TotalTransportHandlingUnitQuantity>
  public $shipment_XMLPART =

  '<cac:Shipment>
    <cbc:ID>1</cbc:ID>
    <cbc:HandlingCode>[motivo_traslado_codigo]</cbc:HandlingCode>
    <cbc:Information>[motivo_traslado_nombre]</cbc:Information>
    <cbc:GrossWeightMeasure unitCode="KGM">[peso]</cbc:GrossWeightMeasure>
    <cac:ShipmentStage>
      <cbc:ID>1</cbc:ID>
      <cbc:TransportModeCode>01</cbc:TransportModeCode>
      <cac:TransitPeriod>
        <cbc:StartDate>[fecha_transporte]</cbc:StartDate>
      </cac:TransitPeriod>
      <cac:CarrierParty>
        <cac:PartyIdentification>
          <cbc:ID schemeID="-">123</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyName>
          <cbc:Name><![CDATA[[nombre_transportista]]]></cbc:Name>
        </cac:PartyName>
      </cac:CarrierParty>
      <cac:TransportMeans>
        <cac:RoadTransport>
          <cbc:LicensePlateID>[transportista_licencia]</cbc:LicensePlateID>
        </cac:RoadTransport>
      </cac:TransportMeans>      
      <cac:DriverPerson>
        <cbc:ID schemeID="[transportista_tipodocumento]">[transportista_ruc]</cbc:ID>
      </cac:DriverPerson>
    </cac:ShipmentStage>
    <cac:Delivery>
      <cac:DeliveryAddress>
        <cbc:ID>[ubigeo_llegada]</cbc:ID>
        <cbc:StreetName><![CDATA[[direccion_llegada]]]></cbc:StreetName>        
    </cac:DeliveryAddress>
    </cac:Delivery>
    <cac:TransportHandlingUnit>
      <cbc:ID>[placa_vehiculo]</cbc:ID>
    </cac:TransportHandlingUnit>
    <cac:OriginAddress>
      <cbc:ID>[ubigeo_partida]</cbc:ID>
      <cbc:StreetName><![CDATA[[direccion_partida]]]></cbc:StreetName>
    </cac:OriginAddress>
  </cac:Shipment> ';
  ###################### TAX_TOTALES  ######################

  public $item_base =
  '<cac:DespatchLine>
    <cbc:ID>[id_item_guia]</cbc:ID>
    <cbc:DeliveredQuantity unitCode="[unidad]">[cantidad]</cbc:DeliveredQuantity>
    <cac:OrderLineReference>
      <cbc:LineID>[id_item_documento]</cbc:LineID>
    </cac:OrderLineReference>
    <cac:Item>
      <cbc:Name><![CDATA[[nombre_producto]]]></cbc:Name>
      <cac:SellersItemIdentification>
        <cbc:ID>[id_producto]</cbc:ID>
      </cac:SellersItemIdentification>
    </cac:Item>
  </cac:DespatchLine>';

  public $items_XMLPART = '';
  
  public $footers = [
    'guia' => "</DespatchAdvice>"
  ];

  public $footer_XMLPART = "";
  ###################### FOOTER ######################

}