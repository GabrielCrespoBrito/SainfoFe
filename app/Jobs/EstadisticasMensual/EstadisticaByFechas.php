<?php

namespace App\Jobs\EstadisticasMensual;

use App\TipoDocumentoPago;
use Illuminate\Support\Facades\DB;

class EstadisticaByFechas 
{
  public $fecha_desde;
  public $fecha_hasta;
  public $calculator;


    public function __construct($fecha_desde, $fecha_hasta)
    {
      $this->fecha_desde = $fecha_desde;
      $this->fecha_hasta = $fecha_hasta;
      $this->calculator = new CalculatorEstadistica();
    }

    public function query()
    {
      return DB::connection('tenant')
      ->table('ventas_cab')
      -> whereBetween('VtaFvta', [$this->fecha_desde, $this->fecha_hasta])
      ->whereIn('TidCodi', [ TipoDocumentoPago::FACTURA, TipoDocumentoPago::BOLETA, TipoDocumentoPago::NOTA_CREDITO, TipoDocumentoPago::NOTA_DEBITO ])
      ->select([
        'ventas_cab.VtaOper as id',
        'ventas_cab.TidCodi as tipodocumento',
        'ventas_cab.VtaFvta as fecha',
        'ventas_cab.User_FModi as fecha_modificacion',
        'ventas_cab.VtaImpo as importe',
        'ventas_cab.Vtabase as base',
        'ventas_cab.VtaIGVV as igv',
        'ventas_cab.VtaISC as isc',
      'ventas_cab.VtaFMail as estado',
      'ventas_cab.VtaTcam as tc',
        'ventas_cab.VtaDcto as dcto',
      'ventas_cab.VtaInaf as inafecta',
      'ventas_cab.VtaExon as exonerada',
      'ventas_cab.VtaGrat as gratuita',
      'ventas_cab.icbper as icbper',
        'ventas_cab.Moncodi as moneda'
      ])
      ->get();
    }


    public function handle()
    {
      $docs = $this->query();

      foreach( $docs as $doc ){
        $this->calculator->setDoc($doc);
      }

              // 'calculos' => $this->calculator->stats_calculo,
        // 'estados' => $this->calculator->stats_estados,
      return [
        'docs' =>$this->calculator->stats_estados,
        'calculos' =>$this->calculator->stats_calculo,
      ];
      

    }

}
