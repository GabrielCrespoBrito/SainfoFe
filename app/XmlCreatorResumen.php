<?php

namespace App;
use Chumper\Zipper\Zipper;

class XmlCreatorResumen extends XmlHelper
{

  // header del xml 
  public $header_part_xml = '<?xml version="1.0" encoding="UTF-8"?>
<SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:ext ="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1">';  

  public $firma_part_xml = 
'<ext:UBLExtensions>
  <ext:UBLExtension>
    <ext:ExtensionContent></ext:ExtensionContent>
  </ext:UBLExtension>
</ext:UBLExtensions>
';

  public $version_part_xml = 
'<cbc:UBLVersionID>2.0</cbc:UBLVersionID>
<cbc:CustomizationID>1.1</cbc:CustomizationID>
<cbc:ID>[id]</cbc:ID>
<cbc:ReferenceDate>[fecha_creacion]</cbc:ReferenceDate>
<cbc:IssueDate>[fecha_envio]</cbc:IssueDate>
';

  public $iden_venta_part_xml = 
'<cac:Signature>
  <cbc:ID>IDSignSP</cbc:ID>
  <cac:SignatoryParty>
    <cac:PartyIdentification>
      <cbc:ID>[empresa_ruc]</cbc:ID>
    </cac:PartyIdentification>
    <cac:PartyName>
      <cbc:Name><![CDATA[[empresa_nombre]]]></cbc:Name>
    </cac:PartyName>
  </cac:SignatoryParty>
  <cac:DigitalSignatureAttachment>
    <cac:ExternalReference>
      <cbc:URI>#SignatureSP</cbc:URI>
    </cac:ExternalReference>
  </cac:DigitalSignatureAttachment>
</cac:Signature>
';

  // AdditionalAccountID ?
  public $empresa_part_xml = 
'<cac:AccountingSupplierParty>
  <cbc:CustomerAssignedAccountID>[empresa_ruc]</cbc:CustomerAssignedAccountID>
  <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
  <cac:Party>
    <cac:PartyLegalEntity>
      <cbc:RegistrationName><![CDATA[[empresa_razon_social]]]></cbc:RegistrationName>
    </cac:PartyLegalEntity>
  </cac:Party>
</cac:AccountingSupplierParty>
  ';

  public $items_part_xml = '';

  public $item_base =
  '<sac:SummaryDocumentsLine>
  <cbc:LineID>[item_id]</cbc:LineID>
    <cbc:DocumentTypeCode>[tipodocumento]</cbc:DocumentTypeCode>
    <cbc:ID>[numero_serie_venta]</cbc:ID>
    <cac:AccountingCustomerParty>
      <cbc:CustomerAssignedAccountID>[cliente_documento]</cbc:CustomerAssignedAccountID>
      <cbc:AdditionalAccountID>[cliente_tipo_documento]</cbc:AdditionalAccountID>
    </cac:AccountingCustomerParty>
  [BillingReference]
   <cac:Status>
      <cbc:ConditionCode>[condicion_code]</cbc:ConditionCode>
    </cac:Status>
   <sac:TotalAmount currencyID="PEN">[total_item]</sac:TotalAmount>
    [billing]
    <cac:TaxTotal>
    <cbc:TaxAmount currencyID="PEN">[isc_total]</cbc:TaxAmount>
       <cac:TaxSubtotal>
         <cbc:TaxAmount currencyID="PEN">[isc_total]</cbc:TaxAmount>
         <cac:TaxCategory>
             <cac:TaxScheme>
                     <cbc:ID>2000</cbc:ID>
                     <cbc:Name>ISC</cbc:Name>
                     <cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>
             </cac:TaxScheme>
         </cac:TaxCategory>
       </cac:TaxSubtotal>
    </cac:TaxTotal> 
    <cac:TaxTotal>
    <cbc:TaxAmount currencyID="PEN">[icbper_total]</cbc:TaxAmount>
       <cac:TaxSubtotal>
         <cbc:TaxAmount currencyID="PEN">[icbper_total]</cbc:TaxAmount>
         <cac:TaxCategory>
             <cac:TaxScheme>
                     <cbc:ID>7152</cbc:ID>
                     <cbc:Name>ICBPER</cbc:Name>
                     <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
             </cac:TaxScheme>
         </cac:TaxCategory>
       </cac:TaxSubtotal>
    </cac:TaxTotal>           
    <cac:TaxTotal>
         <cbc:TaxAmount currencyID="PEN">[igv_total]</cbc:TaxAmount>
         <cac:TaxSubtotal>
         <cbc:TaxAmount currencyID="PEN">[igv_total]</cbc:TaxAmount>
         <cac:TaxCategory>
             <cac:TaxScheme>
                 <cbc:ID>1000</cbc:ID>
                 <cbc:Name>IGV</cbc:Name>
                 <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
             </cac:TaxScheme>
         </cac:TaxCategory>
         </cac:TaxSubtotal>
     </cac:TaxTotal>
</sac:SummaryDocumentsLine>
';

public $billingReferenceBase = 
"<cac:BillingReference>
  <cac:InvoiceDocumentReference>
    <cbc:ID>[documento]</cbc:ID>
    <cbc:DocumentTypeCode>[tipo]</cbc:DocumentTypeCode>
  </cac:InvoiceDocumentReference>
</cac:BillingReference>";


