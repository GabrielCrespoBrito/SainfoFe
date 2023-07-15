<?php

namespace App\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Venta\VentaSunatValidate;
use App\Resumen;

class VentasValidateController extends Controller
{
	public function show( Request $request )
	{
		$data = [
			'report' => false,
			'mes' => null
		];

		return view('reportes.validar_documentos.show', $data );
	}

	public function make( Request $request )
	{
		$mes = $request->mes;
		$validator = new VentaSunatValidate($mes);
		$validator->handle();
		$data_validator = $validator->getData();

		$data = [
			'report' => true,
			'success' => $data_validator['success'],
			'docs' => $data_validator['docs'],
			'mes' => $mes
		];

		return view('reportes.validar_documentos.show',  $data );
	}
}
