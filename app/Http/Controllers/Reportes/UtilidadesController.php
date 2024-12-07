<?php

namespace App\Http\Controllers\Reportes;

use App\Grupo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reporte\GananciaRequest;
use App\Jobs\Reporte\ReporteUtilidades;
use App\Util\PDFGenerator\PDFGenerator;
use App\Util\PDFGenerator\PDFHtmlPdf;
use App\Vendedor;

class UtilidadesController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_UTILIDADESVENTAS', 'R_REPORTE'))->only('create');
  }
  /**
   * Obtener La informaciòn del reporte
   * 
   * @return array
   */

  public function getReporte( $fecha_desde, $fecha_hasta, $local, $grupo, $vendedor, $descontarPorcVendedor = false )
  {
    $reporte = new ReporteUtilidades($fecha_desde, $fecha_hasta, $local, $grupo, $vendedor, $descontarPorcVendedor);
    
    $data = $reporte->getData();
    return $data;
  }

  public function generatePDF( $fecha_desde, $fecha_hasta, $local, $grupo, $vendedor, $titulo, $view, $descontarPorcVendedor = false )
  {
    $data = $this->getReporte($fecha_desde, $fecha_hasta, $local, $grupo, $vendedor, $descontarPorcVendedor);

    if( $grupo != 'todos' ){
      $grupo = Grupo::find($grupo)->GruNomb;
    }

    if ($vendedor != 'todos') {
      $vendedor = Vendedor::find($vendedor)->vennomb;
    }

    $pdfGenerator = new PDFGenerator(view($view , compact('data', 'fecha_desde', 'fecha_hasta', 'local', 'grupo', 'vendedor', 'titulo', 'descontarPorcVendedor')),  PDFGenerator::HTMLGENERATOR);
    $pdfGenerator->generator->setGlobalOptions([
      'no-outline',
      'page-size' => 'Letter',
      'orientation' => 'portrait',
    ]);    
    $pdfGenerator->generate();    
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $grupos = Grupo::noDeleted()->get();   
    return view('reportes.ganancias.create', compact('grupos'));
  }

  /**
   * El reporte de utilidades en pdf de las fecha especificadas
   *
   * 
   * @return HtmlPDFGenerator
   */
  public function pdfComplete($fecha_desde , $fecha_hasta, $local, $grupo, $vendedor, $descontarPorcVendedor = false )
  {
    $this->generatePDF($fecha_desde, $fecha_hasta, $local, $grupo, $vendedor, "REPORTES DE UTILIDADES POR FECHA" , 'reportes.ganancias.pdf_complete', $descontarPorcVendedor);
  }

  /**
   * El reporte de utilidades en pdf en un fecha especifica
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function pdfByFecha($fecha, $local, $grupo, $vendedor, $descontarPorcVendedor = false)
  {
    $this->generatePDF($fecha, $fecha, $local, $grupo, $vendedor, "REPORTES DE UTILIDADES DE FECHA {$fecha}", 'reportes.ganancias.pdf_fecha', $descontarPorcVendedor);
  }

  public function show(GananciaRequest $request)
  {
    $this->authorize(p_name('A_UTILIDADESVENTAS', 'R_REPORTE'));

    $data = $this->getReporte($request->fecha_desde, $request->fecha_hasta, $request->local, $request->grupos, $request->vendedor, $descontarPorcVendedor = $request->input('descontar_porc_vendedor', false) );


    return view('reportes.ganancias.partials.info_html', [
      'tableInHtml' => true,
      'data' => $data, 
      'fecha_desde' => $request->fecha_desde, 
      'fecha_hasta' => $request->fecha_hasta, 
      'local' => $request->local,
      'vendedor' => $request->vendedor,
      'grupo' => $request->grupos,
      'descontarPorcVendedor' => $descontarPorcVendedor
      ]);
  }  
}