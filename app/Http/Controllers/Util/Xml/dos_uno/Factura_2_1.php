<?php

namespace App\Http\Controllers\Util\Xml\dos_uno;

use App\Moneda;
use App\FormaPago;
use App\TipoDocumentoPago;
use App\Util\XmlInformation\XmlInformation;
use App\Http\Controllers\Util\Xml\XmlHelperNew;
use App\Http\Controllers\Util\Xml\dos_uno\factura_parts\Taxes;
use App\Http\Controllers\Util\Xml\dos_uno\factura_parts\Discounts;
use App\Http\Controllers\Util\Xml\dos_uno\factura_parts\BasesParts;
use App\Http\Controllers\Util\Xml\dos_uno\factura_parts\CalculateData;

class Factura_2_1 extends XmlHelperNew
{
  use
    PartsXML,
    Discounts,
    Taxes,
    CalculateData,
    BasesParts;

  # Totales del documento
  public $totals = [
    # (1) Total valor de venta
    'LineExtensionAmount' => 0,
    # (2) Total de impuestos 
    'TaxTotal' => 0,
    # (3) Total precio de venta (incluye impuestos)  (1) + (2)
    'TaxInclusiveAmount' => 0,
    # (4) Monto total de descuentos del comprobante
    'AllowanceTotalAmount' => 0,
    # (5) Monto total de cargos
    'ChargeTotalAmount' => 0,
    # (6) Monto total de anticipos del comprobante
    'PrepaidAmount' => 0,
    # Importe total de la venta, cesión en uso o del servicio prestado 
    'PayableAmount' => 0
  ];

  /**
   * Sumar la cantidad al respectivo total
   *  
   * @return void
   */
  public function addToTotales($tipoTotal, $value)
  {
    $this->totals[$tipoTotal] += $value;
  }

  /**
   * Información xml del documento
   * 
   * @return void
   */
  public function getDocumentoPart()
  {
    $codigo_referencia = "";
    $tipo_documento_refererencia = "";
    $descripcion = "";
    $razon_envio = "";
    $hasguiaReferencia = false;
    $guiaReferencia = '';

    $hasOrdenCompra = false;
    $ordenCompra = "";

    if ($this->documento->hasImported()) {
      $ordenCompra = optional($this->documento->pedido())->CotNume ?? $this->documento->VtaPedi;
      $hasOrdenCompra = true;;
    }

    # Anticipo
    $anticipoXml = $this->getAnticipoPartDocReferencia();

    $notasXml = $this->getNotasDocumento();

    if ($this->documento->isNotaCredito() || $this->documento->isNotaDebito()) {
      $codigo_referencia = $this->documento->codigoReferencia();
      // $descripcion = $this->documento->tipo_nota->descripcion;
      $descripcion = $this->documento->VtaObse;
      $tipo_documento_refererencia = $this->documento->VtaTDR;      
      if ($this->documento->isNotaCredito()) {
        $razon_envio = $this->documento->vtaadoc;;
      } else {
        $razon_envio = "01";
      }
    } 
    
    else {
      $razon_envio = $this->documento->vtaadoc;

      // Guia de remisión
      if ($this->documento->hasGuiaReferenciaElectronica()) {
        $guiaReferencia = $this->getPartVariable(true, 'guiareferencia', $this->documento->guiaReferenciaCorrelative());
      } else {
        $guias = $this->documento->guias_ventas->load('guia');

        if ($guias->count()) {
          foreach ($guias as $guia) {
            $guiaReferencia .=  $this->getPartVariable(true, 'guiareferencia', $guia->guia->numero());
          }
        }
      }
    }

    $this->change_datas([
      ["codigo", $this->correlativo],
      ["fecha", $this->documento->VtaFvta],
      ["hora", $this->documento->VtaHora],
      ['razon_envio', $razon_envio],
      ['guia_referencia',  $guiaReferencia],
      ['codigo_referencia', $codigo_referencia],
      ["descripcion", $descripcion],
      ["docref_anticipo", $anticipoXml],
      ['tipo_documento_refererencia', $tipo_documento_refererencia],
      ["tipo_documento", $this->documento->TidCodi],
      ["notas", $notasXml],
      ["tipo_operacion", $this->documento->xmlInfo()->getTipoOperacion()],
      ["cantidad_items", $this->items->count()],
      ["orden_compra", $this->getPartVariable($hasOrdenCompra, 'ordencompra', $ordenCompra)],
    ], $this->documentInfo_XMLPART);
  }

