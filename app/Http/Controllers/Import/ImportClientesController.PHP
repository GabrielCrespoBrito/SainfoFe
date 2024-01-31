<?php

namespace App\Http\Controllers\Import;

use Exception;
use Throwable;
use ErrorException;
use App\ClienteProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Traits\ImportExcel;
use App\Util\Import\Excell\ImportExcellClientes;
use App\Util\Import\Excell\ImportExcellProducts;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class ImportClientesController extends ImportController
{
  public function __construct()
  {
    parent::__construct(new ClienteProveedor());

    // @TODO poner validaciòn para subir  clientes
    // $this->middleware(p_midd('A_IMPORTARPRODUCTO', 'R_UTILITARIO'))->only('create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illu minate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    
    ini_set('memory_limit', '450M');
    ini_set('max_execution_time', '900');
    $request->validate(['excel' => 'required|mimes:xlsx'], ['excel.required'  => 'Tiene que subir el archivo excel']);
    $message = 'Se ha guardado la información satisfactoriamente';
    $code = 200;
    $hojas_procesar = ['prov_clientes' ];
    $errors = [];

    $message = "Importaciòn de Clientes Exitosa";
    $code = 200;
    try {
      DB::connection('tenant')->beginTransaction();
      $importer = new ImportExcel($request->file('excel'), $hojas_procesar);
      $importer->saveDB();
      DB::connection('tenant')->commit();
    } catch (QueryException | Throwable | Exception | ErrorException | FatalThrowableError $e) {
      $message = strpos($e->getMessage(), "habido") !== false ? "ex" . $e->getMessage() : 'Ha habido un inconveniente en la importaciòn: (' . $e->getMessage() . ')';
      $code = 400;
      DB::connection('tenant')->rollback();
      return response()->json($message, $code);
      error_clear_last();
    }

    return response()->json([
      'message' => $message,
      'errors' => $errors],
      $code
    );
  }


  public function storeCustom(Request $request)
  {
    ini_set('memory_limit', '850M');
    ini_set('max_execution_time', '300');

    $request->validate(
      ['excel' => 'required|mimes:xlsx|max:1024'],
      ['excel.required'  => 'Tiene que subir un archivo excel']
    );

    $importer = new ImportExcellClientes($request->file('excel'));
    $result = $importer
      ->handle()
      ->getResult();

    $message = $result->success ? 'Importación Exitosa' : 'Error en la importaciòn';
    $code = $result->success ? 200 : 400;
    return response()->json(['message' => $message, 'errors' => $result->errors], $code);
  }
}
