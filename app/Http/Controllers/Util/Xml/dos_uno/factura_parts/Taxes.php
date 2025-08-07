<?php

namespace App\Http\Controllers\Util\Xml\dos_uno\factura_parts;

use App\TipoIgv;
use App\Venta;

trait Taxes
{
  /**
   * Poner los totales de cada impuesto
   * 
   * @return void
   */
  public function processTaxtTotals()
  {
    $total_documento = $this->documento->totales_documento;
    $hasAnticipo = $this->documento->hasAnticipo();
    $impuesto_total = 0;

    foreach ( $this->subtotales_arr as $taxName => $subtotal )
    {
      switch ($taxName) {
        case Venta::GRAVADA:          
          $exists = convertBooleanNumber($total_documento->total_gravadas);
          if (!$exists) break;
          if( $hasAnticipo ){
            $impuesto = $total_documento->igv;
            $base = $total_documento->total_gravadas;
          }
          else {
            $base = $total_documento->total_gravadas;
            $impuesto = $total_documento->igv;
          }
          break;
        case Venta::EXONERADA:
          $exists = convertBooleanNumber($total_documento->total_exonerada);
          if (!$exists) break;
          if( $hasAnticipo ){
            $base = $total_documento->total_exonerada - $total_documento->anticipo;
          }
          else {
            $base = $total_documento->total_exonerada;
          }
          $impuesto = 0;
          break;
        case Venta::GRATUITA:
          $exists = convertBooleanNumber($total_documento->total_gratuita);
          if (!$exists) break;
          $base = $total_documento->total_gratuita;         
          $baseParaImpuesto = 0;
          $items = $this->documento->items->whereNotIn('TipoIGV', [TipoIgv::DEFAULT_GRAVADA, TipoIgv::DEFAULT_EXONERADA, TipoIgv::DEFAULT_INAFECTA]); 

          foreach( $items as $item ){
            $baseParaImpuesto += $item->isGratuitaGravada() ? $item->DetImpo : 0;
          }
          $impuesto = ($baseParaImpuesto / 100) * $this->igvs->igvPorc;
          break;
        case Venta::INAFECTA:
          $exists = convertBooleanNumber($total_documento->total_inafecta);
          if (!$exists) break;
          if( $hasAnticipo ){
            $base = $total_documento->total_inafecta - $total_documento->anticipo;
          }
          else {
            $base = $total_documento->total_inafecta;
          }
          $impuesto = 0;
          break;
        case 'ISC':
          $exists = convertBooleanNumber($total_documento->isc);
          if( ! $exists ) break;

          // $base = $total_valor_bruto_venta;
          $base_isc = $total_documento->total_base_isc;
          if( $hasAnticipo ){
            $base = $base_isc - $total_documento->anticipo;
            $porc = ($total_documento->anticipo / $base_isc) * 100 ;
            $impuesto = ($total_documento->isc / 100) * $porc;
          }
          else {
            $base = $base_isc;
            $impuesto = $total_documento->isc;
          }
          break;
        case 'ICBPER':
          $exists = convertBooleanNumber($total_documento->icbper);
          if (!$exists) break;
            $base = 0;
            $impuesto = $total_documento->icbper;
          break;
        default:
        throw new \Exception("El impuesto {$taxName} no exsite", 1);        
        break;
      }

      if ( $exists ) {

        $infoTax = self::TAX_INFO[$taxName] ;
        $taxcategoryID = '';
        $taxAbleAmmount = '';

        if( $taxName != "ICBPER" ){
          $taxcategoryID = $this->getPartVariable(true, 'taxcategory', $infoTax['TaxCategory_ID']);
          $taxAbleAmmount = $this->getPartVariable(true, 'taxableammount', decimal($base));
        }

        $impuesto_total +=  $taxName == Venta::GRATUITA ? 0 : $impuesto;

        $this->taxSubtotales .= $this->change_datas([
          ['TaxCategory_ID', $taxcategoryID ],
          ['TaxExemptionReasonCode', ''],
          ['TaxScheme_ID', $infoTax['TaxScheme_ID']],
          ['TaxScheme_Name', $infoTax['TaxScheme_Name']],
          ['TaxScheme_TaxTypeCode', $infoTax['TaxScheme_TaxTypeCode']],
          ["taxableammount", $taxAbleAmmount ],
          ["igv_total", decimal($impuesto)],
          ['tipoISC', ''],
          ["porcentaje", '' ],
        ], $this->taxSubtotal_base, false);
      }
    }

    return $impuesto_total;    
  }

