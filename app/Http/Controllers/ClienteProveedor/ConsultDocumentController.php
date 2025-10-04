<?php

namespace App\Http\Controllers\ClienteProveedor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Util\ConsultDocument\ConsultDniInterface;
use App\Util\ConsultDocument\ConsultRucInterface;
use App\Http\Requests\ClienteProveedor\ConsultDniRequest;
use App\Http\Requests\ClienteProveedor\ConsultRucRequest;
use App\Util\ConsultAgenteRetencion\ConsultAgenteRetencionMigo;

class ConsultDocumentController extends Controller
{
    public $consulterDni;
    public $consulterRuc;

    public function __construct(ConsultDniInterface $consulterDni, ConsultRucInterface $consulterRuc )
    {
        $this->consulterDni = $consulterDni;
        $this->consulterRuc = $consulterRuc;
    }

    /**
     * Consultar DNI
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function consultDNI(ConsultDniRequest $request)
    {
        $response = $this->consulterDni->consult($request->numero);

        return response()->json(
            ['data' => $response['data'] , 'success' => $response['success'], 'error' => $response['error']] , 
            $response['success'] ? 200 : 400
        );
    }

    /**
     * Consultar DNI
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function consultRUC(ConsultRucRequest $request)
    {
        $response = $this->consulterRuc->consult($request->numero);

        $response['data']['agente_retencion'] = false;
        $response['data']['agente_retencion_resolucion'] = '';
        $response['data']['agente_retencion_apartir_del'] = '';

        if($response['success'] && strlen(trim($request->numero)) == 11){
          $responseAgenteRetencion = (new ConsultAgenteRetencionMigo())->consult($request->numero);
          if($responseAgenteRetencion['success']){
            $response['data']['agente_retencion'] = true;
            $response['data']['agente_retencion_resolucion'] = $responseAgenteRetencion['data']['resolucion'];
            $response['data']['agente_retencion_apartir_del'] = $responseAgenteRetencion['data']['a_partir_del'];
          }
        }

        return response()->json(
            ['data' => $response['data'], 'success' => $response['success'], 'error' => $response['error']],
            $response['success'] ? 200 : 400
        );
    }

}
