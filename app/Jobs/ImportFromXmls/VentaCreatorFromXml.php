<?php

namespace App\Jobs\ImportFromXmls;

class VentaCreatorFromXml extends CreatorAbstract
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
    $this->data['tipo_documento'] = (string)$xml->xpath('//cbc:InvoiceTypeCode')[0] ?? '';
    $this->data['moneda'] = (string)$xml->xpath('//cbc:DocumentCurrencyCode')[0] ?? '';
    $this->data['cantidad_items'] = (string)$xml->xpath('//cbc:LineCountNumeric')[0] ?? '';
    
    // Extraer datos del emisor
    $emisorId = $xml->xpath('//cac:AccountingSupplierParty//cac:PartyIdentification//cbc:ID')[0] ?? null;
    if ($emisorId) {
      $this->data['tipo_documento_emisor'] = (string)$emisorId['schemeID'] ?? '';
      $this->data['ruc_emisor'] = (string)$emisorId;
    }
    
    $this->data['nombre_emisor'] = (string)$xml->xpath('//cac:AccountingSupplierParty//cac:PartyName//cbc:Name')[0] ?? '';
    $this->data['codigo_local'] = (string)$xml->xpath('//cac:AccountingSupplierParty//cac:PartyLegalEntity//cac:RegistrationAddress//cbc:AddressTypeCode')[0] ?? '';
    
    // Extraer datos del cliente
    $clienteId = $xml->xpath('//cac:AccountingCustomerParty//cac:PartyIdentification//cbc:ID')[0] ?? null;
    if ($clienteId) {
      $this->data['tipo_documento_cliente'] = (string)$clienteId['schemeID'] ?? '';
      $this->data['ruc_cliente'] = (string)$clienteId;
    }
    
    $this->data['nombre_cliente'] = (string)$xml->xpath('//cac:AccountingCustomerParty//cac:PartyLegalEntity//cbc:RegistrationName')[0] ?? '';
    
    // Extraer datos de forma de pago
    $this->data['forma_pago_nombre'] = (string)$xml->xpath('//cac:PaymentTerms//cbc:ID')[0] ?? '';
    $this->data['forma_pago'] = (string)$xml->xpath('//cac:PaymentTerms//cbc:PaymentMeansID')[0] ?? '';
    
    // Extraer totales
    $this->data['total_sinigv'] = (string)$xml->xpath('//cac:LegalMonetaryTotal//cbc:LineExtensionAmount')[0] ?? '';
    $this->data['total_conigv'] = (string)$xml->xpath('//cac:LegalMonetaryTotal//cbc:TaxInclusiveAmount')[0] ?? '';
    $this->data['total'] = (string)$xml->xpath('//cac:LegalMonetaryTotal//cbc:PayableAmount')[0] ?? '';
    
    // Extraer datos de los items
    $items = $xml->xpath('//cac:InvoiceLine');
    $this->data['items'] = [];
    
    foreach ($items as $index => $item) {
      $itemData = [];
      $itemData['item_orden'] = (string)$item->xpath('.//cbc:ID')[0] ?? '';
      $itemData['item_cantidad'] = (string)$item->xpath('.//cbc:InvoicedQuantity')[0] ?? '';
      $itemData['item_valor_bruto'] = (string)$item->xpath('.//cbc:LineExtensionAmount')[0] ?? '';
      $itemData['item_precio_bruto'] = (string)$item->xpath('.//cac:PricingReference//cac:AlternativeConditionPrice//cbc:PriceAmount')[0] ?? '';
      $itemData['item_tipo_precio'] = (string)$item->xpath('.//cac:PricingReference//cac:AlternativeConditionPrice//cbc:PriceTypeCode')[0] ?? '';
      $itemData['item_igv'] = (string)$item->xpath('.//cac:TaxTotal//cbc:TaxAmount')[0] ?? '';
      $itemData['item_igv_porc'] = (string)$item->xpath('.//cac:TaxCategory//cbc:Percent')[0] ?? '';
      $itemData['item_precio_unidad'] = (string)$item->xpath('.//cac:Price//cbc:PriceAmount')[0] ?? '';
      $itemData['item_descripcion'] = (string)$item->xpath('.//cac:Item//cbc:Description')[0] ?? '';
      
      $this->data['items'][] = $itemData;
    }
    
    return $this->data;
  }

  public function saveDataModel()
  {
    return (new VentaFromData($this->data, $this->empresa, $this->cacheTemp))->handle();
  }

}
