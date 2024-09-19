<?php

namespace App\Http\Controllers;

use App\M;
use App\Zona;
use Exception;
use App\Moneda;
use App\Vendedor;
use App\ListaPrecio;
use App\TipoCliente;
use App\Departamento;
use App\TipoDocumento;
use App\ClienteProveedor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\BuscarClienteRequest;
use App\Http\Requests\Cliente\RegisterCliente;
use App\Util\ConsultDocument\ConsultDniInterface;
use App\Util\ConsultDocument\ConsultRucInterface;
use App\Http\Requests\ClienteProveedorCrearRequest;
use App\Http\Requests\ClienteProveedorEditarRequest;
use App\Http\Requests\ClienteProveedorEliminarRequest;

class ClienteProveedorController extends Controller
{
  public $cliente_proveedor;

  public function __construct(ConsultDniInterface $consulterDni, ConsultRucInterface $consulterRuc)
  {
    $this->cliente_proveedor = new ClienteProveedor;
    $this->consulterDni = $consulterDni;
    $this->consulterRuc = $consulterRuc;

    $this->middleware([p_midd('A_INDEX', 'R_CLIENTE')])->only('index');
  }

  // ver clientes
  public function index($accion = false, $id_ruc_cliente = "")
  {
    $tipos_clientes   = TipoCliente::all();
    $tipos_documentos = TipoDocumento::all();
    $departamentos = Departamento::all();
    $vendedores    = Vendedor::all();
    $monedas       = Moneda::all();
    $lista_precios = ListaPrecio::all();
    $cliente = '';

    return view('clientes.index', [
      'tipos_clientes'   => $tipos_clientes,
      'tipos_documentos_clientes' => $tipos_documentos,
      'departamentos'    => $departamentos,
      'vendedores'        => $vendedores,
      'monedas'          => $monedas,
      'lista_precios'    => $lista_precios,
      'accion'           => $accion,
      'ruc'              => $id_ruc_cliente,
      'cliente'          => $cliente,
    ]);
  }

  public function create(ClienteProveedorCrearRequest $request)
  {
    $this->authorize(p_name('A_CREATE', 'R_CLIENTE'));

    $data = $request->all();
    // _dd( $data );
    // exit();
    $clienteProveedor = new ClienteProveedor;
    $clienteProveedor->EmpCodi = session()->get('empresa');
    $clienteProveedor->TipCodi = $data['tipo_cliente'];
    $clienteProveedor->TDocCodi = $data['tipo_documento'];
    $clienteProveedor->PCNomb  = $data['razon_social'];
    $clienteProveedor->PCCodi  = null;
    $clienteProveedor->PCRucc  = $data['ruc'];
    $clienteProveedor->PCDire  = $data['direccion_fiscal'];
    $clienteProveedor->PCDist  = isset($data['ubigeo']) ? $data['ubigeo'] : '';
    $clienteProveedor->PCTel1  = $data['telefono_1'];
    $clienteProveedor->PCTel2  = isset($data['telefono_2']) ? $data['telefono_2'] : '';
    $clienteProveedor->PCMail  = $data['email'];
    $clienteProveedor->PCCont  = $data['contacto'];
    $clienteProveedor->PCCMail = null;
    $clienteProveedor->VenCodi = $data['vendedor'];
    $clienteProveedor->ZonCodi = $data['ZonCodi'] ?? Zona::DEFAULT_ZONA;
    $clienteProveedor->TdoCodi = $data['tipo_documento'];
    $clienteProveedor->MonCodi = isset($data['moneda']) ? $data['moneda'] : '01';
    $clienteProveedor->PCAfPe = 0;
    $clienteProveedor->LisCodi = "10";
    $clienteProveedor->PCLine = "0";
    $clienteProveedor->PCDeud = null;
    $clienteProveedor->PCANom = $data['nombre_avalista'];
    $clienteProveedor->PCARuc = $data['ruc_avalista'];
    $clienteProveedor->PCADir = $data['direccion_avalista'];
    $clienteProveedor->PCATel = $data['telefono_avalista'];
    $clienteProveedor->PCAEma = $data['email_avalista'];
    $clienteProveedor->User_crea = auth()->user()->usulogi;
    $clienteProveedor->User_ECrea = gethostname();
    $clienteProveedor->UDelete = "";
    $clienteProveedor->save();
    return $clienteProveedor;
  }

  /*
  * Modificación de un cliente
  *
  **/

