<?php

namespace App\Jobs\Producto;

use App\Producto;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateInventarios
{
	use Dispatchable;

	public $local;
	public $fecha;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($local, $fecha = null)
	{
		$this->local = $local;
		$this->fecha = $fecha;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$productos_chuck = Producto::all()->chunk(50);
		foreach( $productos_chuck as $productos ){
			foreach( $productos as $producto ){
        if( $this->local ){
          $producto->updateStock($producto->ProCodi,  $this->local , $this->fecha  );
        }
        else {
          $producto->updateAllStock($producto->ProCodi, $this->fecha );
        }
			}
		}
	}
}
