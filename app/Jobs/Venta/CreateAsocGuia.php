<?php

namespace App\Jobs\Venta;

use App\Venta;
use Exception;
use App\GuiaSalida;
use App\GuiaSalidaItem;
use App\SerieDocumento;
use App\TipoMovimiento;
use App\Util\ResultTrait;
use Illuminate\Support\Facades\DB;
use App\Models\GuiaVenta\GuiaVenta;
use Illuminate\Support\Facades\Log;

class CreateAsocGuia
{
  use ResultTrait;

  protected $venta;
  protected $asociar_accion;
  protected $tipo_guia;
  protected $guias;
  protected $id_almacen;
  protected $tipo_movimiento;
  protected $guia_id = null;
  protected $guiasIds;
  
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($venta, $tipo_guia, $guias, $id_almacen = null, $tipo_movimiento = null, $guiasIds = null)
  {
    $this->venta = $venta;
    $this->tipo_guia = $tipo_guia;
    $this->asociar_accion = $this->tipo_guia == Venta::GUIA_ACCION_ASOCIAR;
    $this->guias = $guias;
    $this->id_almacen = $id_almacen;
    $this->tipo_movimiento = $tipo_movimiento;
    $this->guiasIds = $guiasIds;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $this->setSuccess(null);
    
    try {
      $this->asociar_accion ? $this->associateGuias() : $this->createGuia();
    } catch (Exception $th) {
      $this->setError($th->getMessage());
      $this->deleteInfoGuia();
    }

    return $this;
  }

  public function deleteInfoGuia()
  {
    // @TODO cuando es asociada, eliminar los registros de guias_ventas
    if ($this->asociar_accion) {
    }
    // Cuandose envia a crear la guia
    else {

      if (!$this->guia_id) {
        return;
      }

      if (($guia = GuiaSalida::find($this->guia_id)) == null) {
        return;
      }

      // Quitarle el GuiOper
      $this->venta->update(['GuiOper' => null]);

      // Si es electronica quitar el correlativo
      if ($guia->hasFormato()) {
        SerieDocumento::reverseNumeracion($guia);
      }

      $guia->deleteComplete();
    }
  }

  public function createGuia()
  {
    $isElectronica = $this->tipo_guia == Venta::GUIA_ACCION_ELECTRONICA || $this->tipo_guia == Venta::GUIA_ACCION_TRANSPORTISTA;
    $tipoGuia = $this->tipo_guia == Venta::GUIA_ACCION_TRANSPORTISTA ? GuiaSalida::TIPO_GUIA_TRANSPORTISTA : GuiaSalida::TIPO_GUIA_REMISION;
    $isNotaCredito  = $this->venta->isNotaCredito();
    $tipo_movimiento = $isNotaCredito ? TipoMovimiento::DEFAULT_INGRESO : TipoMovimiento::DEFAULT_SALIDA;
    $tipo_ingreso_detalle = $isNotaCredito ? GuiaSalidaItem::TIPO_COMPRA : GuiaSalidaItem::TIPO_VENTA;
    $this->guia_id = GuiaSalida::createGuia($this->venta->VtaOper, true,  $this->id_almacen, $tipo_movimiento, $isElectronica, '', null, false, $tipoGuia );
    $this->createItems($tipo_ingreso_detalle, $isNotaCredito,);
  }

  public function createItems($tipo_ingreso_detalle, $isNotaCredito)
  {
    if ($this->guiasIds) {

      $guiaItems = DB::connection('tenant')->table('guia_detalle')
        ->whereIn('GuiOper', $this->guiasIds )
        ->select([
          'Linea',
          'GuiOper',
          'UniCodi',
          'Detcant',
          'DetNomb',
          'DetPrec',
          'MarNomb',
          'CpaVtaCant',
          'DetImpo',
          'DetCodi',
          'DetUnid',
          'DetFact'
        ]);

      $items = $guiaItems->get();

      // Eliminar cabeceras
      DB::connection('tenant')->table('guias_cab')
        ->whereIn('GuiOper', $this->guiasIds)
        ->delete();


      // Eliminar detalles
      $guiaItems->delete();

      $data = [];
      $lastLinea = ((int) GuiaSalidaItem::lastId()) + 1;

      for ($i = 0, $orden = 1; $i < $items->count(); $i++) {
        $item = $items[$i];
        if (!key_exists($item->DetCodi, $data)) {
          $data[$item->DetCodi] = [
            'DetItem' => math()->addCero($orden, 2),
            'GuiOper' => $this->guia_id,
            'Linea' =>   math()->addCero($lastLinea, 8),
            'UniCodi' => $item->UniCodi,
            'DetNomb' => $item->DetNomb,
            'MarNomb' => $item->MarNomb,
            'Detcant' => $item->Detcant * $item->DetFact,
            'DetPrec' => 0,
            'DetDct1' => 0,
            'DetDct2' => 0,
            'DetImpo' => 0,
            'DetPeso' => 0,
            'DetUnid' => $item->DetUnid,
            'DetCodi' => $item->DetCodi,
            'DetCSol' => 0,
            'DetCDol' => 0,
            'CpaVtaCant' => convertNegative($item->Detcant * $item->DetFact),
            'CpaVtaOpe' => $this->venta->VtaOper,
            'DetTipo' => GuiaSalidaItem::TIPO_VENTA,
            'DetFact' => 1,
            'empcodi' => empcodi(),
          ];
          $orden++;
          $lastLinea++;
        } else {
          $data[$item->DetCodi]['Detcant'] += $item->Detcant * $item->DetFact;
          $data[$item->DetCodi]['CpaVtaCant'] = convertNegative($data[$item->DetCodi]['Detcant']);
        }
      }

      foreach ($data as $guiaSalidaData) {
        $guiaItem = GuiaSalidaItem::create($guiaSalidaData);
        $guiaItem->updateStock2($guiaItem->DetCodi);
      }
    } else {
      foreach ($this->venta->items as $item) {
        GuiaSalidaItem::createItem($item, $this->guia_id, $tipo_ingreso_detalle, $isNotaCredito);
      }
    }
  }


  public function associateGuias()
  {
    if (is_array($this->guias)) {
      foreach ($this->guias as $guia) {
        GuiaVenta::associate($this->venta->VtaOper, $guia);
      }
    }
  }
}
