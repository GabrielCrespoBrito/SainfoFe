<?php

namespace App\Http\Controllers\Reportes\KardexValorizado;

use App\Mes;
use App\Local;
use Illuminate\Support\Facades\DB;
use App\Util\PDFGenerator\PDFGenerator;

class ReporteKardex
{
  protected $local = null;
  protected $mes = null;
  protected $empresa = null;
  
  # Información items
  protected $infoItems = [];
  #Item actual
  protected $currentItem;
  # Donde vamos a ir guardando la data
  protected $rowData = [];
  #Item actual
  protected $detalle_report;

  public function  __construct( $mes, $local, $detalle_report = true )
  {
    $this->local = $local;
    $this->mes = $mes;
    $this->empresa = get_empresa();
    $this->detalle_report = $detalle_report;
  }

  public function getInfoReporte()
  {
    return  [
      'periodo' => Mes::findOrfail($this->mes)->mesnomb,
      'nombre' => $this->empresa->EmpNomb,
      'ruc' => $this->empresa->EmpLin1,
      'establecimientos' => 1,
      'id' => $this->empresa->id(),
      'mes' => '',
      'nombre_local' => Local::find($this->local)->LocNomb,
      'codigo_local' => "0000",
      'data' => $this->infoItems
    ];
  }

  public function search($mes, $mesExacto = true, $detCodi = null)
  {
    $dbMainName = config('database.connections.mysql.database');
    ini_set('max_execution_time', 500000);

    $simbol = $mesExacto ? '=' : '<=';

    $query = DB::connection('tenant')->table('guia_detalle')
      ->join('guias_cab', function ($join) {
        $join
          ->on('guias_cab.GuiOper', '=', 'guia_detalle.GuiOper')
          ->on('guias_cab.EmpCodi', '=', 'guia_detalle.empcodi');
      })
      ->join('productos', function ($join) {
        $join
          ->on('productos.ProCodi', '=', 'guia_detalle.DetCodi')
          ->on('productos.empcodi', '=', 'guias_cab.EmpCodi');
      })
      ->join('unidad', function ($join) {
        $join
          ->on('unidad.UniCodi', '=', 'guia_detalle.UniCodi')
          ->on('unidad.empcodi', '=', 'guias_cab.EmpCodi');
      })
      ->join("{$dbMainName}.uniproducto", function ($join) use($dbMainName) {
        $join->on('productos.unpcodi', '=', "{$dbMainName}.uniproducto.UnPCodi");
      })
      ->join("{$dbMainName}.tipo_existencia", function ($join) use ($dbMainName) {
        $join->on('productos.tiecodi', '=', "{$dbMainName}.tipo_existencia.TieCodi");
      })
      //20010100
      // ->whereIn( 'guia_detalle.DetCodi' , ['20030101' , '20410316', '20010101'])    
      ->where('guias_cab.EmpCodi', $this->empresa->empcodi)
      ->where('guias_cab.mescodi', $simbol, $mes);

    if ($this->local != "000") {
      $query->where('guias_cab.Loccodi', $this->local);
    }

    if ($detCodi) {
      $query->where('guia_detalle.DetCodi', '=', $detCodi);
    }

    return $query->select(
      'guia_detalle.DetCodi',
      'guia_detalle.GuiOper',
      'guia_detalle.DetNomb',
      'guia_detalle.DetPrec',
      'guia_detalle.Detcant',
      'guias_cab.TidCodi',
      'guias_cab.docrefe',
      'guias_cab.GuiFemi',
      'guias_cab.EntSal',
      'productos.ProNomb',
      'productos.ID',
      'productos.tiecodi',
      'uniproducto.UnPNomb as unidad_nombre',
      'tipo_existencia.TieNomb as tipo_existencia_nombre',
      'productos.unpcodi',
      'unidad.UniEnte',
      'unidad.UniMedi'
    );
  }

  /**
   * 
   * @return void
   */
  public function make()
  {
    $search = $this->search($this->mes);

    # Productos agrupados
    $items_group = $search
      ->get()
      ->sortBy('GuiFemi')
      ->groupBy('DetCodi');

    $this->processGroupItem($items_group);
  }


  public function getDataItem($saldoInicial, $items, $saldoFinal = [])
  {
    $item = $this->getItem()->first();

    return [
      'code' => $item->DetCodi,
      'descripcion' => $item->DetNomb,
      'codigo_tipo_existencia' => $item->tiecodi,
      'nombre_tipo_existencia' => $item->tipo_existencia_nombre,
      'tipo' => '01',
      'nombre_unidad' => $item->unidad_nombre,
      'codigo_unidad' => $item->unpcodi,
      'codigo_sunat_unidad' => $item->unpcodi,
      'stock_inicial' => $saldoInicial,
      'cant_total_ingreso' => $saldoInicial['cant_total_ingreso'],
      'cant_total_salida' => $saldoInicial['cant_total_salida'],
      'stock_final' => $saldoFinal,
      'items' => $items,
    ];
  }

  /**
   * Establecer el item actual con que se esta trabajando
   * 
   * @return void
   */
  public function setCurrentItem($item)
  {
    $this->item = $item;
  }

  /**
   * Obtener el item actual con que se esta trabajando
   * 
   * @return void
   */
  public function getItem()
  {
    return $this->item;
  }


  /**
   * Obtener el saldo inicial del mes anterior
   * 
   * @return array
   */
  public function getSaldoInicial($detcodi)
  {
    $mes = Mes::getMesAnterior($this->mes);
    $search = $this->search($mes, false, $detcodi);
    $items = $search
      ->get()
      ->sortBy('GuiFemi');

    $process = new ProcessProducto($items, false);
    $process->setSaldo(ProcessProducto::FORM_DATA);
    $process->processItems();

    return $process->getSald();
  }

  public function processGroupItem($items_group)
  {
    foreach ($items_group as $detcodi => $item_group) {

      $this->setCurrentItem($item_group);
      $saldoInicial = $this->getSaldoInicial($detcodi);
      //
      $process = new ProcessProducto($item_group, $this->detalle_report);
      $process->setSaldo($saldoInicial);

      $process->processItems();
      $data = $process->getData();

      $saldoFinal = $this->detalle_report ? [] : $process->getSald();

      array_push($this->infoItems, $this->getDataItem($saldoInicial, $data, $saldoFinal));
    }
  }
}
