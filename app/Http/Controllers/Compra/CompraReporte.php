<?php

namespace App\Http\Controllers\Compra;

use App\ClienteProveedor;
use App\Util\PDFGenerator\PDFGenerator;
use App\Http\Requests\CompraReportePdfRequest;
use App\Util\ExcellGenerator\CompraReporteExcell;

trait CompraReporte
{
  public function showReporte()
  {
    $empresa = get_empresa();
    $proveedores = $empresa->proveedores;
    return view('compras.reporte' , compact('proveedores') );
  }
  
  public function pdfReporte( CompraReportePdfRequest $request )
  {
    $empresa = get_empresa();

    $busqueda = \DB::connection('tenant')->table('compras_cab')
    ->join('prov_clientes', function ($join) {
      $join
        ->on('prov_clientes.PCCodi', '=', 'compras_cab.PCCodi')
        ->on('prov_clientes.EmpCodi', '=', 'compras_cab.EmpCodi')
        ->where('prov_clientes.TipCodi', '=', 'P');
    })
    ->join('condicion', function ($join) {
      $join
        ->on('condicion.conCodi', '=', 'compras_cab.concodi')
        ->on('condicion.empcodi', '=', 'compras_cab.EmpCodi');
    })
    ->join('vendedores', function ($join) {
      $join
        ->on('vendedores.Vencodi', '=', 'compras_cab.vencodi')
        ->on('vendedores.empcodi', '=', 'compras_cab.EmpCodi');
    })
    ->join('moneda', 'moneda.moncodi', '=', 'compras_cab.MonCodi')
    ->where('compras_cab.EmpCodi', '=', empcodi())
    ->whereBetween('compras_cab.CpaFCpa', [ $request->fecha_desde, $request->fecha_hasta ] )
    ->select(
      'compras_cab.CpaOper',
      'compras_cab.CpaNume',
      'prov_clientes.PCNomb',
      'prov_clientes.PCRucc',
      'moneda.monnomb',
      'condicion.connomb',
      'compras_cab.TidCodi',
      'compras_cab.CpaFCpa',
      'compras_cab.User_Crea',
      'compras_cab.CpaImpo',
      'compras_cab.MonCodi',
      'compras_cab.CpaPago',
      'compras_cab.CpaSald',
      'compras_cab.CpaSdCa',
      'compras_cab.CpaTcam',
      'compras_cab.Cpabase',      
      'compras_cab.CpaIGVV',
      'compras_cab.Cpatota',      
      'compras_cab.AlmEsta');

    $proveedor = $request->input('proveedor');
    if( $proveedor != "todos" ){
      $proveedor = ClienteProveedor::findProveedor($proveedor)->PCNomb;
      $busqueda->where('compras_cab.PCCodi' , $request->input('proveedor') );
    }


    $tipodocumento = $request->input('tipodocumento');

    if ( $tipodocumento != "todos") {
      $busqueda->where('compras_cab.TidCodi', $tipodocumento );
    }      

    $busqueda = $busqueda->get()->groupBy('TidCodi');
    $tiposdocumentosNombre = ['01' => 'FACTURA', '03' => 'BOLETA', '07' => 'NOTA CREDITO', '08' => 'NOTA DEBITO', '40' => 'COMPRA LIBRE'];
    $tipodocumento = $tipodocumento == "todos" ? 'TODOS' : $tiposdocumentosNombre[$tipodocumento];

    $data = [
      'nombre_reporte' => strtoupper('Reporte de compras'),
      'nombre_empresa' => $empresa->EmpNomb,
      'ruc' => $empresa->EmpLin1,
      'fecha_desde' => $request->fecha_desde,
      'fecha_hasta' => $request->fecha_hasta,
      'proveedor' => strtoupper($proveedor),
      'tipodocumento' => strtoupper($tipodocumento),
      'items_group' => $busqueda,
      'withProducts' => $request->input('products',false),
    ];

    // _dd( $request->all() );
    // exit();


    if( $request->tiporeporte == "pdf" ){
      $pdf = new PDFGenerator(view('compras.pdf', $data), PDFGenerator::HTMLGENERATOR);
      $pdf->generate();
    }

    else {

      ob_end_clean();
      $excellExport = new CompraReporteExcell($data, 'KardexFisico');
      $info = $excellExport
        ->generate()
        ->store();
      return response()->download($info['full'],  $info['file']);
    }


  }

}