<?php

namespace App\Http\Controllers\Admin;

use App\SettingSystem;
use App\TipoCambioMoneda;
use App\TipoCambioPrincipal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TipoCambioStoredRequest;
use App\Http\Requests\TipoCambioUpdatedTodayRequest;
use App\Util\ConsultTipoCambio\ConsultTipoCambioByDateInterface;
use App\Util\ConsultTipoCambio\ConsultTipoCambioByLatestInterface;

class TipoCambioController extends Controller
{
  public $consulter;

  public function __construct(ConsultTipoCambioByDateInterface $consulter)
  {
    $this->consulter = $consulter;
  }

  public function store(TipoCambioStoredRequest $request)
  // public function store(Request $request)
  {
    $fecha = $request->input('fecha');

    $tc = TipoCambioPrincipal::updateOrCreate(['TipFech' => $fecha], [
      'TipFech' => $fecha,
      'TipComp' => $request->compra,
      'TipVent' => $request->venta,
      'TipFechReal' => $fecha,
    ]);

    noti()->success('Tipo de cambio guardado exitosamente');

    return redirect()->route('admin.tipo_cambio.index');
  }

  public function index(Request $request)
  {
    $search = $request->input('search');


    $fecha = $request->input('fecha');
    $compra = '';
    $venta = '';
    $message = 'hola';
    $saveable = true;
    $message = "Este tipo de cambio ya se encuentra guardado, si vuelve a guardar se sobrescribirá";

    $tc = TipoCambioPrincipal::where('TipFech', $fecha)->first();

    if($fecha == date('Y-m-d')){
      
      if($tc){
        $message = "Este tipo de cambio ya se encuentra guardado, si vuelve a guardar se sobrescribirá";
        $saveable = true;
      }
    }
    else {

      if($tc){
        $saveable = false;
      }
    }



    if($search){


      if(  $fecha > date('Y-m-d') ){
        noti()->error("La fecha de tipo de cambio no puede ser mayor a la fecha actual");
        return redirect()->route('admin.tipo_cambio.index');
      }


      $data = $this->consulter->consult($fecha);
      
      if($data['success']){
        
        if($data['data']){
          noti()->success( "Tipo de Cambio del $fecha");
          $compra = $data['data']->precio_compra;
          $venta = $data['data']->precio_venta;

        }
        else {
          noti()->error( 'Error ',  $data['error'] ? $data['error'] : 'No se pudo consultar el tipo de cambio');
        }
      }

      else {
        noti()->error('Error ',  $data['error'] ? $data['error'] : 'No se pudo consultar el tipo de cambio');
      }
    }


    return view('admin.tipo_cambio.index', [
      'search' => $request->input('search'),
      'fecha' => $fecha,
      'compra' => $compra,
      'venta' => $venta,
      'message' => $message,
      'saveable' => $saveable,
    ]);
  }


  public function search(Request $request)
  {
    $term = $request->input('search')['value'];

    $busqueda = DB::table('tcmoneda')
    ->when($term, function($query) use($term){
      $query->where('TipFech', $term);
    })
    ->orderByDesc('TipCodi')
      ->select(
        'tcmoneda.TipCodi as codigo',
        'tcmoneda.TipFech as fecha',
        'tcmoneda.TipVent as venta',
        'tcmoneda.TipComp as compra',
      );

    $dataTable = DataTables::of($busqueda);

    return $dataTable
      // ->addColumn('alm', 'partials.column_alm')
      ->make(true);
  }

  public function updatedToday(TipoCambioUpdatedTodayRequest $request)
  {
    $tc = TipoCambioMoneda::updateOrCreate(['TipFech' => date('Y-m-d')], [
      'TipFech' => date('Y-m-d'),
      'TipComp' => $request->TipComp,
      'TipVent' => $request->TipVent,
    ]);


    return response()->json(['message' => 'Actualización de tipo de cambio realizada'], 200);
  }


  public function currentTC(Request $request)
  {
    $tc = TipoCambioMoneda::lastTC();
    $tcVenta = optional($tc)->venta ?? 0;
    $tcCompra = optional($tc)->compra ?? 0;

    $tcSunat = SettingSystem::getCurrentTCSunat();

    $ultimaTcExtraido = $tcSunat['fecha'];
    $tcSunatVenta = $tcSunat['venta'];
    $tcSunatCompra = $tcSunat['compra'];

    $html = view('tipo_cambio.form_current', compact('ultimaTcExtraido', 'tcVenta', 'tcCompra', 'tcSunatVenta', 'tcSunatCompra'));
    return $html;
  }
}
