<?php

namespace App\Http\Controllers\ClienteAdministracion;

use App\Venta;
use Illuminate\Http\Request;
// use Maatwebsite\Excel\Excel;
use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadFileClienteRequest;
use App\Http\Requests\ClienteDashboardUpdatePasswordRequest;
use App\Http\Controllers\ClienteAdministracion\Traits\FileVentas;
use App\Http\Controllers\ClienteAdministracion\Traits\SearchVentas;
use App\Http\Controllers\ClienteAdministracion\Traits\ExcellVentasExport;

class ClienteDashboardController extends Controller
{
	use FileVentas, SearchVentas, ExcellVentasExport;

	public $cliente;

	public function __construct()
	{
		$this->middleware('cliente.acceso')->only(['index','search_documentos','descargar_files']);
	}

	public function busquedaDocumentos()
	{		
		return view('clientes_dashboard.buscar_documentos');
	}

  public function index()
  {
  	return view('clientes_dashboard.index' , ['collapse' => true , 'cliente' => get_cliente() ]);
  }

	public function search_documentos( Request $request)
	{
		$busqueda = [];

		if( $request->filter = "1" ){

			$busqueda = 
	    Venta::query()
	    ->with(['forma_pago','vendedor','moneda']);
			// ->where('PCCodi'  , session()->get('PCCodi') )
			// ->where('EmpCodi' , session()->get('EmpCodi_Cliente') )
			// ->where('fe_rpta' , '!=' , NULL)
			// ->where('VtaEsta' , '=' , 'V');

			$busqueda->whereBetween('VtaFvta', [ $request->fecha_desde, $request->fecha_hasta ]);

			if( $request->tipo != "todos" ){				
				$busqueda->where('TidCodi', $request->tipo );
			}

			if( $request->estado != "todos" ){

				if( $request->estado == "anulado" ){
					$busqueda->where('VtaEsta', 'A' );
				}
				else {
					$busqueda->where('fe_rpta', $request->estado )
					->where('VtaEsta', 'V' );					
				}

			}

	   	return datatables()->of( $busqueda )
	    ->addColumn('estado', 'ventas.partials.factura.column_estado')
	    ->rawColumns(['estado'])
	    ->toJson();
		}

   	return datatables()->of( $busqueda )->toJson();
	}

	public function descargar_files( Request $request )
	{

		set_time_limit ( 300 );
		// Si que buscar por filtro
		$filter =  $request->has('filter') ? $request->filter == "true" : false;
		// Excel o descargar comprimido de los documentos
		$type = $request->has('type') ? $request->type : 'zip';
		// En el caso que se halla selecciona unos documentos especificos
		$ids = $request->ids;
		$empcodi = is_null(session()->get('EmpCodi_Cliente')) ? session()->get('empresa') : session()->get('EmpCodi_Cliente') ;
		
		// Comprimir todos los archivos de la venta en un archivo comprimido
		if( $type == "zip" ){

			if( $filter ){
				$ids = $this->searchGetId( $empcodi, $request->fecha_desde, $request->fecha_hasta, $request->tipo , $request->estado );
			}


			if( count($ids) > 500 ){
				return response()->json(['message' => 'No se pueden descargar mas de 500 archivos a la vez' ], 400);
			}


			$comprimido =  $this->saveFiles( $ids , $empcodi );


			if( $comprimido ){
				$contenido = base64_encode(file_get_contents( $comprimido['path'] ));
				return ['contenido' =>  $contenido , 'nombre' =>  $comprimido['name'] , 'type' => 'zip' ];				
			}
			else {				
				return response()->json(['message' => 'No se encontrarón archivos para descargar' ], 400);
			}
		}

		// Mandar un excel con todos los archivos
		else {
			
			if( $filter ){

				$ids = $this->searchByFilter($empcodi,$request->fecha_desde,$request->fecha_hasta,$request->tipo,$request->estado)->get()->toArray();
        
			}
			
			ob_end_clean();
			ob_start();

			$data = $this->generateExcell($ids);
			$contenido = base64_encode(file_get_contents( $data['path'] ));
			return ['contenido' =>  $contenido , 'nombre' =>  $data['name'] , 'type' => 'zip' ];			


			return response()->json(['message' => 'Excel not yet' ], 400);
		}

	}

	public function perfil(){

		return view('clientes_dashboard.perfil' , ['cliente' => get_cliente() ] );
	}

	public function update_password( ClienteDashboardUpdatePasswordRequest $request )
	{

		$cliente = get_cliente();

		$cliente->PCDocu = $request->password;
		$cliente->save();

		session()->flash('message', 'Cambiado la contraseña correctamente');


		return redirect()->route('cliente_administracion.perfil');

	}



}
