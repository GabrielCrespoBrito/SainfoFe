<?php

namespace App\Jobs\VentaItem;

use App\Venta;
use App\VentaItem;

class CreateAnticipoDetalle
{
  protected $venta_anticipo;
  protected $venta_parent_id;
  
  protected $lastLine;

  public function __construct( Venta $venta_anticipo, $venta_parent_id, $lastLine )
  {
    $this->venta_parent_id = $venta_parent_id;
    $this->venta_anticipo = $venta_anticipo;
    $this->lastLine = $lastLine;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
      $anticipo_data =  $this->venta_anticipo->getAnticipoData();
      
      $it = new VentaItem();
      $it->Linea = VentaItem::nextLinea();
      $it->DetItem = agregar_ceros($this->lastLine, 2, 1);;
      $it->VtaOper = $this->venta_parent_id;
      $it->EmpCodi = $this->venta_anticipo->EmpCodi;
      // $it->DetUnid =   $unidad->UniAbre;
      $it->DetUnid = $anticipo_data->unidad_abreviatura;
      $it->UniCodi = $anticipo_data->unidad_codi;
      $it->DetCodi = $anticipo_data->procodi;
      $it->DetNomb = $this->venta_anticipo->getNombreAnticipoDocumento();
      $it->MarNomb = $anticipo_data->marca;
      $it->DetCant = $anticipo_data->cantidad;
      $it->DetPrec = $anticipo_data->precio;  //  $item['DetPrec'];
      $it->DetPeso = $anticipo_data->peso;
      $it->DetEsta = "V";
      $it->DetEspe = 0;
      $it->lote = $anticipo_data->totales;
      $it->DetCSol = $anticipo_data->costo_sol;
      $it->DetCDol = $anticipo_data->costo_dolar;
      $it->DetVSol = $anticipo_data->valor_sol;
      $it->DetVDol = $anticipo_data->valor_dolares;
      $it->GuiOper = null;
      // ----------------------------------------------------------------------------------------------------------------------------------
      $it->DetSdCa = $anticipo_data->cantidad;
      $it->DetDcto = 0;
      $it->DetDctoV = 0;
      $it->Detfact = $anticipo_data->factor;
      $it->DetIGVV = $anticipo_data->igv_unitario;
      $it->DetIGVP = $anticipo_data->igv_total;
      $it->DetISC = 0;
      $it->DetISCP  = 0;
      $it->icbper_value = 0;
      $it->icbper_unit = 0;
      $it->DetImpo = $anticipo_data->importe;
      $it->detfven = '';
      $it->DetDeta = '';
      $it->Estado  = $anticipo_data->estado;
      $it->DetBase = $anticipo_data->base;
      $it->incluye_igv = 1;
      $it->DetPercP = 0;
      $it->TipoIGV = 10;
      $it->save();

  }
}