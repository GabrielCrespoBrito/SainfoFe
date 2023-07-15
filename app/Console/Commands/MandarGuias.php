<?php

namespace App\Console\Commands;

use App\Empresa;
use App\GuiaSalida;
use App\Jobs\ReporteDeEnvioDeDocumentosJob;
use App\Jobs\ResumenEnvioGuiasMail;
use App\Sunat;
use App\Venta;
use Illuminate\Console\Command;

class MandarGuias extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'envio:guias';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Enviar guias automaticamente';

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
    // 
		$empresas_documentos = 
			\DB::table('guias_cab')
			->join('opciones', 'opciones.EmpCodi', '=', 'guias_cab.EmpCodi')
			->where('guias_cab.GuiFemi', hoy() )
			->where('guias_cab.GuiEFor', '=' , "1" )
			->where('guias_cab.fe_rpta', '=' , "9" )
			->select('guias_cab.GuiOper','guias_cab.EmpCodi')
			->get()
			->groupBy('EmpCodi');

		$reportes = [];
		$need_reporte = false;

		foreach( $empresas_documentos as $empcodi => $empresa_documentos ){

			$empresa = Empresa::find($empcodi);

			$reportes[ $empcodi ] = [
				'empresa_name' =>  $empresa->nombre(),
				'empresa_ruc'  =>  $empresa->ruc(),
				'fecha_envios' => hoy(),
				'send_email'   => false,
				'documentos'   => [],
			];

			foreach( $empresas_documentos as $documentos ){

				foreach( $documentos as $documento ){

					$guia = GuiaSalida::find( $documento->GuiOper , $empcodi );
					$sent = Sunat::sendGuia( $documento->GuiOper , $empcodi );

					if( $sent['status'] == "0" ){
						$need_reporte = true;
						$reportes[$empcodi]['send_email'] = true;                   
						
						$data_documento = [
							'GuiOper' => $guia->GuiOper,
							'GuiSeri' => $guia->GuiSeri,
							'GuiNumee' => $guia->GuiNumee,
							'GuiFemi' => $guia->GuiFemi,                           
							'GuiNume' => $guia->GuiNume,
							'Error_code' => $sent["code_error"],
							'Error_message' => $sent['error_message'],
						];
						array_push( $reportes[$empcodi]['documentos'] , $data_documento );
					}
					else {
						$guia->successEnvio( $sent );

					}
				}
			} // end empresas_documentos
		}

		// Envio Correo
		if( $need_reporte ){

			ResumenEnvioGuiasMail::dispatch($reportes);
		}       
	}
}
