<?php

namespace App\Console\Commands;

use App\Venta;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TicketVenta extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'util:ticket_venta';

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

	public function handle()
	{
		$boletas = Venta::where('TidCodi','03')->get();

		foreach ($boletas->chunk(200) as $boletas_chuck ) {
			foreach ($boletas_chuck as $boleta ) {
				if( ! is_numeric($boleta->fe_obse) ){
					$resumen_detalle = $boleta->anulacion()->first();
					if( ! is_null($resumen_detalle) ){
						$boleta->update(['fe_obse' => $resumen_detalle->resumen->DocTicket ]);
					}
				}
			}
		}


	}
}
