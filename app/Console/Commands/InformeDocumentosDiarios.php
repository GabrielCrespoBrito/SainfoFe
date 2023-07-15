<?php

namespace App\Console\Commands;

use App\Empresa;
use App\Jobs\InformeDocumentosDiariosJob;
use Illuminate\Console\Command;

class InformeDocumentosDiarios extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'documentos:reporte';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reporte de documentos generados del dia';

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
    $empresas = Empresa::get(['EmpCodi']);
		foreach( $empresas as $empresa ){
			InformeDocumentosDiariosJob::dispatch( ayer() , $empresa->EmpCodi );	
		}
	}
}
