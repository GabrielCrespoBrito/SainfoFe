<?php

namespace App\Http\Controllers\ModuloApi;

use Illuminate\Http\Request;
use App\Helpers\ConsultarDocumento;
use App\ModuloApi\Models\User\User;
use App\Rules\ModuloApi\TokenValid;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Rules\ModuloApi\RucSearchDocument;
use App\Util\Sunat\Services\ServicesParams;
use App\Http\Requests\ModuloApi\getStatusRequest;
use App\Http\Requests\Documento\ApiConsultRucRequest;
use App\ModuloApi\Models\Empresa\Empresa;
use App\Rules\ModuloApi\ValidSerie;
use App\Util\Sunat\Request\credentials\CredentialManual;
use App\Util\Sunat\Services\SunatConsult\ConsultStatusSunat;

class DocumentConsultController extends Controller
{
	public function getStatus(Request $request)
	{		

		$validator = Validator::make($request->all(), [
			'ruc' => ['required', new RucSearchDocument()],
			'token' => ['required', new TokenValid()],
			'tipo_documento' => 'required|in:01,07,08',
			'serie' => ['required', 'max:4'],
			'numero' => ['required', 'numeric'],
		]);

		$data = ['error' => false];

		if( $validator->fails() ){
			$data['error'] = true;
			$data['errors'] = $validator->errors()->toArray();
			return response()->json($data, 400);

		}

		$empresa = Empresa::findByRuc($request->ruc);


		$credentials = new CredentialManual( $empresa->getUserSol() , $empresa->getPasswordSol() );		
		
		$params = ServicesParams::getFormatGetStatus(
			$request->ruc,
			$request->tipo_documento,
			$request->serie,
      $request->numero
		);

		$communicator = new ConsultStatusSunat( $credentials , true, false );

		$response =
		$communicator
		->setParams( $params )
		->communicate()
		->getResponse();

		if( $response['communicate'] ){
			$data = $response['commnucate_data']->status;
			$code = 200;
		}

		else {
			$code = 400;
		}

		return response()->json($data , $code);
	}
}