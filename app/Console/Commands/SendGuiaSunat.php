<?php

namespace App\Console\Commands;

use App\Sunat;
use App\GuiaSalida;
use Illuminate\Console\Command;

class SendGuiaSunat extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'enviar:guias {mes} {empcodi}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

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
		$mescodi = $this->argument('mes');
		$empcodi = $this->argument('empcodi');

		set_time_limit(6000);

		$guias = GuiaSalida::where('EmpCodi', '=', $empcodi)
			->where('mescodi', '=', $mescodi)
			->where('GuiEFor', '=', "1")
			->where('fe_rpta', '=', "9")
			->get();

		foreach ($guias as $guia) 
		{
			try {
				\DB::beginTransaction();
				$data = Sunat::sendGuia( $guia->GuiOper );
				if ($data['status']) {
					$guia->successEnvio($data);
				} 
			\DB::commit();
			} catch (\Throwable $th) {
				\DB::rollback();
			}
		}

	}
}