  public function edit(ClienteProveedorCrearRequest $request)
  {
    $this->authorize(p_name('A_EDIT', 'R_CLIENTE'));

    $data = json_decode(json_encode($request->all()), true);
    $cliente_proveedor = $this->cliente_proveedor->show_cliente_exacto($data['codigo'], $data['tipo_cliente']);

    if ($cliente_proveedor->canEditDoc()) {
      $cliente_proveedor->PCRucc = $data['ruc'];
      $cliente_proveedor->TdoCodi = $data['tipo_documento'];
      $cliente_proveedor->TDocCodi = $data['tipo_documento'];
    }

    $cliente_proveedor->PCNomb = $data['razon_social'];
    $cliente_proveedor->PCDire = $data['direccion_fiscal'];
    $cliente_proveedor->PCTel1 = $data['telefono_1'];
    $cliente_proveedor->PCMail = $data['email'];
    $cliente_proveedor->PCDist  = isset($data['ubigeo']) ? $data['ubigeo'] : null;
    $cliente_proveedor->PCCont = $data['contacto'];
    $cliente_proveedor->VenCodi = $data['vendedor'];
    $cliente_proveedor->MonCodi = $data['moneda'];
    $cliente_proveedor->LisCodi = $data['lista_precio'];
    $cliente_proveedor->PCAfPe = $data['af_pe'];
    $cliente_proveedor->ZonCodi = $data['ZonCodi'] ?? Zona::DEFAULT_ZONA;
    $cliente_proveedor->PCANom = $data['nombre_avalista'];
    $cliente_proveedor->PCARuc = $data['ruc_avalista'];
    $cliente_proveedor->PCADir = $data['direccion_avalista'];
    $cliente_proveedor->PCATel = $data['telefono_avalista'];
    $cliente_proveedor->PCAEma = $data['email_avalista'];
    $cliente_proveedor->User_EModi = gethostname();
    $cliente_proveedor->User_Modi = auth()->user()->usulogi;
    $cliente_proveedor->save();
    return $cliente_proveedor;
  }

  /**
   * Buscar clientes por el index 
   * 
   * @return datatable
   */
  public function search(Request $request)
  {
    $deleted = $request->input('deleted', 0);

    $busqueda = ClienteProveedor::where('TipCodi', $request->tipoentidad_id)
      ->when($deleted, function ($query) {
        $query->withoutGlobalScope('noEliminados');
        $query->where('UDelete', '=', "*");
      });

    return DataTables::of($busqueda)
      ->addColumn('acciones', 'clientes.partials.column_accion')
      ->rawColumns(['acciones'])
      ->make(true);

    return datatables()->of(ClienteProveedor::tipoCliente())->toJson();
  }

  /*
  * Consultar ultimo 
  *
  */
  public function consulta_codigo(Request $request)
  {
    $tipo = $request->tipo_cliente;
    $ultimo_codigo = $this->cliente_proveedor->buscar_ultimo_codigo($tipo);
    $codigo = $ultimo_codigo;
    return $codigo;
  }


  public function ruc_busqueda(Request $request)
  {
    if (strlen($request->numero) == 11) {
      $data = consultar_ruc($request->numero);
      return response()->json($data, $data['code']);
    } else {
      return consultar_dni($request->numero);
    }
  }

  public function eliminar(ClienteProveedorEliminarRequest $request)
  {
    $this->authorize(p_name('A_DELETE', 'R_CLIENTE'));

    $clienteProveedor =
      $this->cliente_proveedor->show_cliente_exacto($request->codigo,  $request->tipo);

    //
    $isUse = $clienteProveedor->isUse();

    if ($isUse->success) {
      $clienteProveedor->toggleSoftDelete();
      // $message = $isUse->message;
      // return response()->json(['message' => $message], 400);
    } else {
      $clienteProveedor->delete();
    }
  }


  public function restaurar(Request $request)
  {
    $this->authorize(p_name('A_DELETE', 'R_CLIENTE'));


    $cp = ClienteProveedor::withoutGlobalScope('noEliminados')
      ->where('TipCodi', $request->tipo)
      ->where('PCCodi', $request->codigo)->first();

    $cp->toggleSoftDelete();
  }


  public function consultar_datos(Request $request)
  {
    $this->authorize(p_name('A_SHOW', 'R_CLIENTE'));

    $cliente_proveedor = ClienteProveedor::with(['ubigeo.departamento', 'ubigeo.provincia'])
      ->where('PCCodi', $request->codigo)
      ->where('EmpCodi', empcodi())
      ->where('TipCodi', 'C')
      ->first();

    $data = $cliente_proveedor->toArray();
    $data['edit_doc'] = $cliente_proveedor->canEditDoc();;

    return $data;
  }


