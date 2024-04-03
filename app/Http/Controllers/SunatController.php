<?php

namespace App\Http\Controllers;
use App\Sunat;
use App\Venta;
use App\Resumen;
use App\GuiaSalida;
use App\ResumenDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\EnviarTicketRequest;
use App\Http\Requests\EnviarResumenRequest;
use App\Http\Requests\VentaPendienteRequest;
use App\Http\Requests\VerificationDocumentoAnulationRequest;

class SunatController extends Controller
{
  public function __construct()
  {
    // $this->middleware(['permission:send ventas-pendientes'])->only('verificar_pendientes');
  }

  public $sunat;
  
  public function send_sunat( Request $request )
  {
    return $this->sendSunatVenta($request->id_venta, true); 
  }
  
  public function sendSunatVenta($id_venta, $check_status = false)
  {
    $venta = Venta::find($id_venta);
    $sent = $venta->sendSunatPendiente($check_status);
    $message = isset($sent['message']) ? $sent['message'] : '-';
    $code_http = isset($sent['code_http']) ? $sent['code_http'] : '400';
    return response(['data' => $message], $code_http);    
  }

  public function verificar_pendientes( VentaPendienteRequest $request )
  {
    $venta = Venta::find( $request->id_factura );
    # New

    $sent = $venta->sendSunatPendiente(true);
    $message = $sent['message'] ?? $sent['data'] ?? '-';
    $code_http = $sent['code_http'] ?? '400';
    return response()->json([ 'data' => $message ],$code_http);
  }

	public function enviar_resumen( EnviarResumenRequest $request ) 
	{
    $numOper = $request->input('id_resumen');
    $docNume = $request->input('docnume');

    $resumen = Resumen::findMultiple( $numOper, $docNume );
    $response = $resumen->enviarValidarTicket();

    $message = $response->message;
    $code =  $response->success ? 200 : 400;


		return response([ 'data' => $message ], $code );
  }
  
  public function anulacion( $id_documento , $tipo_documento = 'venta' , $anulacion = false )
  {
    $error = '';    
    // Mandar zip
    try {
      \DB::beginTransaction();

      $is_venta = $tipo_documento == 'venta';
      $documento = $is_venta ? 
      Venta::find($id_documento) :      
      GuiaSalida::find($id_documento);

      $empresa = $documento->empresa;


      $ids = (array) $id_documento;
      $data = [];
      $data["fecha_generacion"] = $is_venta ? $documento->VtaFvta : $documento->GuiFDes;
      $data["fecha_documento"] =  $is_venta ? $documento->VtaFvta : $documento->GuiFemi;

      if( $is_venta ){

        if( $documento->isBoleta()){
          $is_resumen = true;
        }
        else {

          if( ($documento->isNotaCredito() && $documento->docRefIsBoleta()) ){
            
            // $empresa->isOse();
            // isOse
            $is_resumen = $empresa->isOse() ? false :  true;
          }
          else {
            $is_resumen = false;
          }
        }
      }

      else {
        $is_resumen = false;
      }

      
      if( $is_resumen ){
        $resumen =  Resumen::createResumen( $data , $data["fecha_generacion"] , $anulacion );
        $detalles = ResumenDetalle::createDetalle( $resumen , $ids , $anulacion );        
        $sent = Sunat::sentResumen( $resumen->NumOper );
      }

      else {
        $resumen = Resumen::createAnulacion($data);
        $detalles = ResumenDetalle::createAnulacion( $resumen , $ids , $tipo_documento );
        $sent = Sunat::sentAnulacion( $resumen->NumOper, false ,$resumen->DocNume );
      }

      // Validar ticket
      if( $sent['status'] ){

        $ticket   = $sent['message'];
        $sent     = Sunat::verificarTicket( $sent['message'] , $resumen->nameFile(true,'.zip'));
        $nameFile = $resumen->nameFile( true , '.xml' );
        $content  = $sent['content'];
        $datos    = ["ResponseCode","Description"];          
        $data     = extraer_from_content( $content , $nameFile, $datos );

        if( $is_venta && !$documento->isNotaCredito() ){
          $documento->successAnulacion($ticket);
        }

        if($sent['status']){
          $resumen->successValidacion($data);
        }

        else {
          $resumen->errorValidacion($data);                        
        }

        $sent['content'] = "";
        $sent['message'] = $resumen->DocDesc;
        $sent['ticket'] = $ticket;

      }
      
      \DB::commit();
      $success = true;
    } 

    catch (\Exception $e) {
      $success = false;
      $error = $e->getMessage();
      \DB::rollback();  
      Log::error( "Error sunat " . $e->getMessage() );
      return response()->json( 'Se ha producido un error ' . $e , 500 );
    }

    if( $success ) {
      return $sent;
    }
  }

  public function anular_documento( VerificationDocumentoAnulationRequest $request)
  {
    return $this->anulacionCustom( $request->id_factura );
  }


  public function enviar_ticket( EnviarTicketRequest $request )
  {
    $resumen = Resumen::findMultiple( $request->id_resumen , $request->docnume);
    $rpta = $resumen->validateTicket();

    notificacion(
      "Validación",
      $rpta->message,
      $rpta->success == true ? 'success' : 'error'
    );
  }

  public function sentTicket($resumen)
  {
    try {
      \DB::beginTransaction();
      $sent =  Sunat::verificarTicket($resumen->DocTicket, $resumen->nameFile(true, '.zip'));
      \DB::commit();
      $success = true;
    }

    catch (\Exception $e) {
      $success = false;
      $error = $e->getMessage();
      \DB::rollback();
      return response()->json('Error al guardar el documento ' . $error, 500);
    }

    if ($success) {

      if ($sent['status']) {
        $resumen->successValidacion($sent);
      }
      
      else {
        $resumen->errorValidacion($sent);
      }

      unset($sent['content']);
      return response()->json(['msj' => $resumen->DocDesc, 'sent' => $sent], $sent['code_http']);
    }
  }


  /**
   * Procesar el resumen 
   * 
   * @param string $numoper 
   * @param string $docnume 
   *    
   * @return mixed
   */
  public function processResumen( $numoper, $docnume )
  {
    $resumen = Resumen::findMultiple($numoper, $docnume);

    // Si tiene Ticket
    if( $resumen->hasTicket() ){
      $response = $resumen->validateTicket();
    }
    else {      
      $response = $resumen->enviarValidarTicket();
    }

    $code = $response->success == true ? '200' : '400';
    $message = $response->message;

    return response(['data' => $message], $code);
    
  }

}