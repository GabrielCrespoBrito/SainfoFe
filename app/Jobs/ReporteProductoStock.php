<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;

class ReporteProductoStock 
{

  /**
   * Informaciòn del reporte
   * 
   * @param array
   */
  protected $data = [];
  public $request;
  public $grupoId;
  public $familiaId;
  public $marcaId;
  public $localId;
  public $stockMinimo;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($data)
  {
    $this->request = $data;
    $this->grupoId = $data['GruCodi'] == 'todos' ? null   : $data['GruCodi'] ?? null;
    $this->familiaId = $data['FamCodi'] == 'todos' ? null : $data['FamCodi'] ?? null;
    $this->marcaId = $data['MarCodi'] == 'todos' ? null : $data['MarCodi'] ?? null;
    $this->localId = $data['LocCodi'] == 'todos' ? null   : $data['LocCodi'];
    $this->stockMinimo = $data['con_stock_minimo'] ?? false;

    $this->handle();
  }



  public function getData()
  {
    return $this->data;
  }

  public function getQuery()
  {
    $query = DB::connection('tenant')->table('productos')
    ->where('UDelete' , '=' , "0");

    if( $this->grupoId ) {
      $query
      ->where('productos.grucodi', $this->grupoId )
      ->where('productos.famcodi', $this->familiaId);
    }

    if ($this->marcaId) {
      $query->where('productos.marcodi', $this->marcaId);
    }
    if ($this->stockMinimo) {

      $query->where('productos.ProSTem', '1');            
      if ($this->localId) {
        $localCampo = 'productos.prosto' . (int) $this->localId;
        $query->where($localCampo, '<=', 'productos.Promini');
      }
    }  

    $selectColumns = [
      'productos.ID as id',
      'productos.ProCodi as codigo',
      'productos.ProNomb as nombre',
      'productos.unpcodi as unidad',
      'productos.marcodi as marca',
      'productos.Promini as stock_minimo'
    ];

    if( $this->localId ){
      $selectColumns[] = "$localCampo as stock_total";
    }
    else {
      $selectColumns[] = DB::raw("(productos.prosto1+productos.prosto2+productos.prosto3+productos.prosto4+productos.prosto5+productos.prosto6+productos.prosto7+productos.prosto8+productos.prosto9) as stock_total");
    }

    return $query
    ->orderBy( 'productos.ProCodi', 'asc')
    ->orderBy('productos.marcodi', 'asc')
    ->orderBy('productos.ProCodi', 'desc')
      ->select(
        $selectColumns
      )->get();
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $query = $this->getQuery();

    if($this->localId   ){
      $this->data = $query;
      return;
    }

    $this->data = ! $this->stockMinimo ? $query : $query->filter(function($producto){
      return $producto->stock_total <= $producto->stock_minimo;
    });
  }
}
