<?php

namespace App\Jobs;

use App\Marca;
use App\Unidad;
use App\CompraItem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class CreatedCompraItems
{
  use Dispatchable, SerializesModels;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public $compra;
  public $items;

  public function __construct($compra, $items)
  {
    $this->compra = $compra;
    $this->items = $items;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {

    $empresa = get_empresa();

    $noActualizarPorCompra = $empresa->getDataAditional('no_actualizar_costo_por_compra');

    for ($i = 0, $index = 1; $i < count($this->items); $i++, $index++) {
      $data = $this->items[$i];
      
      $unidad = Unidad::find($data['UniCodi']);
      $totales  = (object) $data['totales'];
      
      if( $noActualizarPorCompra == false ){
        if( (int) $data['update_costo'] ){
          $unidad->updateCosto( $totales->costo_soles / $totales->cantidad , $totales->costo_dolares / $totales->cantidad , true );
        }
      }

      $abreviatura = optional($unidad)->UniAbre;
      $marca_nombre = optional(Marca::find($data['producto']['marcodi']))->MarNomb;
      
      $data['DetUnid'] = $abreviatura;
      $data['CpaOper'] = $this->compra->CpaOper;
      $data['MarNomb'] = $marca_nombre;
      $data['DetItem'] = agregar_ceros($index, 2, 0);
      $data['Detfact'] = $totales->factor;
      $data['DetIgvv'] = $totales->igv_unitario;
      $data['DetCSol'] = $totales->costo_soles;
      $data['DetCDol'] = $totales->costo_dolares;
      CompraItem::create($data);
    }
  }
}