  public function buscar_cliente(BuscarClienteRequest $request)
  {
    $cliente = ClienteProveedor::with('tipo_documento_c')->where('PCRucc', $request->codigo)->first()->toArray();

    if ($cliente) {
      return $cliente;
    }

    return response("No se ha encontrado este usuario",  404);
  }

  public function searchOne($type, $term, $ruc = true)
  {
    $busqueda =  ClienteProveedor::with('tipo_documento_c')
      ->where([
        ['TipCodi', $type]
      ]);

    $term = strtoupper(trim($term));

    if (str_contains($term, 'c:') || str_contains($term, 'C:')) {
      $term = trim(last(explode('C:', $term)));
      $busqueda->where('PCCodi', 'LIKE', '%' . $term . '%');
    } else {
      $busqueda = $ruc ?
        $busqueda->where('PCRucc', 'like', $term . '%') :
        $busqueda->where('PCNomb', 'LIKE', '%' . strtoupper(trim($term)) . '%');
    }




    return $busqueda
      ->get()
      ->take(10);
  }


  public function buscar_cliente_select2(Request $request)
  {
    $term = $request->data;

    if (empty($term)) {
      return \Response::json([]);
    }

    $type = strtoupper($request->input('type', 'C'));
    $clientes = $this->searchOne($type, $term, true);
    $clientes = $clientes->count() ? $clientes : $this->searchOne($type, $term, false);

    $data = [];

    foreach ($clientes as $cliente) {
      $text = $cliente->PCRucc . " - " . $cliente->PCNomb;
      $returnId = $request->input('returnId', false);
      $data[] = [
        'id' => $returnId ? $cliente->PCCodi : $cliente->PCRucc,
        'text' => $text,
        'data' => $cliente->toArray()
      ];
    }

    return \Response::json($data);
  }

  public function searchByCliente(Request $request)
  {
    $term = $request->data;

    if (empty($term)) {
      return \Response::json([]);
    }

    $type = strtoupper($request->input('type', 'C'));
    $clientes = $this->searchOne($type, $term, true);
    $clientes = $clientes->count() ? $clientes : $this->searchOne($type, $term, false);
    $data = [];

    foreach ($clientes as $cliente) {
      $text = $cliente->PCRucc . " - " . $cliente->PCNomb;
      $data[] = ['id' => $cliente->PCCodi, 'text' => $text, 'data' => $cliente->toArray()];
    }

    return \Response::json($data);
  }

  public function register(RegisterCliente $request)
  {
    $this->authorize(p_name('A_CREATE', 'R_CLIENTE'));

    $tipo_documento = $request->tipo_documento;

    $direccion = null;
    $ubigeo = null;

    if ($tipo_documento === TipoDocumento::NINGUNA) {
      $razon_social =  $request->value;
      $documento = ".";
    } elseif ($tipo_documento === TipoDocumento::DNI ||  $tipo_documento === TipoDocumento::RUC) {

      $documento = $request->value;

      $response =  $tipo_documento === TipoDocumento::DNI ?
        $this->consulterDni->consult($documento) :
        $this->consulterRuc->consult($documento);

      if (!$response['success']) {
        throw new Exception($response['error'], 1);
      }

      $razon_social =  $response['data']['razon_social'];
      $direccion = $response['data']['direccion'] ?? null;
      $ubigeo = $response['data']['ubigeo'] ?? null;
    }

    $clienteProveedor = new ClienteProveedor;

    $clienteProveedor->PCCodi = $clienteProveedor->buscar_ultimo_codigo('C');
    $clienteProveedor->EmpCodi = session()->get('empresa');
    $clienteProveedor->TipCodi = 'C';
    $clienteProveedor->TDocCodi = $tipo_documento;
    $clienteProveedor->PCNomb  = $razon_social;
    $clienteProveedor->PCRucc  = $documento;
    $clienteProveedor->PCDire  = $direccion;
    $clienteProveedor->PCDist  = $ubigeo;
    $clienteProveedor->ZonCodi = Zona::DEFAULT_ZONA;
    $clienteProveedor->TdoCodi = $tipo_documento;
    $clienteProveedor->MonCodi = '01';
    $clienteProveedor->LisCodi = "10";
    $clienteProveedor->User_crea = auth()->user()->usulogi;
    $clienteProveedor->User_ECrea = gethostname();
    $clienteProveedor->UDelete = "";
    $clienteProveedor->save();
    $clienteProveedor = $clienteProveedor->toArray();
    $clienteProveedor['tipo_documento'] = $tipo_documento;
    return $clienteProveedor;
  }
}
