<?php

namespace App\Http\Controllers\Actividad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActividadController extends Controller
{
    public function actividad()
    {
        return view('clientes.actividad');
    }

    public function actividad_search(Request $request)
    {
        $busqueda = \DB::connection('tenant')->table('actividad_clientes')
            ->join('prov_clientes', 'actividad_clientes.PCCodi', '=', 'prov_clientes.PCCodi')
            ->where('prov_clientes.EmpCodi', empcodi())
            ->select(
                'prov_clientes.PCCodi',
                'prov_clientes.PCNomb',
                'actividad_clientes.Fecha',
                'actividad_clientes.Nombre',
                'actividad_clientes.Descripcion'
            );

        $term = $request->input('search')['value'];

        if (!is_null($term)) {
            $busqueda = $busqueda
                ->where('prov_clientes.PCNomb', 'LIKE', '%' . $term . '%')
                ->orWhere('actividad_clientes.Fecha', 'LIKE', '%' . $term . '%')
                ->orWhere('actividad_clientes.Nombre', 'LIKE', '%' . $term . '%')
                ->get();
        } else {
            $busqueda->orderBy('actividad_clientes.Fecha', 'desc');
        }

        return datatables()->of($busqueda)->toJson();
    }
}
