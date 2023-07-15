<?php

namespace App\Http\Controllers\Sunat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sunat\SunatProducto;
use App\Http\Controllers\Controller;

class SunatProductoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $term = strtoupper($request->data);
        $data = [];

        if (empty($term)) {
            return response()->json($data);
        }

        $products = DB::table('sunat_productos')
        ->where('descripcion', 'like' , "%{$term}%")
        ->OrWhere('id', 'like', "%{$term}%")
        ->limit(20)
        ->get();

        $data = [];

        foreach ($products as $product) {
            $data[] = ['id' => $product->id, 'text' => $product->id  . ' - ' .  $product->descripcion  ];
        }


        return \Response::json($data);
    }
}
