<?php

namespace App\Http\Controllers\Proveedor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ClienteProveedor;

class ProveedorController extends Controller
{
    use ProveedorTrait;


    public function search( Request $request )
    {
        $term = $request->data;

        if (empty($term)) {
          return \Response::json([]);
        }

        $proveedores = $this->searchByTerm($term);  
        $data = [];
        
        foreach ($proveedores as $proveedor) {
          $text = $proveedor->PCRucc . " - " . $proveedor->PCNomb;
          $data[] = ['id' => $proveedor->PCCodi, 'text' => $text , 'data' => $proveedor->toArray() ];
        }
    
        return \Response::json($data);
    }


}
