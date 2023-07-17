<?php

namespace App\Jobs\EstadisticasMensual;

use App\Venta;
use App\Moneda;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\ModuloMonitoreo\StatusCode\StatusCode;

class VentaStats
{
  public $lastSearchUpdate;
  public $lastSearch = null;
  public $currentSearch = null;
  
  public $mescodi;
  public $mesCantDias;
  public $query;
  public $data = [];

  const ESTADOS_NOMBRE = [
    StatusCode::CODE_0001 => 'enviados',
    StatusCode::CODE_0002 => 'no_aceptados',
    StatusCode::CODE_0003 => 'anuladas',
    StatusCode::CODE_0011 => 'por_enviar',
  ];

  public function __construct($lastSearchUpdate = null, $mescodi)
  {
    $this->lastSearchUpdate = $lastSearchUpdate;
    $this->mescodi = $mescodi;
    $this->mesCantDias = (int) last(explode('-', mes_to_fecha_inicio_final($mescodi)[1]));
    $this->currentSearch = date('Y:m:d H:i:s');
  }

  public function needSearch()
  {
    if( $this->lastSearchUpdate == null ){
      return true;
    }

    return DB::connection('tenant')
    ->table('ventas_cab')
    ->where('MesCodi', $this->mescodi)
    ->where('User_FModi', '>' , $this->lastSearchUpdate )
    ->count();
  }

  public function setQuery()
  {
    $this->query = DB::connection('tenant')
      ->table('ventas_cab')
      ->where('MesCodi', $this->mescodi)
      ->whereIn('TidCodi', [
        Venta::FACTURA,
        Venta::BOLETA,
        Venta::NOTA_CREDITO,
        Venta::NOTA_DEBITO,
        Venta::NOTA_VENTA
      ])
      ->select([
        'ventas_cab.VtaOper as id',
        'ventas_cab.TidCodi as tipodocumento',
        'ventas_cab.VtaFvta as fecha',
        'ventas_cab.User_FModi as fecha_modificacion',      
        'ventas_cab.VtaImpo as importe',
        'ventas_cab.VtaFMail as estado',
        'ventas_cab.Moncodi as moneda'
      ])
      ->get()
      ->groupBy(['TidCodi', 'VtaFvta']);
  }

  public function setData()
  {
    $docInfo = [
      'total' => 0,
      'total_importe' => 0,
      'enviados' => 0,
      'enviados_importe' => 0,
      'por_enviar' => 0,
      'por_enviar_importe' => 0,
      'no_aceptados' => 0,
      'no_aceptados_importe' => 0,
      'anuladas' => 0,
      'anuladas_importe' => 0,
    ];

    $dataArr = [
      'docs' => [
        '01' => $docInfo,
        '03' => $docInfo,
        '07' => $docInfo,
        '08' => $docInfo,
        '09' => $docInfo,
        '52' => $docInfo,
        'total' => 0
      ],
      'ventas' => [],
    ];

    for ($i = 1; $i <= $this->mesCantDias; $i++) {
      $dataArr['ventas'][$i] = [
        'cantidad' => 0,
        '01' => 0,
        '02' => 0,
      ];
    }

    return $this->data = $dataArr;
  }

  public function addToTotal($documento)
  {
    # Sumar una a la cantida de documentos totales
    $this->data['docs']['total'] += 1;
    # Sumar al tipo de documento
    $this->data['docs'][$documento->tipodocumento]['total'] += 1;
    $dia = (int) last(explode('-', $documento->fecha));
    # Sumar a su tipodedocumento
    $this->data['docs'][$documento->tipodocumento][self::ESTADOS_NOMBRE[$documento->estado]] += 1;
    
    # Sumatoria al dia
    $isNC = $documento->tipodocumento == Venta::NOTA_CREDITO;    
    $soles = $documento->moneda == Moneda::SOL_ID ? $documento->importe : 0;
    $dolares = $documento->moneda == Moneda::DOLAR_ID ? $documento->importe : 0;
    $soles =  convertNegativeIfTrue($soles, $isNC);
    $dolares =  convertNegativeIfTrue($dolares, $isNC);

    # Sumar al total general
    $this->data['docs'][$documento->tipodocumento]['total_importe'] += $soles;
    # Sumar a su estado especifico
    $this->data['docs'][$documento->tipodocumento][self::ESTADOS_NOMBRE[$documento->estado] . '_importe' ] += $soles;

    $this->data['ventas'][$dia]['cantidad'] += 1;
    $this->data['ventas'][$dia]['01'] = $this->data['ventas'][$dia]['01'] + $soles;
    $this->data['ventas'][$dia]['02'] = $this->data['ventas'][$dia]['02'] + $dolares;
    $this->lastSearch = $documento->fecha_modificacion > $this->lastSearch ? $documento->fecha_modificacion : $this->lastSearch;
  }

  public function process()
  {
    $q = $this->query;    
    if (!$q->count()) {
      return;
    }
    
    // Iterar
    foreach ($q as $dias) {
      foreach ($dias as $dia) {
        foreach ($dia as $documento) {
          $this->addToTotal($documento);
        }
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

    if( $search = $this->needSearch() ){
      $this->processData();
    }
    return [
      'data' => $this->data,
      'search' => $search,
      'lastSearch' => $this->lastSearch ?? $this->currentSearch
    ];
  }
}
