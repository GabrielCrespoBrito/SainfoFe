<?php

namespace App\Jobs\EstadisticasMensual;

use App\Venta;
use App\Moneda;
use App\Models\Cierre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\ModuloMonitoreo\StatusCode\StatusCode;
use App\TipoDocumento;

class CrearEstadisticas
{
  public $mes;
  public $query;
  public $queryCompra;
  public $data = [];
  public $mescodi;

  const ESTADOS_NOMBRE = [
    StatusCode::CODE_0001 => 'enviados',
    StatusCode::CODE_0002 => 'no_aceptados',
    StatusCode::CODE_0003 => 'enviados',
    StatusCode::CODE_0011 => 'por_enviar',
  ];

  public function __construct($mescodi, $mes = null)
  {
    $this->mes = $mes;
    $this->mescodi = $mescodi;
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
        'ventas_cab.VtaImpo as importe',
        'ventas_cab.VtaFMail as estado',
        'ventas_cab.Moncodi as moneda'
      ])
      ->get()
      ->groupBy(['TidCodi', 'VtaFvta']);
  }

  public function setQueryCompras()
  {
    $this->queryCompra = DB::connection('tenant')
      ->table('compras_cab')
      ->where('MesCodi', $this->mescodi)
      ->select([
        'compras_cab.CpaFCpa as fecha',
        'compras_cab.CpaImpo as importe',
        'compras_cab.moncodi as moneda'
      ])
      ->get()
      ->groupBy('CpaFCpa');
  }

  public function getDataEmpty()
  {
    $docInfo = [
      'total' => 0,
      'enviados' => 0,
      'por_enviar' => 0,
      'no_aceptados' => 0,
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
      'compras' => [],
    ];

    $cant_dias_mes = (int) last(explode('-', mes_to_fecha_inicio_final($this->mescodi)[1]));

    for ($i = 1; $i <= $cant_dias_mes; $i++) {

      $dataArr['ventas'][$i] = [
        'cantidad' => 0,
        '01' => 0,
        '02' => 0,
      ];

      $dataArr['compras'][$i] = [
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
    # Sumar a su tipodedocumento
    $this->data['docs'][$documento->tipodocumento][self::ESTADOS_NOMBRE[$documento->estado]] += 1;


    # Sumatoria al dia
    $dia = (int) last(explode('-', $documento->fecha));
    $soles = $documento->moneda == Moneda::SOL_ID ? $documento->importe : 0;
    $dolares = $documento->moneda == Moneda::DOLAR_ID ? $documento->importe : 0;

    $isNC = $documento->tipodocumento == Venta::NOTA_CREDITO;

    $soles =  convertNegativeIfTrue($soles, $isNC);
    $dolares =  convertNegativeIfTrue($dolares, $isNC);

    $this->data['ventas'][$dia]['cantidad'] += 1;
    $this->data['ventas'][$dia]['01'] =  $this->data['ventas'][$dia]['01'] + $soles;
    $this->data['ventas'][$dia]['02'] = $this->data['ventas'][$dia]['02'] + $dolares;
  }

  public function addToTotalDocumento($documento)
  {
    # Sumatoria al dia
    $dia = (int) last(explode('-', $documento->fecha));
    $soles = $documento->moneda == Moneda::SOL_ID ? $documento->importe : 0;
    $dolares = $documento->moneda == Moneda::DOLAR_ID ? $documento->importe : 0;

    $this->data['compras'][$dia]['cantidad'] += 1;
    $this->data['compras'][$dia]['01'] = $soles;
    $this->data['compras'][$dia]['02'] = $dolares;
  }


  public function processVentas()
  {
    $q = $this->query;

    if (!$q->count()) {
      return;
    }

    // Iterar
    foreach ($q as $tidcodi => $dias) {
      foreach ($dias as $dia) {
        foreach ($dia as $documento) {
          $this->addToTotal($documento);
        }
      }
    }
  }

  public function processCompras()
  {
    $dias = $this->queryCompra;

    if (!$dias->count()) {
      return;
    }
    // Iterar
    foreach ($dias as $dia) {
      foreach ($dia as $documento) {
        $this->addToTotalDocumento($documento);
      }
    }
    #Code here ............
  }

  public function processData()
  {
    $this->getDataEmpty();
    $this->processVentas();
    $this->processCompras();
  }

  public function handle()
  {
    try {
      $this->setQuery();
      $this->setQueryCompras();
      $this->processData();
      $this->saveData();
    } catch (\Throwable $th) {
    }

    return $this->data;
  }

  public function saveData()
  {
    $data = $this->data;

    if ($this->mes) {
      $this->mes->estadistica = json_encode($data);
      $this->mes->save();
      return;
    }

    Cierre::createByMescodi($this->mescodi, false, $data);
  }
}
