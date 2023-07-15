<?php

namespace App\Console\Commands;

use App\Empresa;
use App\Jobs\ReporteDeEnvioDeDocumentosJob;
use App\Resumen;
use App\ResumenDetalle;
use App\Sunat;
use App\Venta;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MandarResumens extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'envio:resumenes {fecha?}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Envio de resumenes de boletas';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{

		if(is_ose()){
			return;
		}

		$fecha = $this->argument('fecha');
		$fecha = is_null($this->argument('fecha')) ? hoy() : explode("=",$fecha)[1];
		
		DB::transaction( function() use ($fecha) {
			$need_reporte = false;

			$empresas_documentos = 
				\DB::table('ventas_cab')
				->join('opciones', 'opciones.EmpCodi', '=', 'ventas_cab.EmpCodi')
				->where('ventas_cab.TidCodi',  "03" )
				->where('ventas_cab.VtaFvta', $fecha )
				->where('ventas_cab.fe_rpta', '!=' ,  "0" )
				->where('ventas_cab.VtaEsta', '!=' ,  "A" )
				->select('ventas_cab.VtaOper','ventas_cab.EmpCodi' , 'opciones.EmpCodi')
				->get()
				->groupBy('EmpCodi');



			$reportes = [];

			if( !$empresas_documentos->count() ){
				return;
			}

			foreach( $empresas_documentos as $empcodi => $empresa_documentos ){
		
				$empresa = Empresa::find($empcodi);
				$reportes[$empcodi] = [
					'empresa_name' =>  $empresa->nombre(),
					'empresa_ruc'  =>  $empresa->ruc(),
					'fecha_envios' => hoy(),
					'send_email'   => false,
					'documentos'   => [],
				];

				foreach( $empresas_documentos as $documentos ){

					$documentos_arr = [];

					// Verificar que los documentos no esten en otro resumen
					foreach( $documentos as $documento ){

						$venta = Venta::find($documento->VtaOper , $empcodi);
						
						if( is_null($venta->anulacion)){
							array_push($documentos_arr,$documento);
							$data_documento = [
								'VtaOper' => $venta->VtaOper,
								'VtaSeri' => $venta->VtaSeri,
								'VtaNumee'=> $venta->VtaNumee,
								'VtaFvta' => $venta->VtaFvta,							
								'VtaNume' => $venta->VtaNume,
							];												
							array_push($reportes[$empcodi]['documentos'],$data_documento);
						}
					}

					$documentos_arr = collect($documentos_arr);

					if( !$documentos_arr->count() ){
						continue;
					}


					$ids = $documentos_arr->pluck('VtaOper');
					$data = ['fecha_generacion' => $fecha , 'fecha_documento' => $fecha ];
		      $resumen = Resumen::createResumen( $data, $fecha , false , $empcodi );
		      $r_detalles = ResumenDetalle::createDetalle( $resumen , $ids , false , $empcodi );

		      $sent = Sunat::sentResumenOAnulacion( $resumen->NumOper , true , $resumen->DocNume, $empcodi );

		      if( $sent['status'] ){
		        $ticket   = $sent['message'];
		        $nameFile = $resumen->nameFile(true,'.zip');	        
		        $sent     = Sunat::verificarTicket($ticket ,$nameFile);
						
						
		        $content  = $sent['content'];
		        $datos    = ["ResponseCode","Description"];
		        $data     = extraer_from_content( $content , $resumen->nameFile(true,'.xml'), $datos );
		        if( $sent['status'] ){
		          $resumen->saveSuccessValidacion( $data[0] , $data[1] );	          
		        }
		        else {
		        	$need_reporte = true;
		        	$reportes[$empcodi]['send_email'] = true;
		        	$reportes[$empcodi]['error'] = [
		        		'message' => $data[0] . " " . $data[1],
	        			'tarea' =>'Envio resumen',
		        	];
		          $resumen->errorValidacion($data);                        
		        }
		      }
		      else {
	        	$need_reporte = true;
	        	$reportes[$empcodi]['send_email'] = true;
	        	$reportes[$empcodi]['error'] = [
	        		'message' => $sent['message'],
	        		'tarea' => 'Validación de ticket',
	        	];

	        	$code = substr($sent['message'], 1);
	        	$code = substr( $code , 0 , strpos($code,")") );
	          $resumen->errorValidacion(['code'=>$code]);
		      }
				}

			} // foreach empresas por documentos

			if( $need_reporte ){
				ReporteDeEnvioDeDocumentosJob::dispatch($reportes,true);
			}

		}); // dbtransaccion


	}
}
