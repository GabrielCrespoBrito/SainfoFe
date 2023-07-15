<?php

namespace App\ReportData;

use App\Mes;
use App\Venta;
use App\TipoDocumento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VentaAnualReportData
{
  protected $data;
  protected $year;
  protected $currentMes;

  public function __construct($year, $currentMes )
  {
    $this->year = $year;
    $this->currentMes = (int) $currentMes;
    $this->processData();
  }


  public function getTotalByDocumento($mescodi, $searchNC = false )
  {
    $query = DB::connection('tenant')->table('ventas_cab')
      ->where('ventas_cab.EmpCodi', empcodi())
      ->where('ventas_cab.MesCodi', $mescodi );;

    $searchNC ?
      $query->where('ventas_cab.TidCodi',  Venta::NOTA_CREDITO ) :
      $query->whereIn('ventas_cab.TidCodi',  [Venta::FACTURA, Venta::BOLETA,Venta::NOTA_DEBITO ] );

    return $query->sum('ventas_cab.VtaImpo');
  }



  public function getMesTotal($mescodi)
  {
    return $this->getTotalByDocumento($mescodi) - $this->getTotalByDocumento($mescodi, true);
  }


  public function getMesesArray()
  {
    $mesesData = [];
    
    $beforeMes = 12 -  (12 - $this->currentMes); 

    for ( $i = 1; $i <= $beforeMes ; $i++ ){ 
      $keyMes = $this->year . math()->addCero($i,2);
      $mesesData[ $keyMes] = [];
    }
    return $mesesData;
  }

  public function processData()
  {
    $data = ['total' => 0];
    $mesesArr = $this->getMesesArray();

    foreach( $mesesArr as $mescodi =>  &$value ){
      
      $totalMes = $this->getMesTotal($mescodi); 

      $value = [
        'total' => $totalMes,
        'nombre' => Mes::getNombre($mescodi),
        'codigo' => $mescodi
      ];

      $data['total'] += $totalMes;
    }

    $data['meses'] = $mesesArr;
    $data['year'] = $this->year;

    $this->setData($data);
  }

  public function setData($data)
  {
    $this->data = $data;

    return $this;
  }

  public function getData()
  {
    return $this->data;
  }
}
