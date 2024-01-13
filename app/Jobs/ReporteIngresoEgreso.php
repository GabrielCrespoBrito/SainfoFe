<?php

namespace App\Jobs;

use App\Control;
use Illuminate\Support\Facades\DB;

class ReporteIngresoEgreso
{

  /**
   * Informaciòn del reporte
   * 
   * @param array
   */
  protected $data = [];
  public $motivo;
  public $usuario;
  public $tipo_movimiento;
  public $isIngreso;
  public $fecha_desde;
  public $fecha_hasta;
  public $localId;
  public $request;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($data, bool $isIngreso)
  {
    $this->request = $data;
    $this->isIngreso = $isIngreso;
    $this->fecha_desde = $data['fecha_desde'];
    $this->fecha_hasta = $data['fecha_hasta'];
    $this->motivo =  strtolower($data['motivo']) == 'todos' ? null  : $data['motivo'];
    $this->usuario =  strtolower($data['usuario']) == 'todos' ? null  : $data['usuario'];
    
    if(isset($data['tipo_movimiento']) ){
      $this->tipo_movimiento =  strtolower($data['tipo_movimiento']) == 'todos' ? null  : $data['tipo_movimiento'];
    }
    else {
      $this->tipo_movimiento = Control::OTROS_INGRESOS;
    }

    $this->handle();
  }



  public function getData()
  {
    return $this->data;
  }

  public function getQuery()
  {
    $query = DB::connection('tenant')->table('caja_detalle')
      ->where('ANULADO', $this->isIngreso ? 'I' : 'S')
      ->whereBetween('MocFech', [$this->fecha_desde, $this->fecha_hasta]);

    if ($this->tipo_movimiento) {
      $query->where('CtoCodi', $this->tipo_movimiento);
    }

    if ($this->motivo) {
      $query->where('caja_detalle.EgrIng', $this->motivo);
    }

    if ($this->usuario) {
      $query->where('caja_detalle.UsuCodi', $this->usuario);
    }

    $solesCampo =  $this->isIngreso ? "CANINGS" : "CANEGRS";
    $dolaresCampo =  $this->isIngreso ? "CANINGD" : "CANEGRD";

    return $query
      ->orderBy('caja_detalle.MocFech', 'asc')
      ->select([
        'caja_detalle.Id as id',
        'caja_detalle.MocNomb as nombre',
        "caja_detalle.{$solesCampo} as soles",
        "caja_detalle.{$dolaresCampo} as dolares",
        'caja_detalle.MOTIVO as motivo',
        'caja_detalle.MocFech as fecha',
        'caja_detalle.User_Crea as usuario'
      ])->get();
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $query = $this->getQuery();


    $this->data = [
      'items' => $query,
      'totalSoles' => $query->sum('soles'),
      'totalDolares' => $query->sum('dolares'),
      'items' => $query,

    ];

  }
}
