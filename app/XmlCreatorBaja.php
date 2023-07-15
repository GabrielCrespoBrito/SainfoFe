<?php

namespace App;
use Chumper\Zipper\Zipper;
use App\XmlHelper;

class XmlCreatorBaja extends XmlHelper
{
	// header del xml 
	public $header_part_xml = 
  '<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>
<VoidedDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';	

	// extension = firma digital  - PROCESAR
	public $firma_part_xml = 
	'
  <ext:UBLExtensions>
  <ext:UBLExtension>
        <ext:ExtensionContent></ext:ExtensionContent>
  </ext:UBLExtension>
</ext:UBLExtensions>';

	// version = dato fijo
	public $version_part_xml = 
  '
  <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
  <cbc:CustomizationID>1.0</cbc:CustomizationID>
  <cbc:ID>[id]</cbc:ID>
	<cbc:ReferenceDate>[fecha_creacion]</cbc:ReferenceDate>
	<cbc:IssueDate>[fecha_envio]</cbc:IssueDate>';

	public $iden_venta_part_xml = 
	' 
  <cac:Signature>
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
  </cac:Signature>';

  // AdditionalAccountID ?
	public $empresa_part_xml = 
	'
 <cac:AccountingSupplierParty>
    <cbc:CustomerAssignedAccountID>[empresa_ruc]</cbc:CustomerAssignedAccountID>
    <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
    <cac:Party>
        <cac:PartyLegalEntity>
             <cbc:RegistrationName><![CDATA[[empresa_razon_social]]]></cbc:RegistrationName>
        </cac:PartyLegalEntity>
    </cac:Party>
 </cac:AccountingSupplierParty>';

	public $items_part_xml = '';

	public $item_base = '  
  <sac:VoidedDocumentsLine>
       <cbc:LineID>[item_id]</cbc:LineID>
       <cbc:DocumentTypeCode>[tipo_documento]</cbc:DocumentTypeCode>
       <sac:DocumentSerialID>[serie]</sac:DocumentSerialID>
       <sac:DocumentNumberID>[correlativo]</sac:DocumentNumberID>
       <sac:VoidReasonDescription>ANULACION DE LA FACTURA [item_id]</sac:VoidReasonDescription>
  </sac:VoidedDocumentsLine>';

	public $footer_part_xml = 
	'
  </VoidedDocuments>';

	public function setDatasPartsXml()
	{
		$this->change_datas([
			["id" , $this->documento->DocNume ],
			["fecha_creacion" , $this->documento->DocFechaD ],
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
			["empresa_razon_social" , $this->empresa->EmpLin5  ]		
		], $this->empresa_part_xml );

		$i = 1;
		foreach( $this->documento->items as $item )
    {
			$this->items_part_xml .= $this->change_datas([ 
				["item_id" , $i++ ],
				["serie" , $item->detseri ],
        ["tipo_documento" , $item->tidcodi ],
        ["correlativo" , $item->detNume ],
			], $this->item_base , false );
		}

		return;
	}

}