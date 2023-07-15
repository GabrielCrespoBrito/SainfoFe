<?php

namespace App\Console\Commands;

use App\Empresa;
use Illuminate\Console\Command;

class ResetSuscripcionCaracteristicas extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'suscripciones:mes_reinicio';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Ver que suscripciones estan por vencerse o vencidas para enviar informacion al usuario';

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
		# Iterar para ver que suscripciones estan por vencerse
		$empresas = Empresa::all();

		foreach ($empresas as $empresa) {
			# Si estamos en el inicio de un mes            
			if (now()->firstOfMonth()->isSameDay(now())) {
				$empresa->resetSuscripcionCaracteristicas();
			}
		}
	}
}
