<?php

namespace App\Http\Controllers;

use App\Venta;
use App\Resumen;
use App\ResumenDetalle;
use Illuminate\Http\Request;
use App\Http\Requests\AgregarBoletaRequest;
use App\Http\Requests\Resumen\ResumenUpdateRequest;
use App\Http\Requests\Resumen\ResumenSaveBoletasRequest;

class BoletasController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_RESUMENES', 'A_VENTA'))->only(['resumen_dia',  'guardar_boletas', 'resource', 'validar_resumen']);
  }

  public function resumen_dia(Request $request)
  {
    $fecha = $request->input('fecha', date('Ym'));
    $tipo_resumen = $request->input('tipo_resumen', 'R');
    $localDefault = $request->input('local',  user_()->LocalCurrent()->loccodi);

    $boletas = Resumen::OrderByDesc('DocNume')
      ->where('EmpCodi', empcodi())
      ->where('MesCodi', $fecha)
      ->where('DocMotivo', $tipo_resumen)
      ->where('LocCodi', $localDefault)
      ->get();

    return view('ventas.resumen_dia', compact('fecha', 'tipo_resumen', 'localDefault', 'boletas'));
  }

  public function generar_resumen($mescodi)
  {
    $result = \DB::connection('tenant')->table('ventas_cab')
      ->join('ventas_ra_detalle', function ($join) {
        $join
          ->on('ventas_ra_detalle.detseri', '=', 'ventas_cab.VtaSeri')
          ->on('ventas_ra_detalle.detNume', '=', 'ventas_cab.VtaNumee');
        // ->where('ventas_ra_detalle.EmpCodi', '=', 'ventas_cab.EmpCodi');
      })
      ->get();

    if (is_null($result)) {
      notificacion("Busqueda realizada", "No se encontrarón boletas por enviar para el periodo {$mescodi}", "error");
      return redirect()->back();
    } else {
      return redirect()->back();
    }
  }


  public function guardar_boletas(ResumenSaveBoletasRequest $request)
  {
    if (is_null($request->id_resumen)) {
      $resumen =
        Resumen::createResumen($request->all(), $request->fecha_busqueda, false,  false, $request->serie);
      ResumenDetalle::createDetalle($resumen, $request->ids);
    } else {
      $resumen = Resumen::findMultiple($request->id_resumen, $request->docnume);
      $resumen->DocFechaE = $request->fecha_generacion;
      $resumen->DocFechaD = $request->fecha_documento;
      $resumen->save();
      ResumenDetalle::createDetalle($resumen, $request->ids, true);
    }

    $resumen->updateRango();

    return "1";
  }



  public function agregar_boleta($id = false, $docnume = false, $empcodi = null)
  {
    $resumen = $id ? Resumen::findMultiple($id, $docnume) : $id;
    return view('ventas.procesar_boletas', [
      'resumen' => $resumen,
      'id_resumen' => $resumen ? $resumen->NumOper : '',
      'collapse' => true,
      'series' => auth()->user()->boletasSeriesByEmpresa()
    ]);
  }

  public function agregar_boletas(AgregarBoletaRequest  $request)
  {
    return Venta::getBoletasNoEnviadas(
      $request->fecha,
      $request->id_resumen,
      $request->docnume,
      $request->serie
    );
  }

  public function update(ResumenUpdateRequest $request, $numoper, $docnume)
  {
    $resumen = Resumen::findMultiple($numoper, $docnume);
    $resumen->updateResumen($request);

    return "1";
  }


  public function anular_boleta(Request $request)
  {
    $venta = Venta::findOrfail($request->id_factura);
    $this->authorize('anular-boleta', $venta);
    $venta->anular_boleta();
    $venta->ejecutar_egreso();
  }


  public function resource($id_resumen, $docnume, $tipo = "pdf")
  {
    $resumen = Resumen::findMultiple($id_resumen, $docnume);

    if ($tipo == "pdf") {
      $dompdf = new \Dompdf\Dompdf();
      $namePdf = "abc" . '.pdf';
      $data = $resumen->dataPdf();
      // dd($data);
      $view = $resumen->isAnulacion() ? 'ventas.pdf_anulado' : 'ventas.pdf_resumen';
      $dompdf->loadHtml(view($view, $data));
      $dompdf->render();
      return $dompdf->stream($namePdf, array("Attachment" => false));
    } else {
      $nameCdr = $resumen->nameFile(true, '.zip');
      $fileHelper = FileHelper();
      if ($fileHelper->cdrExist($nameCdr)) {
        $content = $fileHelper->getCdr($nameCdr);
        $path = $fileHelper->saveTemp($content, $nameCdr);
        return response()->download($path);
      }
      return "no existe el archivo";
    }
  }


  public function validarResumen(Request $request, $numoper, $docnume)
  {
    $resumen = Resumen::findMultiple($numoper, $docnume);
    $resumen->saveSuccessValidacionByEstado(0, '');
    return response()->json(['message' => 'Resumen validado exitosamente']);
  }
}