  /**
   * Informacion de la empresa y la firma
   * 
   * @return void
   */
  public function getEmpresaPart()
  {
    $codigo_local = "0000";

    $this->change_datas([
      ["ruc_empresa",  $this->ruc_empresa],
      ["nombre_empresa", $this->nombre_empresa],
    ], $this->firmaData_XMLPART);

    $this->change_datas([
      ["nombrecorto_empresa",  $this->nombrecorto_empresa],
      ["nombre_empresa", $this->nombre_empresa],
      ["ruc_empresa",  $this->ruc_empresa],
      ["codigo_local",  $codigo_local],

    ], $this->empresaData_XMLPART);
  }

  /**
   * Informacion del cliente
   * 
   * @return void
   */
  public function getClientePart()
  {
    $cliente = optional($this->documento->cliente);
    $this->change_datas([
      ["nombre_cliente", $cliente->PCNomb],
      ["ruc_cliente",  $cliente->PCRucc],
      ["cliente_tipo_documento",  $cliente->TDocCodi],
    ], $this->clienteData_XMLPART);
  }  

  /**
   * Informacion del cliente
   * 
   * @return void
   */
  public function getFormaPagos()
  {
    $forma_pago_xml = '';


    # Percecion
    if ($this->documento->hasMontoPercepcion()) {
      $totalPercepcion = $this->documento->totales_documento->total_base_percepcion + $this->documento->totales_documento->percepcion;            
      $forma_pago_xml .= $this->getPartVariable(true, 'formapago_percepcion', ['Percepcion', '[moneda]',  fixedValue($totalPercepcion) ]);
    }

    # Contado
    if ($this->isContado) {
      $forma_pago_xml .= $this->documento->isNotaCredito() ? '' : $this->getPartVariable(true, 'formapago_contado', ['FormaPago', 'Contado']);
    }

    # Credito
    else {
      $creditos = $this->documento->getCreditos();

      // Titulo
      $forma_pago_xml .= $this->getPartVariable(true,  'formapago_creditos_titulo', ['FormaPago', 'Credito', getMonedaAbreviaturaSunat($this->documento->MonCodi), fixedValue($creditos->sum('monto'))]);

      // Partes
      $index = 1;
      foreach ($creditos as $credito) {
        $nombre = "Cuota" . agregar_ceros($index, 3, 0);
        $forma_pago_xml .= $this->getPartVariable(true,  'formapago_creditos_partes', ['FormaPago', $nombre, $credito->getMonedaAbreviaturaSunat(), $credito->monto, $credito->fecha_pago]);
        $index++;
      }
    }

    $this->change_datas([
      ["forma_pago", $forma_pago_xml],
    ], $this->formaPagoData_XMLPART);
  }