  /**
   * Devolver para el item el xml de impuesto por defecto 
   * 
   * @param $item App\VentaItem
   * @return String (xml)
   */
  public function getTaxDefault($item, $cifras, $isAnticipoItem = false)
  {
    // $infoTax = self::TAX_INFO[$item->getRealBase()];
    $infoTax = self::TAX_INFO[$item->DetBase];

    // $isGratuito = $item->isGratuito();
    $isGratuito = $item->isGratuitaGravada();

    $igvPorc =  $isGratuito ? $this->igvs->igvPorc : $item->porcentajeIGV();
    $base = $isGratuito ? decimal($cifras->valor_noonorosa * $cifras->cantidad) :  $this->convertValuesIfNCWithDiscounts($cifras->valor_venta_por_item_igv);
    $valor =  $isGratuito ? (($base / 100) * $igvPorc) : $this->convertValuesIfNCWithDiscounts($cifras->igv_total);

    // dd( $item->TipoIGV );
    
    return $this->getTaxBase(
      $this->getPartVariable(true, 'taxcategory', $infoTax['TaxCategory_ID']),
      $this->getPartVariable(true, 'taxexception', $item->TipoIGV),
      $infoTax['TaxScheme_ID'],
      $this->getPartVariable(),
      $infoTax['TaxScheme_Name'],
      $infoTax['TaxScheme_TaxTypeCode'],
      $this->getPartVariable(true, 'taxableammount', decimal($base) ),
      convertNegativeIfTrue($valor, $isAnticipoItem),
      $this->getPercent(true, $igvPorc )
    );
  }

  /**
   * Devolver para el item el xml de isc
   * 
   */
  public function getTaxISC($item, $cifras)
  {
    $infoTax = self::TAX_INFO['ISC'];

    return $this->getTaxBase(
      '',
      '',
      $infoTax['TaxScheme_ID'],
      $this->getPartVariable(true, 'tierrange', $item->xmlInfo()->getTipoISC()),
      $infoTax['TaxScheme_Name'],
      $infoTax['TaxScheme_TaxTypeCode'],
      $this->getPartVariable(true, 'taxableammount', $cifras->valor_venta_por_item ),
      $cifras->isc,
      // $this->getPartVariable(true, 'percent', math()->porcFactor($cifras->isc_porc) )
      $this->getPartVariable(true, 'percent', $cifras->isc_porc )
    );
  }

  /**
   * Devolver para el item el xml de isc
   * 
   */
  public function getTaxICBPER($item, $cifras)
  {
    $infoTax = self::TAX_INFO['ICBPER'];

    $cantidadBolsasValorUnidad = 
    $this->getPartVariable(true, 'cantidad_bolsa',  $cifras->cantidad ) . $this->getPartVariable(true, 'value_bolsa', $cifras->bolsa_unit );

    $xml_str = $this->getTaxBase(
      '',
      '',
      $infoTax['TaxScheme_ID'],
      '',
      $infoTax['TaxScheme_Name'],
      $infoTax['TaxScheme_TaxTypeCode'],
      // $cantidadBolsasValorUnidad,
      '',
      $cifras->bolsa,
      ''
    );


    $str_replace = '</cbc:TaxAmount>' . $cantidadBolsasValorUnidad;
    return str_replace( '</cbc:TaxAmount>', $str_replace , $xml_str  );
  }



  /**
   * Devoler los subtotales de los impuestos del item
   * 
   * @return String
   */
  public function getSubtotal($item, $cifras = null, $isAnticipoItem = false)
  {
    # Todos los subtaxes
    $subTaxesXml = "";

    # Impuesto por defecto 
    $subTaxesXml .= $this->getTaxDefault($item, $cifras , $isAnticipoItem);

    # Impuesto ISC 
    if( $item->hasISC() ){
      $subTaxesXml .= $this->getTaxISC($item, $cifras);
    }

    # Si tiene el impuesto a la bolsa
    if ( $item->hasICBPER() ) {
      $subTaxesXml .= $this->getTaxICBPER($item, $cifras);
    }

    $totalImpuestos = $this->convertValuesIfNCWithDiscounts($cifras->impuestos_totales);
    
    if( $item->isGratuitaGravada() ) {
    // if ($item->isGratuito()) {
      $totalImpuestos = (decimal($cifras->valor_noonorosa * $cifras->cantidad) / 100) * $this->igvs->igvPorc;
    }


    # Devolver subtotales
    return $this->change_datas([
      ["igv_total", decimal(convertNegativeIfTrue( $totalImpuestos,$isAnticipoItem) )],
      ["subtotal", $subTaxesXml ],
    ], $this->item_taxtotal_base, false);
  }

  /**
   * Poner los totales de cada impuestos
   */
  public function getTaxTotalPart()
  {
    $impuestos_totales = $this->processTaxtTotals();
    
    $this->change_datas([
      ["total_tax", decimal($impuestos_totales) ],
      ["subtotales", $this->taxSubtotales]
    ], $this->totales_XMLPART);
  }
}