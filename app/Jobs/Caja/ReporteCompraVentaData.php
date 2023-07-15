<?php

namespace App\Jobs\Caja;

use App\Moneda;
use App\TipoDocumentoPago;

class ReporteCompraVentaData
{
	public $caja;
  public $data;
	public $tipo;
  public $agrupacion;
	public $isReporteVenta;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($caja, $tipo, $agrupacion)
	{
		$this->caja = $caja;
		$this->tipo = $tipo;
    $this->agrupacion = $agrupacion;
		$this->isReporteVenta = $tipo == "ventas";
		$this->process();
	}

	/**
	 * Obtener el nombre del reporte
	 *
	 * @return void
	 */
	public function getNombreReporte()
	{
		return $this->isReporteVenta ? 'Reporte de caja - Ventas' :  'Reporte de caja - Compras';
	}

	public function getNombreDocumento()
	{
		return $this->isReporteVenta ? "Reporte de caja ({$this->caja->CajNume}) - Ventas" :  "Reporte ({$this->caja->CajNume}) Compras";
	}


	/**
	 * Obtener informacion de la cabecera
	 * 
	 * @return array
	 */
	public function getDataCabecera()
	{
		$empresa = $this->caja->empresa;

		return [
			'empresaNombre' => $empresa->EmpNomb,
			'nombreReporte' => $this->getNombreReporte(),
			'nombreDocumento' => $this->getNombreDocumento(),
			'cajaUsuario' => $this->caja->User_Crea,
			'empresaRuc' => $empresa->ruc(),
			'fechaApertura' => $this->caja->CajFech,
			'fechaCierre' => $this->caja->CajFecC,
			'cajaNumero' => $this->caja->CajNume,
			'fechaReporte' => date('Y-m-d m:h:s'),
		];
	}

	public function getGroupDocs()
	{
		return $this->tipo == 'ventas' ? 
		$this->caja->ventas->groupBy('TidCodi') :
		$this->caja->compras->groupBy('TidCodi');
	}

	/**
	 * El formato del array de totales 
	 *
	 * @return void
	 */
	public function getFormatTotal()
	{
		return [
			'importe' => 0,
			'pago' => 0,
			'saldo' => 0
		];
	}


	/**
	 * El formato del array de
	 *
	 * @return void
	 */
	public function getFormatItemsData()
	{
		return [
			'tipos' => [],

			// Totales
			'totales' => [
				Moneda::SOL_ID => $this->getFormatTotal(),
				Moneda::DOLAR_ID => $this->getFormatTotal(),
			]
		];


	}


	/**
	 * Formato de informacion de cada tipo de documento
	 * 
	 * @return array
	 */
	public function getFormatTipoDocumentoData()
	{
		return [

			'docs' => [],

			'totales' => [
				Moneda::SOL_ID => $this->getFormatTotal(),
				Moneda::DOLAR_ID => $this->getFormatTotal(),
			],

			'items' => 0,

			'nombreTipo' => ''
		];

	}

	/**
	 * Obtener informacion de cada documento 
	 * 
	 * @param $doc Model
	 * 
	 * @return array
	 */	
	public function getDataDoc($doc)
	{
		$importe = $doc->importe();
		$pago = $doc->pago();
		$saldo = $doc->saldo();

		return [
			'nroDoc' => $doc->numero(),
			'fechaEmision' => $doc->fechaEmision(),
			'docRef' => $doc->documentoReferencia(),
			'clienteRazonSocial' => strtoupper($doc->clienteRazonSocial()),
			'estado' => $doc->estado(),
			'moneda' => $doc->monedaAbbreviatura(),
      'usuario' => $doc->User_Crea,
			'importe' => math()->decimal($importe,2) ,
			'pago' => math()->decimal($pago,2),
			'saldo' => math()->decimal($saldo,2),
			'condicion' => $doc->condicion(),
		];
	}

	/**
	 * Sumar cantidades a los arrays de totales
	 *
	 * @param integer|string $moneda
	 * @param integer|string $pago
	 * @param integer|string $importe
	 * @param integer|string $saldo
	 * @param array $totalTipo
	 * @param array $totalGlobal
	 * 
	 * @return array
	 */
	public function sumToTotals($moneda, $pago, $importe, $saldo, &$totalArray)
	{
		// Sumar al total del tipo de documento
		$totalArray[$moneda]['importe'] +=  $importe;
		$totalArray[$moneda]['pago'] += $pago;
		$totalArray[$moneda]['saldo'] += $saldo;
	}

	public function convertDecimalTotalCantidad(&$totalArray)
	{
		$totalArray["01"]['importe'] = math()->decimal($totalArray["01"]['importe'],2);
		$totalArray["01"]['pago'] = math()->decimal($totalArray["01"]['pago'],2);
		$totalArray["01"]['saldo'] = math()->decimal($totalArray["01"]['saldo'],2);
		$totalArray["02"]['importe'] = math()->decimal($totalArray["02"]['importe'], 2);
		$totalArray["02"]['pago'] = math()->decimal($totalArray["02"]['pago'], 2);
		$totalArray["02"]['saldo'] = math()->decimal($totalArray["02"]['saldo'], 2);
	}

	/**
	 * Obtener informacion de los documentos
	 * 
	 * @return array
	 */
	public function getDataDocs()
	{
		$groupDocs = $this->getGroupDocs();
		$items = $this->getFormatItemsData();

		// Iterar los tipos de documentos 
		foreach ($groupDocs as $tipo => $docs) {

			$tipo = (string) $tipo;

			$items['tipos'][$tipo] = $this->getFormatTipoDocumentoData();

			// Iterar los documentos
			foreach ($docs as $doc) {
				$data = $this->getDataDoc($doc);
				$items['tipos'][$tipo]['docs'][] = $data;
				
				// Sumar cantidades a los totales por tipo y global
				$this->sumToTotals($doc->getMoneda(), $data['importe'], $data['pago'], $data['saldo'],	$items['tipos'][$tipo]['totales']);
				$this->sumToTotals( $doc->getMoneda(), $data['importe'], $data['pago'], $data['saldo'], $items['totales'] );
			}

			$items['tipos'][$tipo]['nombreTipo'] = $tipo . ' ' .  TipoDocumentoPago::getNombreDocumento($tipo);
			$items['tipos'][$tipo]['items'] = count($docs);

			$this->convertDecimalTotalCantidad($items['tipos'][$tipo]['totales']);
		}

		$this->convertDecimalTotalCantidad($items['totales']);

		return [
			'items' => $items
		];
	}


	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function process()
	{
		$data = array_merge( $this->getDataCabecera(), $this->getDataDocs() );

		$this->setData($data);
	}


	public function setData( array $data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

}