  public $footer_part_xml = 
  '</SummaryDocuments>';


  public function getBillingReference($item , $isAnulacion = null ){
    if( $item->vtatdr ){
      return $this->change_datas([
        ["tipo" , $item->vtatdr ],
        ["documento" , $item->vtaserir . "-" . $item->vtanumer ],      
      ], $this->billingReferenceBase , false );

    }
    return '';
  }


  public function getBilling($item ){

    $base = 
      '<sac:BillingPayment>
        <cbc:PaidAmount currencyID="PEN">%s</cbc:PaidAmount>
        <cbc:InstructionID>%s</cbc:InstructionID>
      </sac:BillingPayment>';

    $billings = "";

    if( (bool) (int) $item->DetGrav ){
      $billings .= sprintf( $base , $item->DetGrav , "01" );
    }
    if( (bool) (int) $item->DetExon ){
      $billings .= sprintf( $base , $item->DetExon , "02" );
    }
    if( (bool) (int) $item->DetInaf ){
      $billings .= sprintf( $base , $item->DetInaf , "03" );
    }

    return $billings;

  }

  public function setDatasPartsXml()
  {
    $this->change_datas([
      ["id" , $this->documento->DocNume ],            
      ["fecha_creacion" , $this->documento->DocFechaE ],
      ["fecha_envio" , $this->documento->DocFechaE ],
    ],$this->version_part_xml );


    $this->change_datas([
      ["empresa_ruc" , $this->empresa->EmpLin1 ],
      ["empresa_nombre" , $this->empresa->EmpNomb ],      
    ],$this->iden_venta_part_xml );

    $this->change_datas([
      ["empresa_ruc" , $this->empresa->EmpLin1 ],
      ["empresa_nombre" , $this->empresa->EmpNomb ],      
    ],$this->iden_venta_part_xml );


    $this->change_datas([ 
      [ "empresa_ruc" , $this->empresa->EmpLin1 ], 
      [ "empresa_id" , 6 ], 
      [ "nombre_empresa" , $this->empresa->EmpNomb ],       
      [ "empresa_direccion" , $this->empresa->EmpLin2 ],      
      ["empresa_razon_social" , $this->empresa->EmpNomb  ]    
    ], $this->empresa_part_xml );
    //

    $items = $this->documento->getItems();

    foreach( $items as $item ) 
    {
      $b = $item->Detbase;
      $condicion_code = $this->documento->isAnulacion() ? 3 : 1;
      // $documento =  ""
      // 
      if($item->TDocCodi == "0"){
        $tipo_documento = "-";
        $cliente_documento = "-";
      }
      else {
        $tipo_documento = $item->TDocCodi;
        $cliente_documento = $item->PCRucc;
      }

      $this->items_part_xml .= $this->change_datas([
        ["item_id" , (int) $item->DetItem ],
        ["numero_serie_venta" , $item->detseri . "-" . $item->detNume ],
        ["total_item" , decimal($item->DetTota) ],
        ['tipodocumento' , $item->tidcodi ],
        ["billing" , $this->getBilling($item) ],
        ["item_valor_bruto" , decimal($item->DetTota) ],
        ["isc_total", decimal($item->DetISC)],
        ["icbper_total", decimal($item->getBolsaTotal())],
        ["condicion_code" , $condicion_code ],
        ["cliente_documento" , $cliente_documento ],
        ['BillingReference' , $this->getBillingReference($item , $this->documento->isAnulacion()) ],
        ["cliente_tipo_documento" , $tipo_documento ],        
        ["igv_total" , decimal($item->DetIGV) ],
      ], $this->item_base , false );        
    }


    return;
  }
}