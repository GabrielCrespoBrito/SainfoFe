<?php

namespace App\Http\Controllers\Util\Xml\dos_cero;
use  App\XmlHelper;
use App\HHCL;

use Chumper\Zipper\Zipper;

class XmlCreator extends XmlHelper
{
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
    self::EXONERADA_SYSTEM => "EXONERADA",
  ];

  const TaxTypeID = [
    self::GRAVADA_SYSTEM  => 1000,
    self::GRATUITA_SYSTEM => 9996,
    self::EXONERADA_SYSTEM => 9997,
    self::INAFECTA_SYSTEM => 9998,
  ];

  const GRATUITA = "GRATUITO";
  const EXONERADA = "EXONERADA";
  const INAFECTA = "INAFECTO";  
  const GRAVADA = "IGV";    

  // header del xml 
  public $header_part_xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
';  

  // extension = firma digital  - PROCESAR
  public $firma_part_xml = 
  '<ext:UBLExtensions>
    <ext:UBLExtension>
      <ext:ExtensionContent>
        <sac:AdditionalInformation>
          <sac:AdditionalMonetaryTotal>
            <cbc:ID>1001</cbc:ID>
            <cbc:PayableAmount currencyID="[moneda_abbre]">[total_venta]</cbc:PayableAmount>
          </sac:AdditionalMonetaryTotal>
          <sac:AdditionalMonetaryTotal>
            <cbc:ID>1002</cbc:ID>
            <cbc:PayableAmount currencyID="[moneda_abbre]">0.00</cbc:PayableAmount>
          </sac:AdditionalMonetaryTotal>
          <sac:AdditionalMonetaryTotal>
            <cbc:ID>1003</cbc:ID>
            <cbc:PayableAmount currencyID="[moneda_abbre]">0.00</cbc:PayableAmount>
          </sac:AdditionalMonetaryTotal>
          <sac:AdditionalMonetaryTotal>          
            <cbc:ID>1004</cbc:ID>
            <cbc:PayableAmount currencyID="[moneda_abbre]">0.00</cbc:PayableAmount>
          </sac:AdditionalMonetaryTotal>' .
        '<sac:AdditionalProperty>
          <cbc:ID>1000</cbc:ID>
          <cbc:Value>[moneda_letra_venta]</cbc:Value>
        </sac:AdditionalProperty>
        </sac:AdditionalInformation>
      </ext:ExtensionContent>
    </ext:UBLExtension>
  <ext:UBLExtension>
    <ext:ExtensionContent></ext:ExtensionContent>
  </ext:UBLExtension>
