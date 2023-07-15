<?php

namespace App\Http\Controllers\Documento;

use Illuminate\Http\Request;
use App\Helpers\ConsultarDocumento;
use App\Http\Controllers\Controller;
use App\Http\Requests\Documento\ApiConsultRucRequest;
use App\Util\Sunat\Request\credentials\CredentialManual;
use App\Util\Sunat\Services\ServicesParams;
use App\Util\Sunat\Services\SunatConsult\ConsultStatusSunat;

class DocumentoApiConsultController extends Controller
{
	public function consult(ApiConsultRucRequest $request, $ruc )
	{
		$response = [];
		$consult = new ConsultarDocumento($ruc, true);
		$response = $consult->searchBySunat();
		return response()->json($response);
	}

	public function consultDoc(ApiConsultRucRequest $request)
	{
		$code = 200;
		$data = [];
		$credentials = new CredentialManual("20475986645GENARO18", "genaro2018");
		$params = ServicesParams::getFormatGetStatus(
			"20475986645",
			"09",
			"T001",
			"3367"
		);

		/* T001-3367 */

		$communicator = new ConsultStatusSunat( $credentials , true, false );

		$response =
		$communicator
		->setParams( $params )
		->communicate()
		->getResponse();

		if( $response['communicate'] ){
			$data = $response['commnucate_data']->status;
		}

		else {
			$code = 400;
		}
		return response()->json($data , $code);
	}

}