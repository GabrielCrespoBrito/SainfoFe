<?php
namespace App\Http\Controllers\Util\Xml\dos_cero;

use App\XmlHelper;
use Chumper\Zipper\Zipper;

class XmlCreatorND extends XmlHelper
{
	public $header_part_xml = '<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>
<DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';	

	// extension = firma digital  - PROCESAR
	public $firma_part_xml = 
	'<ext:UBLExtensions>
 <ext:UBLExtension>
	 <ext:ExtensionContent>
		 <sac:AdditionalInformation>
				<sac:AdditionalMonetaryTotal>
						 <cbc:ID>1001</cbc:ID>
						 <cbc:PayableAmount currencyID="PEN">[total_venta]</cbc:PayableAmount>
				</sac:AdditionalMonetaryTotal>
				<sac:AdditionalProperty>
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
	<cbc:ID>[id]</cbc:ID>
	<cbc:IssueDate>[fecha_envio]</cbc:IssueDate>
	<cbc:IssueTime>[hora_envio]</cbc:IssueTime>
	<cbc:DocumentCurrencyCode>PEN</cbc:DocumentCurrencyCode>  	
	<cac:DiscrepancyResponse>
		 <cbc:ReferenceID>[id_ref]</cbc:ReferenceID>
		 <cbc:ResponseCode>[code_ref]</cbc:ResponseCode>
		 <cbc:Description>[descripcion_ref]</cbc:Description>
	</cac:DiscrepancyResponse>	
	<cac:BillingReference>
				<cac:InvoiceDocumentReference>
						 <cbc:ID>[id_ref]</cbc:ID>
						 <cbc:DocumentTypeCode>01</cbc:DocumentTypeCode>
				</cac:InvoiceDocumentReference>
	</cac:BillingReference>';

	public $iden_venta_part_xml = 
	' 
	<cac:Signature>
		<cbc:ID>IDSignCF</cbc:ID>
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
				<cbc:URI>#IDSignKG</cbc:URI>
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
        <cbc:ID>[empresa_id]</cbc:ID>
        <cbc:StreetName>[empresa_direccion]</cbc:StreetName>
        <cbc:CityName>[empresa_distrito]</cbc:CityName>
        <cbc:District>[empresa_municipalidad]</cbc:District>
        <cac:Country>
          <cbc:IdentificationCode>PE</cbc:IdentificationCode>
        </cac:Country>
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
					<cbc:RegistrationName><![CDATA[[cliente_nombre]]]></cbc:RegistrationName>
       	<cac:RegistrationAddress>
          <cbc:StreetName><![CDATA[[direccion_cliente]]]></cbc:StreetName>
          <cac:Country>
            <cbc:IdentificationCode>PE</cbc:IdentificationCode>
          </cac:Country>
         </cac:RegistrationAddress>					
				</cac:PartyLegalEntity>			
			</cac:Party>
		</cac:AccountingCustomerParty>
		 <cac:RequestedMonetaryTotal>
		         <cbc:PayableAmount currencyID="PEN">[venta_im]</cbc:PayableAmount>
		 </cac:RequestedMonetaryTotal>		
		'; 

	public $items_part_xml = '';

	public $item_base = '  
	<cac:DebitNoteLine>
		<cbc:ID>[item]</cbc:ID>
		<cbc:DebitedQuantity unitCode="NIU">[item_cantidad]</cbc:DebitedQuantity>
		<cbc:LineExtensionAmount currencyID="PEN">[valor_venta_bruto]</cbc:LineExtensionAmount>
		<cac:PricingReference>
			<cac:AlternativeConditionPrice>
				<cbc:PriceAmount currencyID="[moneda_abbre]">[item_price]</cbc:PriceAmount>
				<cbc:PriceTypeCode>01</cbc:PriceTypeCode>
			</cac:AlternativeConditionPrice>
		 </cac:PricingReference>
		 <cac:TaxTotal>
			 <cbc:TaxAmount currencyID="[moneda_abbre]">[item_igv]</cbc:TaxAmount>
			 <cac:TaxSubtotal>
				 <cbc:TaxAmount currencyID="[moneda_abbre]">[item_igv]</cbc:TaxAmount>
				 <cac:TaxCategory>
					<cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
					<cac:TaxScheme>
						<cbc:ID>1000</cbc:ID>
						<cbc:Name>IGV</cbc:Name>
						<cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
					</cac:TaxScheme>
				 </cac:TaxCategory>
			</cac:TaxSubtotal>
		 </cac:TaxTotal>
		 <cac:Item>
				<cbc:Description>[item_descripcion]</cbc:Description>
				<cac:SellersItemIdentification>
					<cbc:ID>[id_item]</cbc:ID>
				</cac:SellersItemIdentification>
		 </cac:Item>
		 <cac:Price>
				<cbc:PriceAmount currencyID="[moneda_abbre]">[item_precio_igv]</cbc:PriceAmount>
		 </cac:Price>
	</cac:DebitNoteLine>
	';

