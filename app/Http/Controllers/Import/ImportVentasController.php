<?php

namespace App\Http\Controllers\Import;

use App\Venta;
use Exception;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Suscripcion\Caracteristica;
use App\Util\Import\Excell\Ventas\ImportExcellVentas;
use Symfony\Component\Debug\Exception\FatalThrowableError;


class ImportVentasController extends ImportController
{
  public function __construct()
  {
    parent::__construct(new Venta());

    $this->middleware(p_midd('A_IMPORTARVENTA', 'R_UTILITARIO'))->only('create');
  }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
    $this->authorize(p_name('A_IMPORTARVENTA', 'R_UTILITARIO'));

    // @TODO, validar que la cantidad de documentos siministrador para subir no exceda la cantidad disponible de documentos por subir en plan
		$request->validate(['excel' => 'required|mimes:xlsx'], ['excel.required'  => 'Tiene que subir el archivo excel']);
		set_time_limit(600);

		$success = true;
		$errors = [];
		$cantidad = 0;

		try {
			$importer = new ImportExcellVentas($request->file('excel'));
			$importData = $importer->saveDB();
			$success = $importData['success'];
			$errors = $importData['errors'];
			$cantidad = $importData['cantidad'];
			DB::commit();

		} catch (Exception | ErrorException | FatalThrowableError $e) {
			$errors[] = "Ha habido un inconveniente al guardar los documentos: ({$e->getMessage()}";
			$success = false;
			DB::rollback();
			error_clear_last();
		}

		if ($success) {
			get_empresa()->sumarConsumo(Caracteristica::COMPROBANTES, $cantidad);
			return response()->json(['message' => "Se ha guardado la información satisfactoriamente"], 200);
		} else {
			return response()->json(['errors' => $errors], 400);
		}
	}
}