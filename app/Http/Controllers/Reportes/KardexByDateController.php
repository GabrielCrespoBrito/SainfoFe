<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFGenerator;
use App\Jobs\Reporte\ReporteKardexFecha;
use App\Util\ExcellGenerator\KardexFechaExcell;
use App\Http\Requests\ReporteKardexFechaRequest;

class KardexByDateController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_KARDEXPORFECHA', 'R_REPORTE'))->only('create');
  }
	public function create()
	{

		$empresa   = get_empresa();

		$data = [
			'locales' => $empresa->almacenes
		];

		return view('reportes.kardex_fecha.create', $data);
	}

	public function store( ReporteKardexFechaRequest $request )
	{
    $this->authorize(p_name('A_KARDEXPORFECHA', 'R_REPORTE'));

		$fecha_inicio = $request->fecha_desde;
		$fecha_final = $request->fecha_hasta;
		$local = $request->LocCodi == "todos" ? null : $request->LocCodi;
    
		$reporter = new ReporteKardexFecha( $fecha_inicio,$fecha_final,$local);
		$reporter->handle();
		$data = $reporter->getData();

		ob_end_clean();

    $empresa = get_empresa();
    $nombreEmpresa =  $empresa->EmpLin1 . ' ' . $empresa->EmpNomb;
    $title = "Reporte de kardex por fecha";

    if( $request->tipo_reporte == "excell" ){

      $excellExport = new KardexFechaExcell( $data['items'], $title, $nombreEmpresa, $fecha_inicio, $fecha_final );

      $info = $excellExport
        ->generate()
        ->store();
        
      return response()->download($info['full'], $info['file']);
    }

    else if( $request->tipo_reporte == "pdf" ) {

      $view = "reportes.kardex_fecha.pdf";
      $pdfGenerator = new PDFGenerator(view($view, [
        'title' => $title,
        'items' => $data['items'],
        'nombre' => $nombreEmpresa,
        'fecha_inicio' => $fecha_inicio,
        'fecha_final' => $fecha_final,
        'ruc' => $empresa->ruc(),
        'razon_social' => $empresa->EmpNomb,
        'local' => $local ?? 'TODOS',
        'fecha_reporte' => $fecha_inicio . ' - ' . $fecha_final,
      ]), PDFGenerator::HTMLGENERATOR);

      $pdfGenerator->generator->setGlobalOptions([
        'no-outline',
        'page-size' => 'Letter',
        'orientation' => 'landscape',
      ]);
      $pdfGenerator->generate();

    }
	}

}
