<?php

namespace App\Jobs\EstadisticasMensual;

use App\Models\Cierre;
use App\Jobs\EstadisticasMensual\GuiaStats;

class CreateStats
{
  public $mes;
  public $stats;
  public $statExists;
  public $query;
  public $onlyVenta;
  public $queryCompra;
  public $data = [];
  public $mescodi;
  public $searchSunat;
  public $lastUpdates;

  public function __construct($mescodi, $mes = null, $onlyVenta = false, $searchSunat = null )
  {
    $this->mescodi = $mescodi;
    $this->searchSunat = $searchSunat;
    $this->mes = $mes;
    $this->onlyVenta = $onlyVenta;
    $this->searchLastUpdates();
  }

  public function searchLastUpdates()
  {
    $lastUpdates = [
      'ventas' => null,
      'ventas_sunat' => null,
      'compras' => null,
      'guias' => null,
      'guia_sunat' => null,
    ];

    $statExists = false;
    $stats = [];

    if ($this->mes) {
      if ($this->mes->estadistica) {
        $lastUpdates = $this->mes->estadistica['busqueda'];
        $statExists = true;
        $stats = $this->mes->estadistica;
      }
    }

    $this->lastUpdates = (object) $lastUpdates;
    $this->statExists = $statExists;
    $this->stats = $stats;
  }

  public function handle()
  {
    try {
      if($this->onlyVenta){
        $ventaStats  =   (new VentaStats($this->lastUpdates->ventas, $this->mescodi ))->handle();
        $this->processInfo($ventaStats);
      }
      else {
        $ventaStats  =   (new VentaStats($this->lastUpdates->ventas, $this->mescodi))->handle();
        $guiasStats  =   (new GuiaStats($this->lastUpdates->guias, $this->mescodi))->handle();
        $comprasStats= (new CompraStats($this->lastUpdates->compras, $this->mescodi))->handle();
        $this->processInfo($ventaStats, $guiasStats, $comprasStats);
      }
      $this->saveData();
    } catch (\Throwable $th) {
      _dd("error", $th);
      exit();
    }

    return $this->data;
  }

  public function processInfo($ventasData, $guiasData = [], $comprasData = [])
  {
    $dataFinal = $this->stats;

    // dd( "dataFinal", $ventasData );
    // dd( "dataFinal", $ventasData, $guiasData, $comprasData );
    // exit();

    if ($this->statExists) {

      if ($ventasData['search']) {
        // $dataFinal['docs'] = $ventasData['data']['docs'];
        // $dataFinal['ventas'] = $ventasData['data']['ventas'];
        
        $dataFinal['docs'] = $ventasData['data']['estados'];
        $dataFinal['calculos'] = $ventasData['data']['calculos'];
        // $dataFinal['ventas'] = $ventasData['data']['ventas'];
      }
          
      if ($guiasData['search']) {
        $dataFinal['docs']['09'] = $guiasData['data'];
      }

      if ($comprasData['search']) {
        $dataFinal['compras'] = $comprasData['data'];
      }
    } 
    
    else {
      $dataFinal['docs'] = $ventasData['data']['estados'];
      $dataFinal['calculos'] = $ventasData['data']['calculos'];
      $dataFinal['ventas'] = $ventasData['data']['dias'];
      $dataFinal['docs']['09'] = $guiasData['data'];
      $dataFinal['compras'] = $comprasData['data'];
    }

    $dataFinal['busqueda']['ventas'] = $ventasData['lastSearch'];
    $dataFinal['busqueda']['ventas_sunat'] = null;

    if($this->searchSunat){
      $dataFinal['busqueda']['ventas_sunat'] = $this->searchSunat;
    }

    $dataFinal['busqueda']['guias'] = $guiasData['lastSearch'];
    $dataFinal['busqueda']['compras'] = $comprasData['lastSearch'];

    $this->data = $dataFinal;
  }

  public function saveData()
  {
    if ($this->mes) {
      $this->mes->estadistica = $this->data;
      $this->mes->save();
      return;
    }

    Cierre::createByMescodi($this->mescodi, false, $this->data);
  }
}
