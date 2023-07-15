<?php

namespace App\Jobs\EstadisticasMensual;

use App\Moneda;
use Illuminate\Support\Facades\DB;

class CompraStats
{
  public $lastSearchUpdate;
  public $lastSearch;
  public $currentSearch;
  public $mescodi;
  public $mesCantDias;
  public $query;
  public $data = [];


  public function __construct($lastSearchUpdate = null, $mescodi)
  {
    $this->lastSearchUpdate = $lastSearchUpdate;
    $this->mescodi = $mescodi;
    $this->currentSearch = date('Y:m:d H:i:s');
    $this->mesCantDias = (int) last(explode('-', mes_to_fecha_inicio_final($mescodi)[1]));
  }

  public function needSearch()
  {
    if ($this->lastSearchUpdate == null) {
      return true;
    }

    return DB::connection('tenant')
      ->table('compras_cab')
      ->where('MesCodi', $this->mescodi)
      ->where('User_FModi', '>', $this->lastSearchUpdate)
      ->count();
  }

  public function setQuery()
  {
    $this->query = DB::connection('tenant')
      ->table('compras_cab')
      ->where('MesCodi', $this->mescodi)
      ->select([
        'compras_cab.User_FModi as fecha_modificacion',
        'compras_cab.CpaImpo as importe',
        'compras_cab.CpaFCon as fecha',
        'compras_cab.moncodi as moneda',
      ])
      ->get()
      ->groupBy('CpaFCpa');
  }

  public function setData()
  {
    $dataArr = [];
    for ($i = 1; $i <= $this->mesCantDias; $i++) {
      // 
      $dataArr[$i] = [
        'cantidad' => 0,
        '01' => 0,
        '02' => 0,
      ];
    }
    return $this->data = $dataArr;
  }

  public function addToTotal($compra)
  {
    $dia = (int) last(explode('-', $compra->fecha));

    # Sumatoria al dia
    $soles = $compra->moneda == Moneda::SOL_ID ? $compra->importe : 0;
    $dolares = $compra->moneda == Moneda::DOLAR_ID ? $compra->importe : 0;

    $this->data[$dia]['cantidad'] += 1;
    $this->data[$dia]['01'] =  $this->data[$dia]['01'] + $soles;
    $this->data[$dia]['02'] = $this->data[$dia]['02'] + $dolares;

    $this->lastSearch = $compra->fecha_modificacion > $this->lastSearch ?
      $compra->fecha_modificacion :
      $this->lastSearch;
  }



  public function process()
  {
    $compras_dias = $this->query;

    if (!$compras_dias->count()) {
      return;
    }

    foreach ($compras_dias as $fecha => $compras) {
      foreach ($compras as $compra) {
        $this->addToTotal($compra);
      }
    }
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
      'lastSearch' => $this->lastSearch ?? $this->currentSearch,
    ];
  }
}