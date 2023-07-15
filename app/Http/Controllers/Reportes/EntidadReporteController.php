<?php

namespace App\Http\Controllers\Reportes;

use App\ClienteProveedor;
use Illuminate\Http\Request;
use App\Models\Tienda\Cliente;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Util\PDFGenerator\PDFGenerator;

class EntidadReporteController extends Controller
{
  public function __construct()
  {
    $this->middleware(p_midd('A_ENTIDAD', 'R_REPORTE'))->only(['create', 'report']);
  }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('reportes.entidades.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    { 
      ob_get_clean();

      $entidades = DB::connection('tenant')->table('prov_clientes')
      ->where('TipCodi', $request->tipo)
      ->select(
        'PCNomb as nombre',
        'TDocCodi as tipo_documento',
        'PCRucc as documento',
        'PCDire as direccion',
        'PCTel1 as telefono',
        'PCMail as correo',
        'PCDist as ubigeo',
        'User_FCrea as fecha_registro'
      )
      ->get();

      $titulo = 'REPORTE DE ' . ($request->tipo == ClienteProveedor::TIPO_CLIENTE ? 'CLIENTES' : 'PROVEEDORES'); 

      $data = [
        'titulo' => $titulo,
        'fecha' => date('Y-m-d H:i:s'),
        'entidades' => $entidades,
      ];

      $generator = new PDFGenerator(view('reportes.entidades.pdf', $data) , PDFGenerator::HTMLGENERATOR);
      return $generator->generate();

    }
}