</ext:UBLExtensions>
';

  // version = dato fijo
  public $version_part_xml = '
  <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
  <cbc:CustomizationID>1.0</cbc:CustomizationID>
  <cbc:ID>[numero_serie_venta]</cbc:ID>
  <cbc:IssueDate>[fecha_venta]</cbc:IssueDate>
  <cbc:InvoiceTypeCode>01</cbc:InvoiceTypeCode>
  <cbc:DocumentCurrencyCode>[moneda_abbre]</cbc:DocumentCurrencyCode>
  ';

  public $iden_venta_part_xml = 
  ' 
  <cac:Signature>
    <cbc:ID>[numero_serie_venta]</cbc:ID>
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
        <cbc:URI>#[numero_serie_venta]</cbc:URI>
      </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
  </cac:Signature>
  ';

  // AdditionalAccountID ?
  public $empresa_part_xml = 
  '
  <cac:AccountingSupplierParty>
    <cbc:CustomerAssignedAccountID>[empresa_ruc]</cbc:CustomerAssignedAccountID>
    <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
    <cac:Party>
      <cac:PartyName>
        <cbc:Name><![CDATA[[empresa_razon_social]]]></cbc:Name>
      </cac:PartyName>
      <cac:PostalAddress>
        <cbc:ID>150131</cbc:ID>
        <cbc:StreetName><![CDATA[[empresa_direccion]]]></cbc:StreetName>
      </cac:PostalAddress>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName><![CDATA[[empresa_razon_social]]]></cbc:RegistrationName>
      </cac:PartyLegalEntity>
    </cac:Party>
  </cac:AccountingSupplierParty>
  ';

  public $cliente_part_xml = '
  <cac:AccountingCustomerParty>
      <cbc:CustomerAssignedAccountID>[cliente_ruc]</cbc:CustomerAssignedAccountID>
      <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
      <cac:Party>
        <cac:PartyLegalEntity>
          <cbc:RegistrationName><![CDATA[[cliente_nombre] ]]></cbc:RegistrationName>
        </cac:PartyLegalEntity>
      </cac:Party>
    </cac:AccountingCustomerParty>
    ';   

  public $tax_totales_part_xml = 
  '<cac:TaxTotal>
    <cbc:TaxAmount currencyID="[moneda_abbre]">[total_igv]</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxAmount currencyID="[moneda_abbre]">[total_igv]</cbc:TaxAmount>
      <cac:TaxCategory>
        <cac:TaxScheme>
          <cbc:ID>1000</cbc:ID>
          <cbc:Name>IGV</cbc:Name>
          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
        </cac:TaxScheme>
      </cac:TaxCategory>
    </cac:TaxSubtotal>
    </cac:TaxTotal>
  ';

  public $legal_total_part_xml = 
  '<cac:LegalMonetaryTotal>
    <cbc:LineExtensionAmount currencyID="[moneda_abbre]">[total_sinigv]</cbc:LineExtensionAmount>
    <cbc:TaxExclusiveAmount currencyID="[moneda_abbre]">[igv]</cbc:TaxExclusiveAmount>
    <cbc:PayableAmount currencyID="[moneda_abbre]">[total_conigv]</cbc:PayableAmount>  
  </cac:LegalMonetaryTotal>
  ';

  public $items_part_xml = '';


  public $allowances_charge_base = '
  <cac:AllowanceCharge>
    <cbc:ChargeIndicator>[allowancecharge_indicator]</cbc:ChargeIndicator>
    <cbc:MultiplierFactorNumeric>[allowancecharge_factor]</cbc:MultiplierFactorNumeric>
    <cbc:Amount currencyID="[moneda_abbre]">[allowancecharge_ammount]</cbc:Amount>
    <cbc:BaseAmount currencyID="[moneda_abbre]">[allowancecharge_base]</cbc:BaseAmount>
  </cac:AllowanceCharge>';

  public $item_base_xxml = '
  ';

  public $item_base = '
  <cac:InvoiceLine>
    <cbc:ID>[item_id]</cbc:ID>
    <cbc:InvoicedQuantity unitCode="NIU">[item_cantidad]</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID="[moneda_abbre]">[valor_venta_bruto]</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="[moneda_abbre]">[item_precio_unidad]</cbc:PriceAmount>
        <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    [allowance_charge]
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="[moneda_abbre]">[item_igv]</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="[moneda_abbre]">[valor_venta_bruto]</cbc:TaxableAmount>       
       <cbc:TaxAmount currencyID="[moneda_abbre]">[item_igv]</cbc:TaxAmount>        
        <cac:TaxCategory>
        <cbc:Percent>[igv_porc]</cbc:Percent>
        <cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
          <cac:TaxScheme>
            <cbc:ID>[tax_type_id]</cbc:ID>
            <cbc:Name>[tax_type_name]</cbc:Name>
            <cbc:TaxTypeCode>[tax_type_code]</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:Item>
      <cbc:Description><![CDATA[[item_descripcion]]]></cbc:Description>
      <cac:SellersItemIdentification>
        <cbc:ID>[codigo_producto]</cbc:ID>
      </cac:SellersItemIdentification>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="[moneda_abbre]">[item_precio_igv]</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>';


  public $footer_part_xml = 
  '</Invoice>';

  public $datas = [];
  
  public function setDatasPartsXml()
  {
    set_timezone();
    $v = $this->documento;
    // ------ iden_venta_part_xml -----
    // formato 1:53 p.m     
    $hora = explode(' ' , $v->VtaHora );    
    // $hora_number = $hora[0];     
    // $hora_am_pm = strpos( $hora[1] , "a" ) === false ? 'PM' : 'AM';
    // // 1:53 PM
    // $hora = $hora_number . ' ' . $hora_am_pm;
    // // 13:53:00 
     $hora = date('H:i:s' , strtotime($v->VtaHora) );

    /* self::TipoVenta Poner todos los tipos de venta */
    $items = $v->items; 
    $gratuitas = $items->where('DetBase' , 'GRATUITA'); 
    $exoneradas = $items->where('DetBase' , 'EXONERADA'); 

    $gratuita_total = "0.00";
    $exonerada_total = "0.00";

    if( $gratuitas->count() ){  
      $gratuita_total = $gratuitas->sum('DetImpo');
    }

    if( $exoneradas->count() ){ 
      $exonerada_total = $exoneradas->sum('DetImpo');
    }   

    $this->change_datas([
      [ "total_venta" , decimal($v->Vtabase) ], 
      [ "total_venta_igv" , decimal($v->VtaImpo) ],       
      [ "total_exonerada" , $exonerada_total  ],            
      [ "total_grauita" , $gratuita_total  ],       
      [ "total" , $v->VtaImpo ],            
      [ "moneda_letra_venta" , HHCL::convertir($v->VtaImpo)], 
    ],$this->firma_part_xml );

    $this->change_datas([
      ["numero_serie_venta" , $v->VtaNume],       
      ["fecha_venta" , $v->VtaFvta], 
      ["hora_venta" , $hora],       
      ["tipo_venta" , $v->TidCodi], 
    ],$this->version_part_xml );

   
    $this->change_datas([
      ["empresa_ruc" , $this->empresa->EmpLin1 ],
      ["empresa_nombre" , $this->empresa->EmpNomb ],      
      ["nombre_empresa_comercial" , $this->empresa->EmpLin5 ],
    ],$this->iden_venta_part_xml );
    // ---------------------------------------

    // ------ empresa_part_xml ------------------
    // empresa id ?
    $this->change_datas([ 
      [ "empresa_ruc" , $this->empresa->EmpLin1 ], 
      [ "empresa_id" , 6 ], 
      [ "nombre_empresa" , $this->empresa->EmpNomb ],       
      [ "empresa_direccion" , $this->empresa->EmpLin2 ],      
      ["empresa_razon_social" , $this->empresa->EmpLin5  ]    
    ], $this->empresa_part_xml );
    // --------------------------------------

    // ------ cliente_part_xml -------------------

    $this->change_datas([ 
      ["cliente_nombre" , $v->cliente->PCNomb ], 
      ["cliente_ruc" , $v->cliente->PCRucc ],
      [ "empresa_id" , 6 ],       
      ["cliente_direccion" , $v->cliente->PCRucc ],
    ],$this->cliente_part_xml );
    
    // return;
    // ------------------------------------------------

    // ------ Items  --------------------
    $igvs_total = 0;

    // retornar el valor dependiendo del tipo de item
    if(!function_exists("rV")){
      function rV($base , $valor ){
        return $base === "GRATUITA" ? "0.00" : $valor;
      }
    }

    $dataTipo = [
      'hasData' => false,
      'totalBruto'  => 0,
      'totalDescuento'  => 0,     
    ];

    $tipoVentaTotales = [
      self::EXONERADA_SYSTEM => $dataTipo,
      self::GRATUITA_SYSTEM => $dataTipo,
      self::INAFECTA_SYSTEM => $dataTipo,
      self::GRAVADA_SYSTEM => $dataTipo,
    ];

    $valorBruto = 0;
    // Agregar los items
    foreach( $v->items as $item ){

      // base
      $b = $item->DetBase;

      // Registrando los totales de cada tipo de venta
      $tipoVentaTotales[$b]['hasData'] = true;
      $tipoVentaTotales[$b]['totalBruto'] += $item->valorBrutoVenta();
      $tipoVentaTotales[$b]['totalDescuento'] += $item->valorVentaItem();
                        
      $valorBruto += $item->valorBrutoVenta();      

      if( $b != self::EXONERADA_SYSTEM ){

        $allowance_charge = "";

        if( $b !== self::GRATUITA_SYSTEM ){

          $allowancecharge_indicator = "false";
          $allowancecharge_reason = "00"; 
          $allowancecharge_factor = $item->DetDcto ? $item->DetDcto : "0.00";
          $allowancecharge_ammount = $item->descuentoBruto();
          $allowancecharge_base = $item->valorBrutoVenta(); //  

          $allowance_charge = $this->change_datas([
            ['allowancecharge_indicator', $allowancecharge_indicator ],         
            ['allowancecharge_reason', $allowancecharge_reason],
            ['allowancecharge_factor', $allowancecharge_factor ],     
            ['allowancecharge_ammount' , $allowancecharge_ammount ],    
            ["allowancecharge_base" , $allowancecharge_base ],
          ] , $this->allowances_charge_base , false   );
          
        }

        $igvs_total += $item->valorVentaItemIGV(); 

        $this->items_part_xml .= $this->change_datas([ 
          ["item_id" , (int) $item->DetItem ],
          ["item_cantidad" , $item->DetCant ],
          ["valor_venta_bruto" , rV($b,$item->valorVentaItem())], 
          ["item_valor_bruto" , $item->valorBrutoVenta() ],
          ["item_precio_unidad" , $item->DetPrec  ],
          ["allowance_charge" , $allowance_charge ],        
          ["item_igv" , decimal(rV($b,$item->valorVentaItemIGV()))],
          ["item_impuesto" , decimal(rV($b,$item->valorVentaItem()))],          
          ["item_igv_porc" , $item->DetIGVP ],
          ["item_factor" , $item->Det ],  
          ["item_codigo", $item->DetCodi ],
          ['tax_type_code' , self::TaxTypeCode[$b] ],
          ['tax_type_name' , self::TaxTypeName[$b]  ],
          ['tax_type_id' , self::TaxTypeID[$b]  ],
          ['codigo_producto' , $item->DetCodi  ],         
          ["item_total_descuento" , decimal($item->descuentoBruto()) ],
          ["item_precio_igv" , decimal($item->valorVentaPorUnidad()) ],
          ["igv_porc" , \DB::table('opciones_emp')->first()->Logigv ],

          ["item_descripcion" , $item->DetNomb ],             
        ], $this->item_base , false );        
      }

      // break;           
    } // end foreach

    // ---------- Item  -------------------

    // ------ tax_totales_part_xml  -------------------
    $this->change_datas([ 
      [ "total_igv" , $v->igv_compra() ],
    ],$this->tax_totales_part_xml );

    // ------ tax_totales_part_xml (Pendiente) ----------


    // ------ legal_total_part_xml (Pendiente) -------
    $this->change_datas([ 
      ["total_sinigv" , $v->Vtabase ],      
      ["total_igv" , $v->VtaIGVV ],     
      ["igv" , $v->igv_compra() ],            
      ["total_conigv" , $v->VtaImpo ],            
    ], $this->legal_total_part_xml );

    return;
    // ----------------------------------------------
  }






}