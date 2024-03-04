<?php

namespace App\Http\Controllers\Reportes;

use App\Caja;
use App\TipoPago;
use App\VentaPago;
use App\ClienteProveedor;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFGenerator;
use App\Http\Requests\VentaTipoPagoReportRequest;

class VentaTipoPagoReportController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_TIPOPAGO', 'R_REPORTE'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('reportes.ventas_tipopago.show', [
      'tipos_pagos' => TipoPago::all()
    ]);
  }

  public function pdf(VentaTipoPagoReportRequest $request)
  {
    $empresa = get_empresa();
    $pagos = VentaPago::with(['cliente' => function($q){
      $q->where('TipCodi', ClienteProveedor::TIPO_CLIENTE);
    }])
      ->whereBetween('PagFech', [$request->fecha_desde, $request->fecha_hasta]);

    if ($request->cliente_documento) {
      $pagos->where('PCCodi', $request->cliente_documento );
    }

    if ($request->tipo_pago_id) {
      $pagos->where('TpgCodi', $request->tipo_pago_id);
    }

    $pagos = $pagos
      ->orderBy('TpgCodi', 'asc')
      ->get()
      ->groupBy('TpgCodi');

    if ($pagos->count()) {
      $data = [
        'nombre_reporte' => 'REPORTE DE VENTAS POR TIPO DE PAGO',
        'pagos_group' => $pagos,
        'fecha_desde' =>  $request->input('fecha_desde'),
        'fecha_hasta' => $request->input('fecha_hasta'),
        'nombre_empresa' => $empresa->EmpNomb,
        'ruc' => $empresa->EmpLin1,
      ];
      $view = view('reportes.ventas_tipopago.pdf', $data);
      $pdf = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      return $pdf->generate();
    } else {
      return back()->withErrors(['caja' => 'No existen registros']);
    }
  }

  public function pdfByCaja($caja_id, $tipo_pago = null)
  {
    $empresa = get_empresa();
    $caja = Caja::findOrfail($caja_id);

    $clienteCallBack = function($join)
    {
      $join->on('prov_clientes.PCCodi', '=', 'ventas_pago.PCCodi')
        ->where('prov_clientes.TipCodi', '=', 'C');
    };

    $ventasCallBack =  function ($join) use($caja)
    {
      $join->on('ventas_cab.VtaOper', '=', 'ventas_pago.VtaOper')
      ->where('ventas_cab.LocCodi', '=', $caja->LocCodi);
      // ->where('ventas_cab.User_Crea', '=', $caja->User_Crea);
    };

    
    if ($tipo_pago) {

      $tipo_pago_current = TipoPago::findOrfail($tipo_pago);
      
      if($tipo_pago_current->isEfectivo()){

        $pagos = DB::connection('tenant')->table('ventas_pago')
        ->join( 'prov_clientes', $clienteCallBack )
        ->where( 'ventas_pago.TpgCodi', $tipo_pago )
        ->where( 'ventas_pago.CajNume', $caja->CajNume );

        //--
        // $query = DB::connection('tenant')->table('compras_detalle')
        // ->join('compras_cab', function ($join) use ($fecha, $local) {
        //   $join->on('compras_cab.CpaOper', '=', 'compras_detalle.CpaOper');

        //   if ($fecha) {
        //     $join->where('compras_cab.CpaFCon', '<=',  $fecha);
        //   }

        //   if ($local) {
        //     $join->where('compras_cab.LocCodi', '=',  $local);
        //   }
        // });
        //--


      // $pagos = VentaPago::with(['venta', 'cliente' => function($q){
      //   $q->where('TipCodi', ClienteProveedor::TIPO_CLIENTE);}])->where('CajNume', $caja_id)
      //   ->where('TpgCodi', $tipo_pago);
      
      }

      else {

          $pagos = DB::connection('tenant')->table('ventas_pago')
            ->join('prov_clientes', $clienteCallBack)
            ->join('ventas_cab', $ventasCallBack)
            ->where('ventas_pago.PagFech', $caja->CajFech)
            ->where('ventas_pago.TpgCodi', $tipo_pago);

        // $pagos = VentaPago::with(['venta' => function($q) use($caja){
        //   $q->where('LocCodi', $caja->LocCodi )
        //   ->where('User_Crea', $caja->User_Crea);
        // },
        // 'cliente' => function ($q) {
        //   $q->where('TipCodi', ClienteProveedor::TIPO_CLIENTE);
        // }])
        //   ->where('PagFech', $caja->CajFech)
        //   ->where('TpgCodi', $tipo_pago);        
      }      
    }

    else {

      $pagos = DB::connection('tenant')->table('ventas_pago')
        ->join('prov_clientes', $clienteCallBack)
        ->join('ventas_cab', $ventasCallBack)
        ->where('ventas_pago.PagFech', $caja->CajFech);
   }

    $pagos = $pagos
      ->orderBy('ventas_pago.TpgCodi')
      ->orderBy('ventas_pago.PagFech', 'asc')
      ->select([
        'ventas_pago.VtaOper',
        'ventas_pago.TpgCodi',      
        'ventas_pago.PagFech',
        'ventas_pago.PagBoch',
        'prov_clientes.PCNomb',
        'ventas_pago.CtoOper',
        'ventas_pago.Bannomb',
        'ventas_pago.MonCodi',
        'ventas_pago.PagTCam',
        'ventas_pago.PagImpo',
      ])
      ->get()
      ->groupBy('TpgCodi');

      // _dd($pagos);
      // exit();

    // $pagos = $pagos
    //   ->orderBy('TpgCodi', 'asc')
    //   ->orderBy('PagFech', 'asc')
    //   ->get()
    //   ->groupBy('TpgCodi');


    if ($pagos->count()) {

      // $caja = Caja::findOrfail($caja_id);
      $caja_data = (object) [
        'numero' =>  $caja->CajNume,
        'usuario' => $caja->User_Crea,
        'fecha_apertura' =>  $caja->CajFech,
        'fecha_cierre' =>  $caja->CajFecC,
      ];

      $data = [
        'nombre_reporte' => 'REPORTE DE VENTAS POR TIPO DE PAGO',
        'pagos_group' => $pagos,
        'fecha_desde' => null,
        'fecha_hasta' => null,
        'caja_data' => $caja_data,
        'nombre_empresa' => $empresa->EmpNomb,
        'ruc' => $empresa->EmpLin1,
      ];
      $view = view('reportes.ventas_tipopago.pdf', $data);
      $pdf = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
      return $pdf->generate();
    } else {
      noti()->error('No existen registros');
      return back();
    }
  }
}
