<?php

namespace App\Http\Controllers\Reportes;

use App\TipoDocumentoPago;
use Illuminate\Http\Request;
use App\Jobs\ReporteComprasMensual;
use App\Http\Controllers\Controller;
use App\Mes;
use App\Util\ExcellGenerator\CompraMensualContableExcell;
use App\Util\ExcellGenerator\VentaContableExcell;
use App\Util\PDFGenerator\PDFGenerator;

class ComprasMensualController extends Controller
{ 
  public function __construct()
  {
    $this->middleware(p_midd('A_CONTABLECOMPRASMENSUAL', 'R_REPORTE'))->only(['create', 'pdf']);
  }
  public function create()
  {
    return view('reportes.ventas_mensual.form', ['isVenta' => false]);
  }

  public function getPDF($mes, $data_reporte)
  {
    $empresa = get_empresa();
    $view = view('reportes.compras_mensual.pdf', [
      'nombre_empresa' => $empresa->EmpNomb, 
      'ruc_empresa' => $empresa->ruc(), 
      'periodo' => Mes::find($mes)->mesnomb,
      'data_reporte' => $data_reporte,
      'titulo' => 'Reporte mensual de compras',      
      ]);
    $generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR);
    return $generator->generate();
  }

  /**
   * Obtener el reporte en formato html
   * 
   * @return View
   */

  public function getHtml( $formato, $mescodi, $data_reporte )
  {
    return view('reportes.compras_mensual.form', [
        'mes' => $mescodi,
        'formato' => $formato,
        'data_reporte'  => $data_reporte
    ]);
  }

  /**
   * Reporte en formato excell
   * 
   * @return
   */
  public function getExcell($mescodi, $data_reporte)
  {

    $empresa = get_empresa();
    ob_end_clean();
    $nombreEmpresa =  $empresa->EmpLin1 . ' ' . $empresa->EmpNomb;
    $excellExport = new CompraMensualContableExcell($data_reporte, Mes::find($mescodi)->mesnomb, $nombreEmpresa);
    $info = $excellExport
      ->generate()
      ->store();
    return response()->download($info['full'], $info['file']);
  }

  public function getReport($request)
  {
    $reporte = new ReporteComprasMensual($request->mes);
    $data = $reporte->getData();

    if ($request->formato == "html") {
      return $this->getHtml( $request->formato, $request->mes , $data  );
    }

    if ($request->formato == "pdf") {
      return $this->getPDF( $request->mes, $data );
    }

    if ($request->formato == "excell") {
      return $this->getExcell( $request->mes, $data );
    }    

  }


  public function pdf(Request $request)
  {
    $reporte = new ReporteComprasMensual( $request->mes );
    $data = $reporte->getData();
    
    return $this->getReport($request);
  }

}
