<?php

namespace App\Http\Controllers\Reportes;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFGenerator;
use App\Jobs\Producto\ProductoMasVendidoReport;
use App\Grupo;

class ProductoController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_PRODUCTOMASVENDIDO', 'R_REPORTE'))->only('create','showPDF');
  }

  public function create()
  {
    return view('reportes.productos_mas_vendidos.form', ['grupos' => Grupo::all()]);
  }

  public function showPDF(Request $request)
  {
    $fecha_desde = $request->fecha_desde;
    $fecha_hasta = $request->fecha_hasta;
    $local = $request->local;

    // , $fecha_desde, $fecha_hasta, $local = "todos
    $validator = validator([
      'fecha_desde' => $fecha_desde,
      'fecha_hasta' => $fecha_hasta,
      'local' => $local,
    ], [
      'fecha_desde' => 'required|date',
      'fecha_hasta' => 'required|date|after_or_equal:fecha_hasta',
      'local' => 'required',
    ]);


    if( $validator->fails() ){
      return back()->withErrors($validator->errors());
    }

    else {
      $fecha_desde_carbon = new Carbon($fecha_desde);
      $fecha_hasta_carbon = new Carbon($fecha_hasta);
      //  ---------
      $dias = config('app.reporte_mas_vendido_dias_limite');
      if($fecha_desde_carbon->addDays($dias)->isBefore($fecha_hasta_carbon)){
        return back()->withErrors( ['fecha_hasta' => "El lapso del reporte no puede superar los {$dias} dias"]);
      }
    }

    $local_report = $local == 'todos' ? null : $local;

    $data_products = (new ProductoMasVendidoReport($fecha_desde, $fecha_hasta, $local_report))->getData();

    $empresa = get_empresa();

    $data = [
      'productos' => $data_products,
      'empresa_nombre' => $empresa->nombre(),
      'empresa_ruc' => $empresa->ruc(),
      'fecha_desde' => $fecha_desde,
      'fecha_hasta' => $fecha_hasta,
      'local' => $local
    ];

    //
    $pdfGenerator = new PDFGenerator(view('reportes.productos_mas_vendidos.pdf',$data), PDFGenerator::HTMLGENERATOR);
    $pdfGenerator->generator->setGlobalOptions([
      'no-outline',
      'page-size' => 'Letter',
      'orientation' => 'portrait',
    ]);
    return $pdfGenerator->generate();  
  }
}
