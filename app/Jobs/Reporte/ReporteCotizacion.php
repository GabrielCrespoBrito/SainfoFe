<?php

namespace App\Jobs\Reporte;

use App\User;
use App\Local;
use App\Venta;
use App\Vendedor;
use App\Cotizacion;
use App\ClienteProveedor;

class ReporteCotizacion
{

  const REPORTE_NOMBRE  = "REPORTE DE COTIZACIONES";

  /**
   * Data del reporte
   */
  protected $data = [];
  protected $current_fecha;
  protected $vendedor;
  protected $usucodi;
  protected $estado;
  protected $fecha_desde;
  protected $fecha_hasta;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($vendedor, $usucodi, $estado, $fecha_desde, $fecha_hasta)
  {
    $this->vendedor = $vendedor;
    $this->usucodi = $usucodi;
    $this->estado = $estado;
    $this->fecha_desde = $fecha_desde;
    $this->fecha_hasta = $fecha_hasta;
    $this->handle();
  }

  /**
   * Execute the job.
   *
   */
  public function getQuery()
  {
    $query = Cotizacion::with(['items.producto',  'cliente_with' => function ($query) {
      $query->where('TipCodi', 'C');
    }])
      ->whereBetween('CotFVta', [$this->fecha_desde, $this->fecha_hasta]);

    if ($this->vendedor) {
      $query->where('vencodi', $this->vendedor);
    }

    if ($this->usucodi) {
      $query->where('usucodi', $this->usucodi);
    }

    if ($this->estado) {
      $query->where('cotesta', $this->estado);
    }
    
    return $query->get();
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $query =  $this->getQuery();


    // Si no existen registros, deter el resto del script
    if ($query->count() === 0) {
      return;
    }

    $data = [];
    $this->addToData($data, $this->getInfoReporte());
    $total_reporte = &$data['total'];

    $this->processAll($query, $data, $total_reporte);
    $this->data = $data;
  }

  /**
   * Procesar los dias por iterar
   *
   * @return void
   */
  public function processAll($cotizaciones, &$data, &$total_reporte)
  {
    foreach ($cotizaciones as $cotId => $cotizacion) {

      $info = [
        'id' => $cotizacion->CotNume,
        'fecha' => $cotizacion->CotFVta,
        'estado' => $cotizacion->cotesta == 'L' ? 'Liberado' : 'Pendiente',
        'cliente_ruc' => $cotizacion->cliente_with->PCRucc,
        'cliente_cliente' => $cotizacion->cliente_with->PCNomb,
        'vendedor' => $cotizacion->vendedor->vennomb,
        'total' => $cotizacion->cotimpo,
        'items' => [],
      ];

      foreach ($cotizacion->items as $item) {
        $info['items'][] = [
          'id' => $item->DetCodi,
          'nombre' => $item->DetNomb,
          'cantidad' => $item->DetCant,
          'importe' => $item->DetImpo,
        ];
      }

      $data['items'][] = $info;
    }
  }



  /**
   * Establecer la data del reporte
   * 
   * @return array
   */
  public function addToData(&$arrAdd, $info = [], $addIndexItems = true)
  {
    // Info
    $arrAdd['info'] = $info;

    // Items
    if ($addIndexItems) {
      $arrAdd['items'] = [];
    }

    // Total
    $arrAdd['total'] = [
      'importe' => 0,
      'pago' => 0,
      'saldo' => 0,
    ];

    return $arrAdd;
  }

  public function getInfoReporte()
  {
    $vendedor = $this->vendedor ? Vendedor::find($this->vendedor)->vennomb : 'TODOS';
    $usuario  = $this->usucodi ? User::find($this->usucodi)->usulogi : 'TODOS';
    $estado   = $this->estado ? ['P' => 'Pendiente', 'L' => 'Liberada'][$this->estado] : 'TODOS';
    $empresa  = get_empresa();

    return [
      'reporte_nombre' => self::REPORTE_NOMBRE,
      'empresa_nombre' => $empresa->nombre(),
      'empresa_ruc' => $empresa->ruc(),
      'fecha_generacion' => date('Y-m-d H:i:s'),
      'vendedor' => $vendedor,
      'usuario' => $usuario,
      'estado' => $estado,
      'fecha_desde' => $this->fecha_desde,
      'fecha_hasta' => $this->fecha_hasta,
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
