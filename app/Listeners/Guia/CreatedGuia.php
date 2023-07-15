<?php

namespace App\Listeners\Guia;

use App\GuiaSalida;
use App\GuiaSalidaItem;
use App\Unidad;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatedGuia
{
  public $guia_id;

  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
    // DetUnid
  }

  /**
   * Handle the event.
   *
   * @param  object  $event
   * @return void
   */
  public function handle($event)
  {
    $this->createGuia($event);
    $this->createDetalles($event);
    $this->updateGuiaCalculos();
  }


  public function createGuia($event)
  {
    $id_almacen = $event->venta->LocCodi;
    $id_movimiento = "S001";
    $setCorrelative = $event->data['tipo'] == 2;
    $this->guia_id = GuiaSalida::createGuia($event->venta->VtaOper, true,  $id_almacen, $id_movimiento, $setCorrelative, $event->data['observacion'] , $event->data['fecha_emision'] );
  }

  public function createDetalles( $event)
  {
    $items = $event->data['items'];
    $data_items = [];
    foreach ($items as $item) {
      $venta_item = $event->venta->items->where('Linea', $item['id'])->first();
      $data_items[] = [
        'UniCodi' => $venta_item->UniCodi,
        'DetNomb' => $venta_item->DetNomb,
        'DetCodi' => $venta_item->DetCodi,
        'DetFact' => $venta_item->Detfact,
        'Marca' => $venta_item->MarNomb,
        'DetPrec' => $venta_item->DetPrec,
        'Detcant' =>   $item['cantidad'],
        'DetUniNomb' => $venta_item->DetUnid
      ];
    }
    GuiaSalidaItem::createItems($this->guia_id, $data_items);
  }

  public function updateGuiaCalculos()
  {
    GuiaSalida::find($this->guia_id)->calculateTotal();
  }

}