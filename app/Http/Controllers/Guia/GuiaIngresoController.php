<?php

namespace App\Http\Controllers\Guia;

use App\Sunat;
use App\Venta;
use App\Empresa;
use App\Vendedor;
use App\GuiaSalida;
use App\GuiaSalidaItem;
use App\SerieDocumento;
use App\ClienteProveedor;
use App\Models\Guia\Guia;
use Illuminate\Http\Request;
use App\Events\GuiaHasCreate;
use App\Mail\GuiaRemisionMail;
use App\Models\Guia\GuiaIngreso;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\GuiaAnulacionRequest;
use App\Http\Requests\Guia\SaveConformidadRequest;
use App\Http\Requests\Guia\GuiaIngresoStoreRequest;
use App\Http\Requests\Guia\GuiaIngresoUpdateRequest;

class GuiaIngresoController extends GuiaController
{
	public $model;
	public $tipo;

	public function __construct()
	{
		$this->tipo = GuiaIngreso::INGRESO;
		$this->model = new GuiaIngreso;

		$this->middleware('guia.seriecreada')->only(['create', 'edit', 'store']);
    $this->middleware(p_midd('A_INDEX', 'R_GUIAINGRESO'))->only('index');
    $this->middleware(p_midd('A_CREATE', 'R_GUIAINGRESO'))->only('create', 'store');

    // $this->middleware(p_midd('A_EDIT', 'R_GUIAINGRESO'))->only('show', 'edit');
    // $this->middleware(p_midd('A_IMPRIMIR', 'R_GUIAINGRESO'))->only('pdf');
    // $this->middleware(p_midd('A_RECURSO', 'R_GUIAINGRESO'))->only('file');
    // $this->middleware(p_midd('A_EMAIL', 'R_GUIAINGRESO'))->only('sentEmail');
    // $this->middleware(p_midd('A_DELETE', 'R_GUIAINGRESO'))->only('delete');
    // $this->middleware(p_midd('A_ANULAR', 'R_GUIAINGRESO'))->only('anularGuia');
  }

	public function getDataDefault()
	{
		return [
			'tipo' => $this->tipo,
			'titulo' => $this->titulo,
		];
	}


	public function index(Request $request)
	{
		return view('guia_remision.guia_ingreso.index', [
			'format' => $request->input('format', false),
      'locales' => user_()->locales->load('local')

		]);
	}

	/**
   * //@TODO al hacer una venta con un monto muy elevado me da un error relacionado con la caja
	 */
	public function create($id_venta = null)
	{
		$data = $this->acciones('create', null, Guia::INGRESO);
		return view('guia_remision.guia_ingreso.create', $data);
	}

	public function edit($id_guia)
	{
		$guia = GuiaSalida::find($id_guia);
		$data = $this->acciones('edit', $id_guia, Guia::INGRESO);
		return view('guia_remision.guia_ingreso.edit', $data);
	}

	public function show( $id_guia )
	{
		return redirect()->action(
			'GuiaSalidaController@edit',
			[ 'id_guia' => $id_guia ]
		);
	}

	public function store( GuiaIngresoStoreRequest $request )
	{  
		$id_guia = null;

		DB::connection('tenant')->transaction(function () use ($request, &$id_guia) {
			$fromVenta = false;
			$data = $request->all();
			if ($request->doc_ref) {
				$fromVenta = true;
				$data = Venta::findByNume($request->doc_ref)->VtaOper;
			}
			$id_guia = GuiaSalida::createGuia($data, $fromVenta, $request->id_almacen, $request->id_movimiento, false , '', $request->fecha_emision, true );
			GuiaSalidaItem::createItems($id_guia, $request->items);
			$guia = GuiaSalida::find($id_guia);
			$guia->calculateTotal();
			$guia->setPendienteState();
			event(new GuiaHasCreate($id_guia));
		});
	}

	public function update(GuiaIngresoUpdateRequest $request, $id_guia)
	{
		$guia = GuiaSalida::find($id_guia);
		$guia->updateGuia($request);
		notificacion('', 'Guia Actualizada correctamente', 'success');
    return response()->json(['route_redirect' => route('guia_ingreso.index')
    ]);
	}
	public function pdf($id_guia)
	{    
		$guia = GuiaSalida::find($id_guia);
		$namePdf = $guia->nameEnvio('.pdf');
		$fileHelper = filehelper();

		if ($fileHelper->pdfExist($namePdf)) {
			$content = $fileHelper->getPdf($namePdf);
			$path = $fileHelper->saveTemp($content, $namePdf);
			return response()->file($path);
		} else {
			return $guia->savePdf();
		}
	}

