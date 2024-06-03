<?php

namespace App\Http\Controllers\Import;

use App\Producto;
use Illuminate\Http\Request;
use App\Util\Import\Excell\ImportExcellProducts;

class ImportProductosController extends ImportController
{
  public function __construct()
  {
    parent::__construct(new Producto());    
    $this->middleware(p_midd('A_IMPORTARPRODUCTO', 'R_UTILITARIO'))->only('create');
  }

  public function store(Request $request)
  {
    ini_set('memory_limit', '850M');
    ini_set('max_execution_time', '300');

    $request->validate(
      [ 'excel' => 'required|mimes:xlsx|max:1024'],
      [ 'excel.required'  => 'Tiene que subir un archivo excel']
    );

    $importer = new ImportExcellProducts($request->file('excel'));

    $result = $importer
    ->handle()
    ->getResult();


    $message = $result->success ? 'Importación Exitosa' : 'Error en la importaciòn';
    $code = $result->success ? 200 : 400;
    return response()->json( ['message' => $message, 'errors' => $result->errors] , $code);
  }
}