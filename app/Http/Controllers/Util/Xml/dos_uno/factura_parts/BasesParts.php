<?php

namespace App\Http\Controllers\Util\Xml\dos_uno\factura_parts;

trait BasesParts
{
  /**
   * Devoler xml del impuesto
   * 
   * @return String
   */
  public function getDescuentoBase($indicador, $razoncode, $porcentaje, $totalDescuento, $cifraDescontar) : String
  {
    return $this->change_datas([
      ["indicador", $indicador],
      ["razon_code", $razoncode],
      ["porcentaje", $porcentaje],
      ["total_descuento", decimal($totalDescuento)],
      ["cifra_a_descontar", decimal($cifraDescontar)],
    ], $this->descuento_base, false);
  }

  /**
   * Etiqueta de descuento opcionales usada en varias partes
   * 
   * @return String
   */
  function getPercent($show = true, $value = false): String
  {
    return $show ? "<cbc:Percent>{$value}</cbc:Percent>" : "";
  }


  /**
   * Etiquetas opcionales usadas en varias partes
   * 
   * @return String
   */
  function getPartVariable($show = false, $type = null, $value = null) : String
  {
    if( ! $show ) {
      return '';
    }

    $value = (array) $value;

    # Partes
    $parts = [
      'percent' => "
      <cbc:Percent>%s</cbc:Percent>",

      'value_bolsa' => '
      <cbc:PerUnitAmount currencyID="[moneda]">%s</cbc:PerUnitAmount>',

      'cantidad_bolsa' => '
      <cbc:BaseUnitMeasure unitCode="NIU">%s</cbc:BaseUnitMeasure>',

      'taxableammount' => '
      <cbc:TaxableAmount currencyID="[moneda]">%s</cbc:TaxableAmount>',

      'tierrange' => "
      <cbc:TierRange>%s</cbc:TierRange>",

      'taxexception' => "
      <cbc:TaxExemptionReasonCode>%s</cbc:TaxExemptionReasonCode>",

      'codproductosunat' =>'
      <cac:CommodityClassification>
        <cbc:ItemClassificationCode listAgencyName="GS1 US" listID="UNSPSC" listName="Item Classification">%s</cbc:ItemClassificationCode>
      </cac:CommodityClassification>',

      'nota' => '
      <cbc:Note languageLocaleID="%s">%s</cbc:Note>',

      'info_item' => '
      <cac:AdditionalItemProperty>
        <cbc:Name>%s</cbc:Name>
      </cac:AdditionalItemProperty>',

      'nota_nocode' => '
      <cbc:Note>%s</cbc:Note>',

      'ordencompra' => 
      "<cac:OrderReference>
        <cbc:ID>%s</cbc:ID>
      </cac:OrderReference>",

      'taxcategory' => "
      <cbc:ID>%s</cbc:ID>",

      'guiareferencia' =>
      '<cac:DespatchDocumentReference>
        <cbc:ID>%s</cbc:ID>
        <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">09</cbc:DocumentTypeCode>
      </cac:DespatchDocumentReference>',

      'formapago_contado' =>
      '<cac:PaymentTerms>
        <cbc:ID>%s</cbc:ID>
        <cbc:PaymentMeansID>%s</cbc:PaymentMeansID>
      </cac:PaymentTerms>
      ',

      'formapago_percepcion' =>
      '<cac:PaymentTerms>
        <cbc:ID>%s</cbc:ID>
        <cbc:Amount currencyID="%s">%s</cbc:Amount>
      </cac:PaymentTerms>
      ',

      'formapago_creditos_titulo' =>
      '<cac:PaymentTerms>
        <cbc:ID>%s</cbc:ID>
        <cbc:PaymentMeansID>%s</cbc:PaymentMeansID>
        <cbc:Amount currencyID="%s">%s</cbc:Amount>
      </cac:PaymentTerms>
      ',

      'formapago_creditos_partes' =>
      '<cac:PaymentTerms>
        <cbc:ID>%s</cbc:ID>
        <cbc:PaymentMeansID>%s</cbc:PaymentMeansID>
        <cbc:Amount currencyID="%s">%s</cbc:Amount>
        <cbc:PaymentDueDate>%s</cbc:PaymentDueDate>        
      </cac:PaymentTerms>
      '         
    ];

    logger( 'Base Parts type:'.  $type, $value);

    return vsprintf($parts[$type],$value);
  }


/**
 * Devoler xml de un impuesto
 * 
 * @return String
 */
  public function getTaxBase( $taxCategoryId, $taxExceptionCode, $taxId, $tipoISC, $taxName, $taxSchemeCode, $valorTotal, $taxValor, $porcentaje ) : String
  {
    return $this->change_datas([
      [ 'TaxCategory_ID', $taxCategoryId ],
      [ 'TaxExemptionReasonCode', $taxExceptionCode ],
      [ 'TaxScheme_ID', $taxId ],
      [ 'tipoISC', $tipoISC],
      [ 'TaxScheme_Name', $taxName ],
      [ 'TaxScheme_TaxTypeCode', $taxSchemeCode ],
      [ "taxableammount" , $valorTotal ],
      [ "igv_total", decimal($taxValor) ],
      [ "porcentaje",   $porcentaje ],
    ], $this->taxSubtotal_base, false );
  }



}