	public function file($type, $id)
	{
		$guia = GuiaSalida::find($id);;
		$fileHelper = FileHelper(get_empresa('EmpLin1'));
		$exists = null;

		if ($type == 'xml') {
			$nameFile = $guia->nameEnvio('.xml');
			$exists = $fileHelper->xmlExist($nameFile);
		}
		if ($type == 'cdr') {
			$nameFile = 'R-' .  $guia->nameEnvio('.zip');
			$exists = $fileHelper->cdrExist($nameFile);
		}


		if ($exists) {
			$content = $type == 'xml' ? $fileHelper->getEnvio($nameFile) : $fileHelper->getCdr($nameFile);
			$path = $fileHelper->saveTemp($content, $nameFile);
			return response()->download($path);
		}
		return "No se encuentra este recurso";
	}


	public function anularGuia(GuiaAnulacionRequest $request)
	{
		$guia = GuiaSalida::find($request->id_factura);
		$guia->anular();
	}

	public function delete($id_guia, Request $request)
	{
		$guia = GuiaSalida::find($id_guia);
		$guia->deleteComplete();

		notificacion("Eliminado", "Se ha eliminado exitosamente la guia de remisión {$guiaName}", "success");
		return redirect()->back();
	}

	public function sentEmail(Request $request)
	{
		$this->validate($request, [
			'corre_hasta' => 'required',
			'asunto' => 'required',
		], [
			'corre_hasta.required' => 'Es necesario colocar el email del cliente',
			'asunto.required' => 'Es necesario colocar el asunto'
		]);

		$guia = GuiaSalida::find($request->id_guia);
		$data = [
			'subject' => $request->asunto,
			'documento_codi' => $guia->GuiOper,
			'cliente_documento' => $guia->GuiSeri . '-' . $guia->GuiNumee,
			'view' => "mails.enviar_guia",
			'tipo_documento' => "GUIA REMISIÓN",
			'fecha' => $guia->GuiFDes,
			'empresa_codi' => $guia->EmpCodi,
			'mensaje'      => $request->mensaje ?? "",
			'empresa'         => $guia->empresa->EmpNomb,
			'attach'           => [],
		];

		$fileHelper = fileHelper(get_ruc());
		$nameCDR = $guia->nameCdr();
		$namePDF = $guia->nameEnvio('.pdf');
		$nameXML = $guia->nameEnvio('.xml');

		if ($fileHelper->cdrExist($nameCDR)) {
			$path = $fileHelper->saveTemp($fileHelper->getCdr($nameCDR), $nameCDR);
			array_push($data['attach'], $path);
		}

		if ($fileHelper->pdfExist($namePDF)) {
			$path = $fileHelper->saveTemp($fileHelper->getPdf($namePDF), $namePDF);
			array_push($data['attach'], $path);
		}

		if ($fileHelper->xmlExist($nameXML)) {
			$path = $fileHelper->saveTemp($fileHelper->getEnvio($nameXML), $nameXML);
			array_push($data['attach'], $path);
		}

		return Mail::to($request->corre_hasta)->send(new GuiaRemisionMail($data));
	}


	public function guiaSuccess($guia, $data)
	{
		$data_envio = $guia->successEnvio($data);
		$data = array_merge($data, $data_envio);
		$data['content'] = "";
		$data['message'] = $data[2];
		return response()->json($data, 200);
	}

	public function verificar(Request $request, $id_guia)
	{
		$guia = GuiaSalida::find($id_guia);
		$empresa = $guia->empresa;
		$data = Sunat::verify(
			$empresa->EmpLin1,
			$guia->getTipoDocumento(),
			$guia->GuiSeri,
			$guia->GuiNumee,
			$guia->nameEnvio()
		);

		if ($empresa->produccion()) {
			if ($data['message'] == 127) {
				$data = Sunat::sendGuia($request->id_guia);
				if ($data['status']) {
					return $this->guiaSuccess($guia, $data);
				}
				return $this->guiaSuccess($guia, $data);
			} else {
				return $this->guiaSuccess($guia, $data);
			}
		}
		return response()->json(['message' => $data['message']], $data['code_http']);
	}

	public function reporte()
	{
		$tipos_documentos = SerieDocumento::ultimaSerie();
		$empresa = Empresa::where('empcodi', session()->get('empresa'))->first();
		$almacenes  = $empresa->almacenes;
		$vendedores = Vendedor::all();
		$clientes = ClienteProveedor::where('Empcodi', empcodi())->get();
		return view('reportes.guias', compact('clientes', 'almacenes', 'vendedores', 'tipos_documentos'));
	}

	public function report_pdf(Request $request , $guia_id)
	{
    // loremp-ipsum-odlor
		dd($request->all());
	}

  public function saveConformidad( SaveConformidadRequest $request, $id )
  {
    $guia = GuiaIngreso::find( $id );
    $guia->e_conformidad = $request->e_conformidad;
    $guia->obs_traslado = $request->obs_traslado;
    $guia->save();
    return $request->all();
  }

}