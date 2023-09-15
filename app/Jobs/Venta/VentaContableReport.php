<?php

namespace App\Jobs\Venta;

use App\Moneda;
use App\TipoDocumentoPago;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VentaContableReport
{
  public $info;
  public $mes;
  public $fecha_inicio;
  public $fecha_final;
  public $tipo;
  public $estadoSunat;

  public function __construct($fecha_inicio, $fecha_final, $tipo, $estadoSunat)
  {
    $this->estadoSunat = $estadoSunat == "todos" ? null : $estadoSunat;
    $this->fecha_inicio =  $fecha_inicio;
    $this->fecha_final =  $fecha_final;
    $this->tipo =  $tipo == "todos" ? null : $tipo;
  }



  public function getQuery()
  {
    $docs = DB::connection('tenant')->table('ventas_cab')
      ->join('prov_clientes', function ($join) {
        $join
          ->on('prov_clientes.PCCodi', '=', 'ventas_cab.PCCodi')
          ->on('prov_clientes.EmpCodi', '=', 'ventas_cab.EmpCodi')
          ->where('prov_clientes.TipCodi', '=', 'C');
      })
      ->whereBetween('ventas_cab.VtaFvta', [$this->fecha_inicio, $this->fecha_final])
      ->whereNotIn('ventas_cab.TidCodi', [TipoDocumentoPago::NOTA_VENTA, 50])
      ->select([
        'ventas_cab.EmpCodi',
        'ventas_cab.TidCodi',
        'ventas_cab.VtaFvta',
        'VtaFVen',
        'TidCodi',
        'VtaNumee',
        'VtaSeri',
        'ventas_cab.VtaOper',
        'prov_clientes.PCNomb',
        'prov_clientes.PCCodi',
        'prov_clientes.PCRucc',
        'prov_clientes.TDocCodi',
        'ventas_cab.VtaExon',
        'ventas_cab.VtaDcto',
        'ventas_cab.VtaInaf',
        'ventas_cab.VtaISC',
        'ventas_cab.VtaIGVV',
        'ventas_cab.VtaImpo',
        'ventas_cab.VtaTcam',
        'ventas_cab.VtaFMail',
        'ventas_cab.icbper',
        'ventas_cab.Vtabase',
        'ventas_cab.VtaFVtaR',
        'ventas_cab.VtaTDR',
        'ventas_cab.VtaSeriR',
        'ventas_cab.VtaNumeR',
        'ventas_cab.MonCodi'
      ]);


    if ($this->estadoSunat) {
      $docs->where('VtaFMail', '=', $this->estadoSunat);
    }

    if ($this->tipo) {
      $docs->where('TidCodi', '=', $this->tipo);
    }

    return
      $docs
      ->orderBy('TidCodi', 'asc')
      ->orderBy('VtaSeri', 'asc')
      ->orderBy('VtaNumee', 'asc')
      ->get()
      ->groupBy('TidCodi');
  }


  /**
   * Execute the job.
   *
   * @return $this
   */
  public function handle()
  {
    $query =  $this->getQuery();
    $data = [];

    $this->addToData($data);
    $total_reporte = &$data['total'];

    $this->processTipoDocumento($query, $data, $total_reporte);
    $this->data = $data;

    return $this;
  }

  /**
   * Procesar los dias por iterar
   *
   * @return void
   */
  public function processTipoDocumento($ventas_group_documento, &$data, &$total_reporte)
  {
    foreach ($ventas_group_documento as $tidcodi => $ventas) {
      $data['items'][$tidcodi] = [];
      $add = &$data['items'][$tidcodi];
      $this->addToData($add, ['data' => $tidcodi, 'codigo' => $tidcodi,  'nombre' => nombreDocumento($tidcodi),  'count' => count($ventas)]);
      $total_tipodocumento = &$add['total'];
      $this->processDocumentos($ventas, $add, $total_reporte, $total_tipodocumento);
    }
  }

  /**
   * Procesar los documentos de un tipo de documento especifico
   * 
   * @return void
   */
  public function processDocumentos($ventas, &$addToAdd, &$total_reporte, &$total_tipodocumento)
  {
    foreach ($ventas as $venta) {
      $addToAdd['items'][$venta->VtaOper] = [];
      $add = &$addToAdd['items'][$venta->VtaOper];
      $this->addToData($add, $this->getInfoItem($venta), false);
      $total_item = &$add['total'];
      $this->processItem($venta, $total_reporte, $total_tipodocumento, $total_item);
    }
  }

  /**
   * Procesar el item de la venta
   * 
   * @return void
   */
  public function processItem($venta,  &$total_reporte, &$total_tipodocumento, &$total_documento)
  {
    $totales_venta = $this->getTotalDocumento($venta);

    $this->addToTotal($total_reporte, $venta, $totales_venta);
    $this->addToTotal($total_tipodocumento, $venta, $totales_venta);
    $this->addToTotal($total_documento, $venta, $totales_venta);
  }

  public function getTotalDocumento($venta)
  {
    $isSol = $venta->MonCodi == Moneda::SOL_ID;
    $tc = $isSol ? 1 : $venta->VtaTcam;
    $isNC = $venta->TidCodi == "07";

    if ($isNC) {
      $venta->Vtabase = convertNegative($venta->Vtabase);
      $venta->VtaExon = convertNegative($venta->VtaExon);
      $venta->VtaInaf = convertNegative($venta->VtaInaf);
      $venta->VtaISC  = convertNegative($venta->VtaISC);
      $venta->VtaIGVV = convertNegative($venta->VtaIGVV);
      $venta->icbper  = convertNegative($venta->icbper);
      $venta->VtaImpo = convertNegative($venta->VtaImpo);
      $venta->VtaDcto = convertNegative($venta->VtaDcto);
    }

    return (object) [
      'base_imponible' => $isSol ? $venta->Vtabase : $venta->Vtabase * $tc,
      'exonerada' => $isSol ? $venta->VtaExon : $venta->VtaExon * $tc,
      'inafecta' => $isSol ? $venta->VtaInaf : $venta->VtaInaf * $tc,
      'isc' => $isSol ? $venta->VtaISC : $venta->VtaISC * $tc,
      'dcto' => $isSol ? $venta->VtaDcto : $venta->VtaDcto * $tc,
      'igv' => $isSol ? $venta->VtaIGVV : $venta->VtaIGVV * $tc,
      'tc' => $tc,
      'icbper' => $isSol ? $venta->icbper : $venta->icbper * $tc,
      'importe_soles' => $isSol ? $venta->VtaImpo : $venta->VtaImpo * $tc,
      'importe_dolares' => $isSol ? 0 : $venta->VtaImpo,
    ];
  }

  public function addToTotal(&$total, $compra, $totales_compra)
  {
    $total['base_imponible'] += $totales_compra->base_imponible;
    $total['exonerada'] += $totales_compra->exonerada;
    $total['inafecta'] += $totales_compra->inafecta;
    $total['isc'] += $totales_compra->isc;
    $total['igv'] += $totales_compra->igv;
    $total['tc'] += $totales_compra->tc;
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
      'tc' => 0,
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
  public function getInfoItem($venta)
  {
    $fecha_vencimiento  =  $venta->VtaFVen ? newformat_date($venta->VtaFVen) : null;

    return [
      "fecha_emision" => newformat_date($venta->VtaFvta),
      // "fecha_vencimiento"=> $venta->VtaFVen ?? newformat_date($venta->VtaFVen),
      "fecha_vencimiento" => $fecha_vencimiento,
      "tipo_documento" => $venta->TidCodi,
      "serie" => $venta->VtaSeri,
      "numero" => $venta->VtaNumee,
      "cliente_tipo_documento" => $venta->TDocCodi,
      "cliente_documento" =>  $venta->PCRucc,
      "cliente_nombre" => $venta->PCNomb,
      "tipo_cambio" => fixedValue($venta->VtaTcam),
      "docref_fecha_emision" => $venta->VtaFVtaR ? newformat_date($venta->VtaFVtaR) : '',
      "docref_tipo_documento" => $venta->VtaTDR,
      "docref_serie" => $venta->VtaSeriR,
      "docref_numero" => $venta->VtaNumeR,
      "estado" => $venta->VtaFMail,
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
