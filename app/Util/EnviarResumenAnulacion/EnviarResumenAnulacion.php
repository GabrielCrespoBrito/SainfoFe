<?php

namespace App\Util\EnviarResumenAnulacion;

use App\Venta;
use App\Resumen;
use App\ResumenDetalle;

class EnviarResumenAnulacion
{
  protected $documento;
  protected $fechaDoc;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(Resumen $resumen)
  {
    $this->resumen = $resumen;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function generate()
  {

    ///
      if( $is_resumen ){
        // $resumen =  Resumen::createResumen( $data , $data["fecha_generacion"] , $anulacion );
        // $detalles = ResumenDetalle::createDetalle( $resumen , $ids , $anulacion );        
        $sent = Sunat::sentResumen( $resumen->NumOper );
      }

      else {
        // $resumen = Resumen::createAnulacion($data);
        // $detalles = ResumenDetalle::createAnulacion( $resumen , $ids , $tipo_documento );
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
      return response()->json( 'Se ha producido un error ' . $e , 500 );
    }


    ///
  }
  
}
