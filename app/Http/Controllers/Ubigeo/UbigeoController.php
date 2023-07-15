<?php

namespace App\Http\Controllers\Ubigeo;

use App\Http\Controllers\Controller;
use App\Ubigeo;
use Illuminate\Http\Request;

class UbigeoController extends Controller
{
  public function ubigeosearch( Request $request )
  {
    // $this->authorize('ubigeo_consulta util');

    $term = $request->data;

    if (empty($term)) {
      return \Response::json([]);
    }

    $ubigeos = Ubigeo::with(['departamento','provincia'])
    ->where( 'ubinomb' , 'like' , '%'.$term.'%')->take(20)->get();

    if(  !$ubigeos->count() ){
      $ubigeos = Ubigeo::with(['departamento','provincia'])->where( 'ubicodi' , 'like' , '%'.$term.'%')
      ->take(20)
      ->get();
    }
    $data = [];
    
    foreach ($ubigeos as $ubigeo) {
      
      $dataUbigeo = $ubigeo->toArray();

      $text = sprintf("(%s) %s - %s - %s",
        $dataUbigeo['ubicodi'],
        $dataUbigeo['departamento']['depnomb'],
        $dataUbigeo['provincia']['provnomb'],
        $dataUbigeo['ubinomb'] 
      );

      $data[] = ['id' => $dataUbigeo['ubicodi'], 'text' => $text , 'data' => $dataUbigeo  ];
    }

    return \Response::json($data);
  } 

}
