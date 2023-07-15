<?php

namespace App\Http\Controllers\Reportes;

use App\Mes;
use App\Venta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Util\ExcellGenerator\VentaDetraccionExcell;
use App\Util\PDFGenerator\PDFGenerator;

class DetraccionReportController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_DETRACCION', 'R_REPORTE'))->only(['create', 'pdf']);
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        $formato = 'linea';
        $show_report = false;
        $data_report = null;
        $mes = null;
        return view('reportes.detraccion.create', compact('mes','formato', 'show_report', 'data_report'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request)
    {        
        $this->validate($request,[
            'mes' => 'required',
            'formato' => 'required|in:pdf,excell,html',
        ]);

        $empresa = get_empresa();
        $venta = new Venta();
        $data_report = [];
        $data_report['ventas'] = $venta->reportData()->detraccion($request->mes);
        $data_report['nombre_empresa'] = $empresa->EmpNomb;
        $data_report['ruc_empresa'] = $empresa->EmpLin1;
        $data_report['periodo'] = Mes::find($request->mes)->mesnomb;
        $data_report['isPDF'] = $request->formato == 'pdf';

        switch ($request->formato) {
            case 'html':
                $mes = $request->mes;
                $formato = 'linea';
                $show_report = true;
                return view('reportes.detraccion.create', compact('mes', 'formato', 'show_report','data_report'));                
                break;
            case 'pdf':
                $view = view('reportes.detraccion.pdf', ['data_report' => $data_report ]);
                $generator = new PDFGenerator($view, PDFGenerator::HTMLGENERATOR, ['']);
                $generator->generator->setGlobalOptions(['orientation' => 'portrait']);
                $generator->generate();
                break;
            case 'excell':
                ob_end_clean();
                $nombreEmpresa =  $empresa->EmpLin1 . ' ' . $empresa->EmpNomb;
                $excellExport = new VentaDetraccionExcell($data_report['ventas'], $nombreEmpresa, $data_report['periodo']);
                $info = $excellExport
                ->generate()
                ->store();
                return response()->download($info['full'], $info['file']);
        }

        return $request->all();
    }

}
