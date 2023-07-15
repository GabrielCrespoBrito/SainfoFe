<?php

namespace App\Jobs\EstadisticasMensual;

use App\GuiaSalida;
use App\Venta;
use App\Moneda;
use App\Models\Cierre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\TipoDocumento;

class GuiaStats
{
  public $lastSearchUpdate;
  public $lastSearch;
  public $currentSearch;
  public $mescodi;
  public $query;
  public $data = [];

  const ESTADOS_NOMBRE = [
    StatusCode::CODE_0001 => 'enviados',
    StatusCode::CODE_0002 => 'no_aceptados',
    StatusCode::CODE_0003 => 'enviados',
    StatusCode::CODE_0011 => 'por_enviar',
  ];

  public function __construct($lastSearchUpdate = null, $mescodi)
  {
    $this->lastSearchUpdate = $lastSearchUpdate;
    $this->currentSearch = date('Y:m:d H:i:s');
    $this->mescodi = $mescodi;
  }

  public function needSearch()
  {
    if ($this->lastSearchUpdate == null) {
      return true;
    }

    return DB::connection('tenant')
    ->table('guias_cab')
    ->where('MesCodi', $this->mescodi)
    ->where('EntSal', GuiaSalida::SALIDA)
    ->where('GuiEFor', GuiaSalida::CON_FORMATO)
    ->where('User_FModi', '>', $this->lastSearchUpdate)
    ->count();
  }

  public function setQuery()
  {
    // 
    $this->query = DB::connection('tenant')
    ->table('guias_cab')
    ->where('mescodi', $this->mescodi)
    ->where('EntSal', GuiaSalida::SALIDA)
    ->where('GuiEFor', GuiaSalida::CON_FORMATO)
    ->select([
        'guias_cab.VtaOper as id',
        'guias_cab.User_FModi as fecha_modificacion',
        'guias_cab.fe_rpta as estado',
        'guias_cab.GuiUni'
      ])
      ->orderByDesc('User_FModi')
      ->get();
  }

  public function setData()
  {
    return $this->data =
    [
      'total' => 0,
      'enviados' => 0,
      'por_enviar' => 0,
      'no_aceptados' => 0,
    ];
  }

  public function process()
  {
    $guias = $this->query;

    $guias_cant = $guias->count();

    if (! $guias_cant) {
      return;
    }
    $total_guia = $guias_cant;
    $cant_enviadas_guia  = $guias->where('estado', '=', "0")->count();
    $cant_por_enviar_guia = $guias->whereNotIn('estado', ["0", "2"])->count();
    $cant_noaceptadas_guia = 0;

    $this->data['total'] = $total_guia;
    $this->data['enviadas'] = $cant_enviadas_guia;
    $this->data['por_enviar'] = $cant_por_enviar_guia;
    $this->data['no_aceptadas'] = $cant_noaceptadas_guia;
    $this->lastSearch = $guias->first()->fecha_modificacion;
  }

  public function processData()
  {
    $this->setQuery();
    $this->process();
  }
  
  public function handle()
  {
    $this->setData();
    
    if ( $search = $this->needSearch()) {
      $this->processData();
    }
    return [
      'data' => $this->data,
      'search' => $search,
      'lastSearch' => $this->lastSearch ?? $this->currentSearch
    ];
  }
}
