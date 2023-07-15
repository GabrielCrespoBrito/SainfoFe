<?php
namespace App\Http\Controllers\Reportes;

use App\Caja;
use App\Moneda;
use App\TipoPago;
use App\VentaPago;
use App\BancoEmpresa;
use App\ClienteProveedor;
use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFGenerator;
use App\Http\Requests\VentaTipoPagoReportRequest;
use App\Util\ExcellGenerator\CajaMovimientoExcell;
use App\Util\ExcellGenerator\CuentaPorPagarExcell;
use App\Util\ExcellGenerator\ReporteTipoPagoExcell;

class CuentaPagoReportController extends Controller

{
  public function __construct()
  {
  }

  public function pdf($caja_id, $tipo = "pdf", $tipo_pago = null)
  {
    ob_end_clean();


    $empresa = get_empresa();
    $pagos = VentaPago::with(['cliente' => function($q){
      $q->where('TipCodi', ClienteProveedor::TIPO_CLIENTE);
    }])->where('CajNume', $caja_id);

    if ($tipo_pago) {
      $pagos->where('TpgCodi', $tipo_pago);
    }

    $pagos = $pagos
      ->orderBy('TpgCodi', 'asc')
      ->orderBy('PagFech', 'asc')
      ->get()
      ->groupBy('TpgCodi');

    if ($pagos->count()) {
      $caja = Caja::findOrfail($caja_id);
      $cuenta = BancoEmpresa::find($caja->CueCodi);
      $banco = $cuenta->banco->bannomb;
      $moneda = Moneda::getAbrev($cuenta->MonCodi);

      $caja_data = (object) [
        'numero' =>  $caja->CajNume,
        'usuario' => $caja->User_Crea,
        'fecha_apertura' =>  $caja->CajFech,
        'fecha_cierre' =>  $caja->CajFecC,
        'cuenta' => $cuenta->numero(),
        'banco' => $banco,
        'moneda' => $moneda
      ];

/*

    // -----------------------------------------------------------------------------------------------    
    $this->sheet->row($this->getLineaAndSum(),  [$this->data['titulo']]);
    $this->sheet->row($this->getLineaAndSum(),  [$this->data['nombre_empresa']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Caja:', $this->data['caja']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Usuario:', $this->data['usuario']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Fecha Apertura:', $this->data['fecha_apertura']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Fecha Cierre:', $this->data['fecha_cierre']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Cuenta:', $this->data['cuenta']]);
    $this->sheet->row($this->getLineaAndSum(),  ['Banco:', $this->data['banco']]);
    $this->sheet->row($this->getLineaAndSum(),  ['moneda:', $this->data['moneda']]);

    FuckThat
*/

      $data = [
        'titulo' => 'REPORTE POR TIPO DE PAGO',
        'nombre_reporte' => 'REPORTE DE CUENTA',
        'pagos_group' => $pagos,
        'fecha_desde' => null,
        'fecha_hasta' => null,
        'caja' => $caja->CajNume,
        'caja_data' => $caja_data,
        'usuario' => $caja->User_Crea,
        'fecha_apertura' =>  $caja->CajFech,
        'fecha_cierre' =>  $caja->CajFecC,
        'cuenta' => $cuenta->numero(),
        'banco' => $banco,
        'moneda' => $moneda,
        'nombre_empresa' => $empresa->EmpNomb,
        'soles' => $caja->CajSalS,
        'dolares' => $caja->CajSalD,
        'ruc' => $empresa->EmpLin1,
        'dolar_abbre' => Moneda::getAbrev(Moneda::DOLAR_ID),
        'soles_abbre' => Moneda::getAbrev(Moneda::SOL_ID)
      ];

      if( $tipo == "pdf" ){
        $view = view('reportes.cuenta_tipopago.pdf', $data);
        $pdf = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
        return $pdf->generate();
      }
      else {

        $excell = new  ReporteTipoPagoExcell( $data );

        $info = $excell
          ->generate()
          ->store();

        return response()->download($info['full'], $info['file']);
      }

    } 
    
    
    else {
      noti()->error('No existen registros');
      return back();
    }

  }
}


