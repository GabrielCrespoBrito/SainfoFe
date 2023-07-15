<?php

namespace App\Util\XmlInformation;

use Illuminate\Database\Eloquent\Model;

abstract class XmlInformation
{
	/**
	 * @var Illuminate\Database\Eloquent\Model
	 */
	protected $model;

	/**
	 * Codigo de notas
	 */
	const NOTA_CODIGO_MONTO_CIFRA = "1000";
	const NOTA_CODIGO_DETRACCION  = "2006";
  const NOTA_CODIGO_PERCEPCION  = "2000";

	/**
	 * Códigos – Tipo de Precio de Venta Unitario - Catalogo 16
	 * 
	 * */
	const PRECIO_UNITARIO_IGV = "01";
	const PRECIO_NO_ONOROSA = "02";

	/**
	 * Codigo de cargo o descuento - Anexo 53
	 * 
	 * */ 
	const OTROS_DESCUENTO = "00";
	const DESCUENTO_NO_AFECTA_IGV = "01";
	const DESCUENTO_AFECTA_IGV = "01";
	const DESCUENTO_GLOBALES_AFECTA_IGV = "02";
	const DESCUENTO_ANTICIPO = "04";

	const OTROS_CARGOS = "50";
	const PERCEPCION_VENTA_INTERNA = "51";
	const PERCEPCION_COMBUSTIBLE = "52";
	const PERCEPCION_TASA_ESPECIAL = "53";
	const OTROS_CARGOS_RELACIONADOS_AL_SERVICIO = "54";
	const OTROS_CARGOS_NO_RELACIONADOS_AL_SERVICIO = "55";
	const RETENCION_APLICADO = "62";

	/**
	 * Codigo de cargo o descuento 
	 * 
	 * */ 
	const INDICADOR_DESCUENTO = "false";
	const INDICADOR_CARGO = "true";


	/**
	 * Códigos de Tipos de Sistema de Cálculo del ISC - Catalogo 08
	 * 
	 * @return Array
	 * */
	const ISC_PRICE_TIPO =
	[
		'SISTEMA_VALOR' => "01",
		'MONTO_FIJO' => "02",
		'SISTEMA_PRECIO_PUBLIC' => "03"
	];

  const CATALOGO_61 = [
     '01' => 'Factura electrónica',
    //  '01' => 'Factura emitida en formatos impresos o importados',
     '03' => 'Boleta de venta electrónica',
    //  '03' => 'Boleta de venta emitida en formatos impresos o importados',
     '04' => 'Liquidación de compra electrónica',
    //  '04' => 'Liquidación de compra emitida en formatos impresos o importados',
     '09' => 'Guía de remisión remitente electrónica',
    //  '09' => 'Guía de remisión remitente emitida en formatos impresos o importados',
     '12' => 'Ticket o cinta emitido por máquina registradora',
     '31' => 'Guía de remisión transportista electrónica',
    //  '31' => 'Guía de remisión transportista emitida en formatos impresos o importados',
     '49' => 'Constancia de depósito - IVAP (Ley N.° 28211)',
     '50' => 'Declaración Aduanera de Mercancías',
     '52' => 'Declaración Simplificada',
     '80' => 'Constancia de depósito - Detracción',
     '81' => 'Código de autorización emitida por el SCOP',
     '82' => 'Declaración jurada de mudanza',
  ];


	const TAXES = [
		'GRAVADA' => [
			'TaxCategory_ID' => 'S',
			'TaxExemptionReasonCode' => 10,
			'TaxScheme_ID' => 1000,
			'TaxScheme_Name' => 'IGV',
			'TaxScheme_TaxTypeCode' => 'VAT',
		],
		'EXONERADA' => [
			'TaxCategory_ID' => 'E',
			'TaxExemptionReasonCode' => 20,
			'TaxScheme_ID' => 9997,
			'TaxScheme_Name' => 'EXO',
			'TaxScheme_TaxTypeCode' => 'VAT',
		],
		'INAFECTA' => [
			'TaxCategory_ID' => 'O',
			'TaxExemptionReasonCode' => 30,
			'TaxScheme_ID' => 9998,
			'TaxScheme_Name' => 'INA',
			'TaxScheme_TaxTypeCode' => 'FRE',
		],
		'GRATUITA' => [
			'TaxCategory_ID' => 'Z',
			'TaxExemptionReasonCode' => 31,
			'TaxScheme_ID' => 9996,
			'TaxScheme_Name' => 'GRA',
			'TaxScheme_TaxTypeCode' => 'FRE',
		],
	];
  
	const TIPOS_OPERACION = [
		'VENTA_INTERNA' => "0101",
    'VENTA_PERCEPCION' => "2001",
		'OPERACION_SUJETA_DETRACCION' => "1001",
	];

	/**
	 * Códigos de - Documentos Relacionados Tributarios - Catalogo 12
	 * 
	 * @return Array
	 * */
	const TIPO_DOCUMENTO_RELACIONADO = [
		 "FACTURA_ERROR_RUC" => "01",
		 "FACTURA_ANTICIPO" => "02",
		 "BOLETA_ANTICIPO" => "03",
		 "TICKET_SALIDA_ENAPU" => "04",
		 "CODIGO_SCOP" => "05",
		 "OTROS" => "99",
	];





/**
 * Modelo
 * 
 * @param $model Illuminate\Database\Eloquent\Model;
 */
	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	/**
	 *	Retornar modelo que estemos trabajando
	 * 
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 *	Retornar toda la información de un impuesto especifico
	 * 
	 * @return Array
	 */
	public function getTaxInfo($tax)
	{
		return self::TAXES[$tax];
	}

	/**
	 * Códigos – Tipo de Precio del ISC - Catalogo
	 * 
	 * @return String
	 */
	public function getISCTipo(): String
	{
		return self::ISC_PRICE_TIPO['DEFAULT'];
	}


}