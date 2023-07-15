<?php

namespace App\Jobs\Empresa;

use App\Empresa;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateTestEmpresa
{
	use Dispatchable;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$nombre = str_random(16);

		$empresa = new Empresa;
		$empresa->empcodi = agregar_ceros(Empresa::max('empcodi'), 3);
		$empresa->EmpNomb = $nombre;
		$empresa->EmpLin1 = random_int(10000000, 25000000);
		$empresa->EmpLin5 = $nombre;
		$empresa->save();
		return $empresa;;
	}
}
