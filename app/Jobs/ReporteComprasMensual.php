<?php

namespace App\Jobs;

use App\Compra;
use App\TipoDocumentoPago;

class ReporteComprasMensual
{
  /**
   * Data del reporte
   */
  public $data = [];

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($mes)
  {
    $this->mes = $mes;

    $this->handle();
  }

  public function getQuery()
  {
    return
      Compra::with('cliente_with')
      ->where('mescodi', $this->mes)
      ->whereNotIn('TidCodi', [TipoDocumentoPago::NOTA_VENTA, 50])
      ->get()
      ->groupBy('TidCodi', 'asc');
  }

  protected $current_fecha;
  protected $fecha_desde;
  protected $fecha_hasta;
  protected $local;


  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $query =  $this->getQuery();
    $data = [];

    $this->addToData($data);
    $total_reporte = &$data['total'];

    $this->processTipoDocumento($query, $data, $total_reporte);
    $this->data = $data;
  }

  /**
   * Procesar los dias por iterar
   *
   * @return void
   */
  public function processTipoDocumento($compras_group_documento, &$data, &$total_reporte)
  {
    foreach ($compras_group_documento as $tidcodi => $compras) {
      $data['items'][$tidcodi] = [];
      $add = &$data['items'][$tidcodi];
      $this->addToData($add, ['data' => $tidcodi, 'codigo' => $tidcodi,  'nombre' => nombreDocumento($tidcodi),  'count' => count($compras)]);
      $total_tipodocumento = &$add['total'];
      $this->processDocumentos($compras, $add, $total_reporte, $total_tipodocumento);
    }
  }

  /**
   * Procesar los documentos de un tipo de documento especifico
   * 
   * @return void
   */
  public function processDocumentos($compras, &$addToAdd, &$total_reporte, &$total_tipodocumento)
  {
    foreach ($compras as $compra) {
      $addToAdd['items'][$compra->CpaOper] = [];
      $add = &$addToAdd['items'][$compra->CpaOper];
      $this->addToData($add, $this->getInfoItem($compra), false);
      $total_item = &$add['total'];
      $this->processItem($compra, $total_reporte, $total_tipodocumento, $total_item);
    }
  }

  /**
   * Procesar el item de la venta
   * 
   * @return void
   */
  public function processItem($compra,  &$total_reporte, &$total_tipodocumento, &$total_documento)
  {
    $totales_compra = $this->getTotalDocumento($compra);

    $this->addToTotal($total_reporte, $compra, $totales_compra);
    $this->addToTotal($total_tipodocumento, $compra, $totales_compra);
    $this->addToTotal($total_documento, $compra, $totales_compra);
  }

  public function getTotalDocumento($compra)
  {
    $isSol = $compra->isSol();
    $tc = $compra->CpaTCam;

    return (object) [
      'base_imponible' => $isSol ? $compra->Cpabase : $compra->Cpabase * $tc,
      'exonerada' => 0,
      'inafecta' => 0,
      'isc' => 0,
      'igv' => $isSol ? $compra->CpaIGVV : $compra->CpaIGVV * $tc,
      'icbper' => 0,      
      'importe_soles' => $isSol ? $compra->CpaImpo : $compra->CpaImpo * $tc,
      'importe_dolares' => $isSol ? 0 : $compra->CpaImpo,
    ];
  }

  public function addToTotal(&$total, $compra, $totales_compra)
  {
    $total['base_imponible'] += $totales_compra->base_imponible;
    $total['exonerada'] += $totales_compra->exonerada;
    $total['inafecta'] += $totales_compra->inafecta;
    $total['isc'] += $totales_compra->isc;
    $total['igv'] += $totales_compra->igv;
    $total['icbper'] += $totales_compra->icbper;
    $total['importe_soles'] += $totales_compra->importe_soles;
    $total['importe_dolares'] += $totales_compra->importe_dolares;
  }

  /**
   * Establecer la data del reporte
   * 
   * @return array
   */
  public function addToData(&$arrAdd, $info = [], $addIndexItems = true)
  {
    # Info
    $arrAdd['info'] = $info;

    # Items
    if ($addIndexItems) {
      $arrAdd['items'] = [];
    }

    # Total
    $arrAdd['total'] = [
      'base_imponible' => 0,
      'exonerada' => 0,
      'inafecta' => 0,
      'isc' => 0,
      'igv' => 0,
      'icbper' => 0,
      'importe_soles' => 0,
      'importe_dolares' => 0,
    ];

    return $arrAdd;
  }

  /**
   * Establecer la informaciòn del item
   * 
   * @return array
   */
  public function getInfoItem($compra)
  {
    return [
      'id' => $compra->CpaOper,
      'fecha' => $compra->CpaFCon,
      'fecha_vencimiento' => $compra->CpaFven,
      'tipo_documento' => $compra->TidCodi,
      'serie' => $compra->CpaSerie,
      'numero' => $compra->CpaNumee,
      'tipo_documento_cliente' => $compra->cliente_with->TdoCodi,
      'documento_cliente' => $compra->cliente_with->PCRucc,
      'nombre_cliente' => $compra->cliente_with->PCNomb,
      'tipo_cambio' => $compra->CpaTCam,
      'fecha_documento_referencia' => '',
      'tipo_documento_referencia' => '',
      'serie_documento_referencia' => '',
      'numero_documento_referencia' => '',
    ];
  }

  /**
   * Obtener la data del reporte
   * 
   * @return array
   */
  public function getData()
  {
    return $this->data;
  }
}
