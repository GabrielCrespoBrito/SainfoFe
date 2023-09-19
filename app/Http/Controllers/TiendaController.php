<?php

namespace App\Http\Controllers;

use App\Woocomerce\WoocomerceOrder;
use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;

class TiendaController extends Controller
{
  public function indexTable(Request $request)
  {
    $success = false;
    $error = null;
    $data = null;
    $tienda_url = "";
    $importMode = $request->input('importMode', '1');

    $parameters = [
      'status' => $status =  $request->input('status', 'ywraq-new')
    ];

    $woocomerce = (new WoocomerceOrder())->all($parameters);
    $success = $woocomerce->success;
    $error = $woocomerce->error;
    $data = $woocomerce->data;

    return view('ecommerce.indexTable', compact('status', 'importMode', 'data', 'success', 'error'));
  }





  public function index(Request $request)
  {
    if(!get_empresa()->hasTiendaApiCredentials()){
      noti()->error('Para conectar el sistema a tu tienda online, contactar con soporte' );
      return back();
    }

    $success = false;
    $error = null;
    $data = null;
    $tienda_url = "";

    $parameters = [
      'status' => $status =  $request->input('status', 'ywraq-new')
    ];

    // 
    // rest_invalid_param
    // // <select id="order_status" name="order_status" >
    // <option value="wc-pending">Pendiente de pago</option>
    // <option value="wc-processing">Procesando</option>
    // <option value="wc-on-hold">En espera</option>
    // <option value="wc-completed" selected="selected">Completado</option> completed
    // <option value="wc-cancelled">Cancelado</option>
    // <option value="wc-refunded">Reembolsado</option>
    // <option value="wc-failed">Fallido</option>
    // <option value="wc-checkout-draft">Borrador</option>
    // <option value="wc-ywraq-new">Nueva solicitud de presupuesto</option>
    // <option value="wc-ywraq-pending">Presupuesto pendiente</option>
    // <option value="wc-ywraq-expired">Presupuesto caducado</option>
    // <option value="wc-ywraq-accepted">Presupuesto aceptado</option>
    // <option value="wc-ywraq-rejected">Presupuesto rechazado</option>							
    // </select>

    // dd("hola", $parameters);
    $tienda_url = get_empresa()->getDataAditional('woocomerce_api_url');
    // $woocomerce = (new WoocomerceOrder())->all($parameters);
    // // $woocomerce = (new WoocomerceOrder())->get(24608,$parameters);
    // $success = $woocomerce->success;
    // $error = $woocomerce->error;
    // $data = $woocomerce->data;

    // dd($data['importInfo']);

    // return view('ecommerce.index', compact( 'tienda_url', 'status', 'data', 'success', 'error'));
    return view('ecommerce.index', compact('tienda_url'));
  }

  public function destroy( $id )
  {
    // _dd("hola eliminar", $id);
    // exit();
    $woocomerce = (new WoocomerceOrder())->delete( $id);
    $success = $woocomerce->success;
    $error = $woocomerce->error;
    $data = $woocomerce->data;

    if( $success ){
      noti()->success('Acción Exitosa', 'La Cotización ' . $id . ' ha sido eliminada exitosamente');
    }
    else {
      noti()->error('Error',sprintf('Ha ocurrido un error al eliminar la Cotización %s Error(%s)', $id, $error));
    }

    return redirect()->back();
  }

  // public function showTable(Request $request)
  // {
  //   $woocomerce = (new WoocomerceOrder())->get($id);
  //   // $woocomerce = (new WoocomerceOrder())->get(24608,$parameters);
  //   $success = $woocomerce->success;
  //   $error = $woocomerce->error;
  //   $order = $woocomerce->data;

  //   return view('ecommerce.show', compact('order'));
  // }

}
