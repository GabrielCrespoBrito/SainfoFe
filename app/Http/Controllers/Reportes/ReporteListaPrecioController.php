<?php

namespace App\Http\Controllers\Reportes;

use App\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Util\ExcellGenerator\ReporteListaPrecioExcell;
use App\Util\PDFGenerator\PDFGenerator;

class ReporteListaPrecioController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_LISTAPRECIO', 'R_REPORTE'))->only(['create', 'pdf']);
  }


  public function create()
  {
    $grupos = Grupo::noDeleted()->get();
    $listas = get_empresa()->listas;
    return view('reportes.listaprecio.show', ['grupos' => $grupos, 'listas' => $listas]);
  }

  public function pdf(Request $request)
  {
    ob_end_clean();
    $empresa = get_empresa();
    $lista = $empresa->listas->where('LisCodi', $request->input('LisCodi'))->first();
    $nombre_local = $lista->local->LocNomb;
    $nombre_lista = $lista->LisNomb;
    $campoStock =  sprintf('productos.prosto%s as stock', substr($lista->local->LocCodi, -1));
    // 
    $productos_group = DB::connection('tenant')->table('productos')
      ->join('unidad', function ($join) {
        $join
          ->on('productos.ID', '=', 'unidad.Id')
          ->on('productos.empcodi', '=', 'unidad.empcodi');
      })
      ->join('marca', function ($join) {
        $join
          ->on('productos.marcodi', '=', 'marca.MarCodi')
          ->on('marca.empcodi', '=', 'unidad.empcodi');
      })
      ->join('grupos', function ($join) {
        $join
          ->on('productos.grucodi', '=', 'grupos.GruCodi')
          ->on('productos.empcodi', '=', 'grupos.empcodi');
      })
      ->where('productos.empcodi', '=', empcodi())
      ->whereIn('productos.GruCodi', $request->input('codi'))
      ->where('unidad.LisCodi', '=', $request->input('LisCodi'))
      // ->where('unidad.Unicodi', 'LIKE', '%01')
      ->select([

        'unidad.Unicodi',
        'unidad.Id',
        'unidad.UniAbre',
        'unidad.UniPUCD',
        'unidad.UniPUCS',
        'unidad.UniMarg',
        'unidad.UNIPUVS',
        'unidad.UniPeso',
        'unidad.UNIPUVD',
        'marca.MarNomb',
        'grupos.GruNomb',
        'productos.ID as id_producto',
        'productos.ProCodi',
        'productos.GruCodi',
        'productos.ProNomb',
         $campoStock,
        ])
        ->orderBy('productos.GruCodi', 'asc')
        ->get()
      ->groupBy('GruCodi');


    $data = [
      'nombre_reporte' => 'Lista de precios',
      'productos_group' => $productos_group,
      'tipo_cambio' => 3.333,
      'lista' => '000',
      'local' => 'llll',
      'costo' => $request->input('costo', 0),
      'stock' => $request->input('stock', 0),
      'nombre_empresa' => $empresa->EmpNomb,
      'ruc' => $empresa->EmpLin1,
      'nombre_local' => $nombre_local,
      'nombre_lista' => $nombre_lista,
    ];


    if($request->input('tipo_reporte', 'pdf') == 'pdf'){
      $pdf = new PDFGenerator(view('reportes.listaprecio.pdf', $data), PDFGenerator::HTMLGENERATOR);
      return $pdf->generate();
    }
    else {

      $excellGenerator = new ReporteListaPrecioExcell($data, 'lista_precio_reporte');
      $info = $excellGenerator
      ->generate()
      ->store();
      
      return response()->download($info['full'], $info['file']);
    }


    
  }
}
