<?php

namespace App\Jobs\ImportFromXmls;

use App\Util\ResultTrait;

class GuiaRemisionCreatorFromXml extends CreatorAbstract
{
  public function generateData()
  {
    $this->data = [];
    
    // Crear un objeto SimpleXMLElement para parsear el XML
    $xml = simplexml_load_string($this->xmlContent);
    
    if ($xml === false) {
      throw new \Exception('Error al parsear el XML');
    }
    
    // Registrar los namespaces necesarios
    $xml->registerXPathNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
    $xml->registerXPathNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
    
    // Extraer datos básicos del documento
    $this->data['documento_correlativo'] = (string)$xml->xpath('//cbc:ID')[0] ?? '';
    $this->data['fecha_emision'] = (string)$xml->xpath('//cbc:IssueDate')[0] ?? '';
    $this->data['hora_emision'] = (string)$xml->xpath('//cbc:IssueTime')[0] ?? '';
    
    // Extraer datos del emisor (DespatchSupplierParty)
    $emisorId = $xml->xpath('//cac:DespatchSupplierParty//cac:Party//cac:PartyIdentification//cbc:ID')[0] ?? null;
    if ($emisorId) {
      $this->data['tipo_documento_emisor'] = (string)$emisorId['schemeID'] ?? '';
      $this->data['ruc_emisor'] = (string)$emisorId;
    }
    
    $this->data['razon_social_emisor'] = (string)$xml->xpath('//cac:DespatchSupplierParty//cac:Party//cac:PartyLegalEntity//cbc:RegistrationName')[0] ?? '';
    
    // Extraer datos del cliente (DeliveryCustomerParty)
    $clienteId = $xml->xpath('//cac:DeliveryCustomerParty//cac:Party//cac:PartyIdentification//cbc:ID')[0] ?? null;
    if ($clienteId) {
      $this->data['tipo_documento_cliente'] = (string)$clienteId['schemeID'] ?? '';
      $this->data['ruc_cliente'] = (string)$clienteId;
    }
    
    $this->data['razon_social_cliente'] = (string)$xml->xpath('//cac:DeliveryCustomerParty//cac:Party//cac:PartyLegalEntity//cbc:RegistrationName')[0] ?? '';
    
    // Extraer datos del transportista (CarrierParty)
    $transportistaId = $xml->xpath('//cac:CarrierParty//cac:PartyIdentification//cbc:ID')[0] ?? null;
    if ($transportistaId) {
      $this->data['ruc_transportista'] = (string)$transportistaId;
    }
    
    $this->data['razon_social_transportista'] = (string)$xml->xpath('//cac:CarrierParty//cac:PartyLegalEntity//cbc:RegistrationName')[0] ?? '';
    $this->data['item_mtc'] = (string)$xml->xpath('//cac:CarrierParty//cac:PartyLegalEntity//cbc:CompanyID')[0] ?? '';
    
    // Extraer datos del envío (Shipment)
    $this->data['item_id'] = (string)$xml->xpath('//cac:Shipment//cbc:ID')[0] ?? '';
    $this->data['item_peso'] = (string)$xml->xpath('//cac:Shipment//cbc:GrossWeightMeasure')[0] ?? '';
    $this->data['item_modalidad_transporte'] = (string)$xml->xpath('//cac:ShipmentStage//cbc:TransportModeCode')[0] ?? '';
    $this->data['item_fecha_transporte'] = (string)$xml->xpath('//cac:TransitPeriod//cbc:StartDate')[0] ?? '';
    
    // Extraer datos de direcciones
    $this->data['item_ubigeo_llegada'] = (string)$xml->xpath('//cac:Delivery//cac:DeliveryAddress//cbc:ID')[0] ?? '';
    $this->data['item_direccion_llegada'] = (string)$xml->xpath('//cac:Delivery//cac:DeliveryAddress//cac:AddressLine//cbc:Line')[0] ?? '';
    $this->data['item_ubigeo_partida'] = (string)$xml->xpath('//cac:Despatch//cac:DespatchAddress//cbc:ID')[0] ?? '';
    $this->data['item_direccion_partida'] = (string)$xml->xpath('//cac:Despatch//cac:DespatchAddress//cac:AddressLine//cbc:Line')[0] ?? '';
    
    // Extraer datos de los items (DespatchLine)
    $items = $xml->xpath('//cac:DespatchLine');
    $this->data['items'] = [];
    
    foreach ($items as $index => $item) {
      $itemData = [];
      $itemData['item_id'] = (string)$item->xpath('.//cbc:ID')[0] ?? '';
      $itemData['item_cantidad'] = (string)$item->xpath('.//cbc:DeliveredQuantity')[0] ?? '';
      $itemData['item_descripcion'] = (string)$item->xpath('.//cac:Item//cbc:Description')[0] ?? '';
      $itemData['item_line_id'] = (string)$item->xpath('.//cac:OrderLineReference//cbc:LineID')[0] ?? '';
      $itemData['item_sellers_id'] = (string)$item->xpath('.//cac:SellersItemIdentification//cbc:ID')[0] ?? '';
      
      $this->data['items'][] = $itemData;
    }
    
    return $this->data;
  }
  
  public function saveDataModel()
  {
  }
}