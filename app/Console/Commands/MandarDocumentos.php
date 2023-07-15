<?php

namespace App\Console\Commands;

use App\Sunat;
use App\Venta;
use App\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Jobs\ReporteDeEnvioDeDocumentosJob;

class MandarDocumentos extends Command
{
	protected $signature = 'envio:documentos {empresa_id?}';
	protected $description = 'Enviar documentos automaticamente a la sunat';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */

	public function enviarPendiente(Empresa $empresa)
	{
		$database = $empresa->getDatabase();
		Config::set('database.connections.tenant.database', $database);
		$ventas_group = Venta::pendientes($empresa->isOse())->get()->chunk(100);
		foreach ($ventas_group as $ventas) {
			foreach ($ventas as $venta) {
				try {
					$venta->sendSunatPendiente(true, $empresa);
				} catch (\Throwable $th) {
				}
			}
		}
	}

	public function handle()
	{
		// Enviar de empresa especifica
		if ($empresa_id = $this->argument('empresa_id')) {
			$empresa = Empresa::find($empresa_id);
			$this->enviarPendiente($empresa);
		}

		// Enviar de todas las empresa
		else {
			$empresas_group = Empresa::activas()->ambienteProduccion()->get()->chunk(20);
			foreach ($empresas_group as $empresas) {
				foreach ($empresas as $empresa) {
					$this->enviarPendiente($empresa);
				}
			}
		}

		Log::info('@FIN EJECUTANDO ENVIO DE DOCUMENTOS');
	}
}
