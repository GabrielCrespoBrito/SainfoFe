<?php

namespace App\Jobs\Guia;

use stdClass;
use App\Venta;
use Exception;
use App\Unidad;
use App\Producto;
use App\VentaItem;
use App\UnidadFake;
use App\PDFPlantilla;
use App\SerieDocumento;
use App\TipoCambioPrincipal;
use Illuminate\Support\Facades\DB;
use App\Jobs\Producto\GetLastCostos;
use App\Models\Venta\Traits\Calculator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Venta\Traits\CalculatorTotal;

class GenerateVenta implements ShouldQueue
{
  use Dispatchable;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public $guia;
  public $request;
  public $venta;
  public $tipo_cambio;
  public $items_totales;

  public function __construct($guia, $request)
  {
    $this->request = optional($request);
    $this->guia = $guia;
  }
  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    DB::connection('tenant')->beginTransaction();

    try {
      $this->processItems();
      $this->createVenta();
      $this->createItems();
      $venta = $this->getVenta();      
      SerieDocumento::updateSeries(
        $venta->EmpCodi,
        $venta->TidCodi,
        $venta->VtaSeri,
        $venta->VtaNumee
      );
      $success = true;
      DB::connection('tenant')->commit();
    } catch (Exception $e) {
      $success = false;
      DB::connection('tenant')->rollBack();
      throw new Exception( $e->getMessage() , 1);
    }

    if ($success) {
      $this->updateInfoDocs();
      $venta->saveXML();
      $venta->fresh()->generatePDF(PDFPlantilla::FORMATO_A4, true, false, false);
      get_empresa()->sumarConsumo('comprobantes');
    }
  }
  
  public function createVenta()
  {
    $request = $this->request;
    $guia = $this->guia;
    $request->tipo_documento    = $request->gen_tdoc;
    $request->serie_documento   = $request->gen_serie;
    $request->fecha_emision     = $request->gen_fecha;
    $request->fecha_vencimiento = date('Y-m-d');
    $request->cliente_documento = $guia->PCCodi;
    $request->forma_pago = "01";
    $request->moneda = $guia->moncodi;
    $request->vendedor = $guia->vencodi;
    $request->VtaTcam = $this->tipo_cambio;
    $calculator = new CalculatorTotal($this->items_totales);
    $calculator->setParameters(0,0,0,0);
    $totales = $calculator->getTotal();
    $venta = Venta::createFactura($request,true, $totales );
    $this->setVenta($venta);
  }




  public function updateInfoDocs()
  {
    $venta = $this->getVenta();    
    $guia = $this->guia;    
    
    $venta->DocRefe = $guia->GuiNume; 
    $venta->GuiOper = $guia->GuiOper;
    $venta->save();

    $this->updateGuia();
  }

  public function processItems()
  {
    // $vtaoper = $this->getVenta()->VtaOper;
    $items = $this->guia->items->toArray();
    $isSol = $this->guia->isSol();
    $this->tipo_cambio = TipoCambioPrincipal::ultimo_cambio(false);
    $unidadClassFake = new UnidadFake();
    $calculator = new Calculator();
    $totales_items = [];

    foreach ($items as &$item) {

      $producto = Producto::where('ProCodi', $item['DetCodi'])->first();

      $incluye_igv = $producto->incluye_igv;
      $base_imponible = $producto->BaseIGV;
      $descuento = $item['DetDct1'] + $item['DetDct2'];
      $is_bolsa = $producto->isBolsa();
      $isc = $producto->ISC;
      $unidadFactor = $item['DetFact'];      
      $unidadClassFake->setData($unidadFactor, $item['DetUnid'], $item['DetPeso']);

      $calculator->setValues(
        $item['DetPrec'],
        $item['Detcant'],
        (bool) $incluye_igv,
        $base_imponible,
        $descuento,
        $is_bolsa,
        $isc,
        $unidadFactor,
        $this->tipo_cambio,
        $isSol
      );

      $calculator->calculate();
      $data = $calculator->getCalculos();
      $data['producto'] = $producto;
      $data['unidad'] = $unidadClassFake;

      // $unidadClassFake->getCostos($producto->ProCodi, $this->fecha_emision, $this->local, $cantidad, $factor);
      // getCostos
      // $unidad = $producto

      $unidad = Unidad::find( $item['UniCodi'] );
      // public function getCostos($procodi, $fecha, $local, $cantidad, $factor_venta, $incluye_igv = true)


      $data['costos'] =  
      $unidad->getCostos(
        $producto->ProCodi,
        $this->request->fecha_emision,
        auth()->user()->localCurrent()->loccodi,
        $item['Detcant'],
        1,
        $producto->incluyeIgv()
      );

      $totales_items[] = $data;

      $item['DetCome'] = $item['DetDeta'];
      $item['Marca'] = $item['MarNomb'];
      $item['DetCant'] = $item['Detcant'];
      $item['DetDcto'] = $descuento ;
      $item['incluye_igv'] = $incluye_igv;
      $item['DetIGVP'] = get_option('Logigv');
    }

    $this->items = $items;
    $this->items_totales = $totales_items; 
  }


  public function createItems()
  {
    VentaItem::createItem( 
      $this->getVenta()->VtaOper,
      $this->items,
      true,
      $this->items_totales
    );

  }

	public function updateGuia()
	{
		$this->guia->saveVentaDocRel($this->getVenta());
	}

  /**
   * Get the value of venta
   */
  public function getVenta()
  {
    return $this->venta;
  }

  /**
   * Set the value of venta
   *
   * @return  self
   */
  public function setVenta($venta)
  {
    $this->venta = $venta;

    return $this;
  }
}