  /**
   * Informacion de los totales del documento
   * 
   * @return void
   */
  public function getLegalTotalesPart()
  {
    $totales = $this->documento->totales_documento;
    $anticipo = 0;
    $anticipo_igv = 0;
    $descuento_total  = 0;
    $total_importe = $totales->total_importe;
    $total_gravadas = $totales->total_gravadas + $totales->total_exonerada + $totales->total_inafecta;

      if ( $dctoGlobal = (float) $totales->descuento_global) {
        $dctoTotal = (float) $totales->descuento_total;
        // Si todo el descuento que hay en el doc es dcto global, no ponerlo, de lo contrario si hay un combinaciòn de dcto global y dcto por linea, si ponerlo
        $descuento_total =  ($dctoGlobal == $dctoTotal) ? 0 : $dctoTotal;
    }
    
    // loremp-ipsum-sump-ter-sdad-cswghf- Rodeadte de personas que compartan tu visiòn,
    if ($this->documento->hasAnticipo()) {
      $anticipo = $this->documento->VtaTotalAnticipo;
      // $anticipo_igv = $anticipo * config('app.parametros.igv_bace_cero');
      $anticipo_igv = $anticipo * $this->igvs->igvBaseCero;
      $total_gravadas += $anticipo;
      $anticipo += $anticipo_igv;
      $total_importe = $this->documento->VtaImpo + $anticipo;
    }

    $this->change_datas([
      ["LineExtensionAmount", fixedValue($total_gravadas)],
      ["TaxInclusiveAmount", fixedValue($total_importe)],
      ["AllowanceTotalAmount", fixedValue($descuento_total)],
      // ["ChargeTotalAmount", fixedValue($totales->percepcion)],
      ["ChargeTotalAmount", "0"],
      ["PrepaidAmount", fixedValue($anticipo)],
      ["PayableAmount", fixedValue($this->documento->VtaImpo)],
    ], $this->legalTotales_XMLPART);


    // if ( $this->tipo_documento != TipoDocumentoPago::NOTA_DEBITO && $this->tipo_documento != TipoDocumentoPago::NOTA_CREDITO ) {
    //   $this->change_datas([
    //     ["LineExtensionAmount", fixedValue($total_gravadas)],
    //     ["TaxInclusiveAmount", fixedValue($total_importe)],
    //     ["AllowanceTotalAmount", fixedValue($descuento_total)],
    //     ["ChargeTotalAmount", fixedValue($totales->percepcion)],
    //     ["PrepaidAmount", fixedValue($anticipo)],
    //     ["PayableAmount", fixedValue($this->documento->VtaImpo)],
    //   ], $this->legalTotales_XMLPART);
    // } else {
    //   $this->change_datas([
    //     ["LineExtensionAmount",  0 ],
    //     ["TaxInclusiveAmount",   0 ],
    //     ["AllowanceTotalAmount", 0 ],
    //     ["ChargeTotalAmount",    0 ],
    //     ["PrepaidAmount",        0 ],        
    //     ['PayableAmount', $this->documento->VtaImpo]
    //   ], $this->legalTotales_XMLPART);
    // }
  }

  /**
   * Procesar los items del documento
   * 
   * @return void
   */
  public function getItemPart()
  {
    $i = 1;
    $isAnticipo = $this->documento->hasAnticipo();
    foreach ($this->items as $item) {      
      $cifras = (object) $item->calculos();
      $descuento = $item->hasDescuento() ? $this->getDescuentoItem($item, $cifras->descuento, $cifras->valor_venta_bruto) : '';
      $producto = $item->producto;
      $show_product_code = $producto->hasCodeSunat();
      $code = $producto->getCodeSunat();
      $precio = $item->isGratuita() ? $cifras->valor_noonorosa : $cifras->precio_unitario;
      $show_placa = $item->hasPlaca();
      $isAnticipoItem = $isAnticipo ? $item->hasNameAnticipo() : false;
      $valor_unitario = $cifras->valor_unitario;      
      $valor_venta_por_item = $item->isGratuita() ? ($precio * $cifras->cantidad) :  $cifras->valor_venta_por_item;

      $this->items_XMLPART .=
        $this->change_datas(
          [
            ["tipoDocumentoLine", self::TIPO_DOCUMENTO_LINE[$this->tipo_documento]],
            ["tipoDocumentoQuantity", self::TIPO_DOCUMENTO_QUANTITY[$this->tipo_documento]],
            ["orden", $i++],
            ["tipo_precio", $item->xmlInfo()->getTipoPrecio()],
            ["cantidad", $cifras->cantidad],
            ["precio_item", $precio],
            ["item_info", $this->getPartVariable($show_placa, 'info_item', $item->getPlaca())],
            ["descuento", $descuento],
            ["tax_total", $this->getSubtotal($item,  $cifras, $isAnticipoItem)],
            ["descripcion", str_replace("  ", " ", $item->completeDescripcion())],
            ["valorVentaItem", decimal($this->convertValuesIfNCWithDiscounts($valor_venta_por_item))],
            ["codproductosunat", $this->getPartVariable($show_product_code, 'codproductosunat', $code)],
            ["valorUnitarioPorItem", decimal($this->convertValuesIfNCWithDiscounts($valor_unitario), 10)],
          ],
          $this->item_base,
          false
        );
    }
  }


