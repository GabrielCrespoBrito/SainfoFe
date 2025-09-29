<?php

namespace App\Http\Controllers\Admin;

use App\Venta;
use Exception;
use App\Empresa;
use App\Resumen;
use App\GuiaSalida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Jobs\Admin\ActiveEmpresaTenant;
use App\Jobs\Admin\UpdateAllPendientes;
use App\Jobs\Admin\UpdateEmpresasGuiasPendientes;
use App\Jobs\Admin\UpdateEmpresasVentasPendientes;
use App\Jobs\Admin\UpdateEmpresasResumenPendientes;

class ActionController extends Controller
{

  /**
   * Actualizar la información de las empresas con documentos pendientes
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function updateAccionesPendientes(Request $request)
  {
    ini_set('memory_limit', -1);
    ini_set('max_execute_time', 240);

    (new UpdateAllPendientes(true, true))->handle();
    return response()->json(['success' => true]);
  }

    /**
     * Actualizar la información de las empresas con documentos pendientes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateDocumentsPendientes(Request $request)
    {
      ini_set('memory_limit', -1);
      ini_set('max_execute_time', 240);


      (new UpdateEmpresasVentasPendientes(true,true))->handle();
      return response()->json(['success' => true]);
    }

  /**
   * Actualizar la información de las empresas con documentos pendientes
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function updateGuiasPendientes(Request $request)
  {
    ini_set('memory_limit', -1);
    ini_set('max_execute_time', 240);
    
    (new UpdateEmpresasGuiasPendientes(true,true))->handle();
    return response()->json(['success' => true]);
  }


  /**
   * Actualizar la información de las empresas con documentos pendientes
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function updateResumenesPendientes(Request $request)
  {
    ini_set('memory_limit', -1);
    (new UpdateEmpresasResumenPendientes(true, true))->handle();
    return response()->json(['success' => true]);
  }
  
  /**
   * Actualizar la información de las empresas con documentos pendientes
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function showOpciones(Request $request)
  {
    (new ActiveEmpresaTenant(Empresa::find($request->empresa_id)))->handle();

    switch ($request->type) {
      case 'documentos':
        $model = Venta::find($request->id);
        return view('admin.partials.opciones.ventas', ['model' => $model]);
        break;
      case 'resumenes':
        $model = Resumen::findMultiple($request->id, $request->docnume);
        return view('admin.partials.opciones.resumenes', ['model' => $model]);
        break;
      case 'guias':
        $model = GuiaSalida::find($request->id);
        return view('admin.partials.opciones.guias', ['model' => $model]);
        break;
      case 'ordenes_pago':
        return "<h1> .... </h1>";
        break;                      
      default:
        throw new Exception("Error Processing Request", 1);
    }
  }


  /**
   * Actualizar la empresa con la que se esta trabajando
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function changeEmpresa(Request $request)
  {
    session()->put('empresa_id' , $request->empresa_id );
    return response()->json([ 'success' => true ]);
  }
}