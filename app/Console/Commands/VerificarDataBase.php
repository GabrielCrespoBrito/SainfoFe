<?php

namespace App\Console\Commands;

use App\Util\DBHelper\DBHelper;
use Illuminate\Console\Command;

class VerificarDataBase extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'db:help {tarea=ejecutar} {valor=null}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Verificar que existan todas las tablas y columnas para el buen funcionamiento del sistema ';

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
		$tarea = $this->argument('tarea');
		$valor = $this->argument('valor');
		$dbHelper = new DBHelper();
		
		$this->info(".... Ejecutando ....");
		switch($tarea){
			
			case 'ejecutar':
				if(   method_exists($dbHelper , $valor) ){
					$dbHelper->{$valor}();
				}
				$this->warn("El metodo {$valor} no existe");
			break;

			default:
			$this->warn("Tarea {$tarea} no existe");
			break;
		}
		
		$messages = $dbHelper->getMessages();
		foreach( $messages as $message ){
			$this->info( "-" . $message );
		}

		$this->info(".... Terminado ....");

		return;
	}
}