  /**
   * Poner parte sobre detracción
   * 
   * 
   */
  public function getDetraccionePart()
  {
    $detraccion = "";

    if ($this->documento->hasDetraccion()) {

      $tipo_pago = "009";
      $banco_de_la_nacion = $this->empresa->numeroCuentaDetraccion() ?? '-';
      $cod_detraccion =  $this->documento->VtaDetrCode;
      $porc_detraccion = $this->documento->VtaDetrPorc;

      $detraccion .= $this->change_datas([
        ["tipo_pago", $tipo_pago],
        ["banco_de_la_nacion", $banco_de_la_nacion],
        ["cod_detraccion", $cod_detraccion],
        ["porc_detraccion", $porc_detraccion],
        ["total_detraccion", decimal($this->documento->totales_documento->detraccion)],
      ], $this->detraccionData_base, false);
    }

    $this->change_datas([
      ["detraccion", $detraccion],
    ], $this->detraccion_XMLPART);
  }

  /**
   * Poner parte sobre detracción
   * 
   * @return string
   */
  public function getAnticipoPart()
  {
    $anticipo = "";

    if ($this->documento->hasAnticipo()) {

      // $anticipo_value = $this->documento->VtaTotalAnticipo * config('app.parametros.igv_bace_uno');
      $anticipo_value = $this->documento->VtaTotalAnticipo * $this->igv->igvBaseUno;

      $anticipo .= $this->change_datas([
        ["anticipo_code", 1],
        ["total_anticipo", decimal($anticipo_value)],
      ], $this->anticipo_base, false);
    }

    $this->change_datas([
      ["anticipo", $anticipo],
    ], $this->anticipos_XMLPART);
  }


  public function getAnticipoPartDocReferencia()
  {
    $xml = "";

    if ($this->documento->hasAnticipo()) {
      $xml .= $this->change_datas([
        // Correlativo del documento relacionado        
        ['correlativo', $this->documento->VtaNumeAnticipo],
        // Tipo de documento relacionado
        ['tipo_documento', $this->documento->xmlInfo()->getDocumentReferenciaTipo()],
        // Status
        ['documento_status', $this->documento->xmlInfo()->getDocumentReferenceStatus()],
        // Tipo de documento de identidad
        ['tipo_documento_identidad', $this->documento->cliente->TDocCodi],
        // Documentode identidad
        ['documento_identidad', $this->documento->cliente->PCRucc],
      ], $this->anticipo_referencia_base, false);
    }

    return $xml;
  }

  public function getNotasDocumento()
  {
    $xml = "";

    $cifra_letra = $this->documento->cifra_letra();
    $xml .=  $this->getPartVariable(true, 'nota', [XmlInformation::NOTA_CODIGO_MONTO_CIFRA, "<![CDATA[SON: {$cifra_letra}]]>"]);

    // Notas para la detracción
    if ($this->documento->hasDetraccion()) {
      $xml .=  $this->getPartVariable(true, 'nota', [XmlInformation::NOTA_CODIGO_DETRACCION, "<![CDATA[Operación sujeta a detracción]]>"]);
    }

    // Notas para la detracción
    if ($this->documento->hasMontoPercepcion()) {
      $xml .=  $this->getPartVariable(true, 'nota', [XmlInformation::NOTA_CODIGO_PERCEPCION, "<![CDATA[COMPROBANTE DE PERCEPCIÓN]]>"]);
    }
    
    // Notas para la detracción
    if ($this->documento->hasMontoRetencion()) {
      $totales = $this->documento->totales_documento;
      $forma_pago = $this->isContado ? FormaPago::FORMA_PAGO_CONTADO_NOMBRE : FormaPago::FORMA_PAGO_CREDITO_NOMBRE;
      $moneda_abre = Moneda::getAbrev($this->documento->MonCodi);
      $monto_pagar =  fixedValue($totales->total_cobrado - $totales->retencion);
      $str = sprintf(
        "<![CDATA[FORMA DE PAGO: %s---IMPORTE NETO A PAGAR--- %s %s]]>",
        $forma_pago,
        $moneda_abre,
        $monto_pagar
      );

      $xml .=  $this->getPartVariable(true, 'nota_nocode', [strtoupper($str)]);
    }

    return $xml;
  }

  /**
   * Procesar la información en todas las partes del documento
   * 
   * @return void;
   */
  public function setDatasPartsXml()
  {
    $this->getDocumentoPart();
    $this->getEmpresaPart();
    $this->getClientePart();
    $this->getFormaPagos();
    $this->getAnticipoPart();
    $this->getItemPart();
    $this->getDescuentoGlobalPart();
    $this->getTaxTotalPart();
    $this->getLegalTotalesPart();
    $this->getDetraccionePart();
  }
}