	public $footer_part_xml = '
	</DebitNote>';

	public $datas = [];

	public function setDatasPartsXml()
	{
    

    set_timezone();

		$id_doc_ref = $this->documento->VtaSeriR . '-' . $this->documento->VtaNumeR;
 		$letra = HHCL::convertir($this->documento->VtaImpo);
		$total_venta = abs($this->documento->Vtabase);

		$this->change_datas([
			["total_venta", decimal($total_venta)],	
			["moneda_letra_venta" , $letra ], 
		],$this->firma_part_xml);


		$this->change_datas([
			["id" , $this->documento->VtaNume ],			 			
			["fecha_envio" , $this->documento->VtaFvta ],
			["hora_envio" , $this->documento->VtaHora ],
			["id_ref" , $id_doc_ref ],
			["code_ref" , $this->documento->vtaadoc ],
			["descripcion_ref" , $this->documento->VtaObse ],			
			["tipo_documento" , $this->documento->TidCodi ],			
		],$this->version_part_xml );


    $ref_codigo  = $this->documento->ref_serie . '-' . $this->documento->ref_numero;

		$this->change_datas([
			["empresa_ruc" , $this->empresa->EmpLin1 ],
			["empresa_nombre" , $this->empresa->EmpNomb ],
		],$this->iden_venta_part_xml );

		$this->change_datas([ 
			[ "empresa_ruc" , $this->empresa->EmpLin1 ], 
			[ "empresa_id" , $this->empresa->FE_UBIGEO ], 			
			[ "nombre_empresa" , $this->empresa->EmpNomb ], 			
			[ "empresa_provincia" , $this->empresa->FE_PROV ], 			
			[ "empresa_municipalidad" , $this->empresa->FE_DIST ], 	
			[ "empresa_distrito" , $this->empresa->FE_PROV ], 	
			[ "empresa_direccion" , $this->empresa->EmpLin2 ], 			
			["empresa_razon_social" , $this->empresa->EmpLin5  ]		
		], $this->empresa_part_xml );

		$cliente = $this->documento->cliente;

		$this->change_datas([ 
			["cliente_nombre" , $cliente->PCNomb ], 
			["direccion_cliente" , $cliente->PCDire ], 
			["cliente_ruc" , $cliente->PCRucc ],
			[ "empresa_id" , 6 ], 			
			[ "venta_im" , $this->documento->VtaImpo ], 						
		], $this->cliente_part_xml );


		$i = 1;

		function rV($base , $valor )
			{
				return $base === "GRATUITA" ? "0.00" : $valor;
			}

		foreach( $this->documento->items as $item ){

			$b = $item->DetBase;

			if( $b != self::EXONERADA_SYSTEM ){

				$allowance_charge = "";
				$this->items_part_xml .= $this->change_datas([ 
					["item" , (int) $item->DetItem                       ],
					["item_cantidad" , $item->DetCant                    ],
					["valor_venta_bruto" , rV($b,$item->valorVentaItem())],	
					["item_valor" , $item->valorBrutoVenta()             ],
					["item_price" , $item->DetPrec                       ],
					["allowance_charge" , $allowance_charge              ],
					["item_igv" ,  decimal(rV($b,$item->valorVentaItemIGV()))],
					["item_impuesto" ,  decimal(rV($b,$item->valorVentaItem()))],					
					["item_igv_porc" , $item->DetIGVP                    ],
					["item_factor" , $item->Det    ],	
					["id_item", $item->DetCodi],
					['tax_type_code' , self::TaxTypeCode[$b] ],
					['tax_type_name' , self::TaxTypeName[$b]  ],
					['tax_type_id' , self::TaxTypeID[$b]  ],
					['codigo_producto' , $item->DetCodi  ],					
					["item_total_descuento" ,  decimal($item->descuentoBruto()) ],
					["item_precio_igv" ,  decimal($item->valorVentaPorUnidad()) ],
					["igv_porc" , \DB::table('opciones_emp')->first()->Logigv ],

					["item_descripcion" , $item->DetNomb ],							
				], $this->item_base , false );				
			}

			// break;						
		} // end foreach

		//

		return;
	}


}
