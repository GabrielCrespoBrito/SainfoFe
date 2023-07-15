<?php

namespace App\Jobs;

use App\ListaPrecio;
use App\Unidad;

class CreateUnidsForLista
{
	public $listaPrecioCopy;
	public $listaCodiCopy;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $listaPrecioCopy, $listaCodiCopy )
    {
      $this->listaPrecioCopy = $listaPrecioCopy;
			$this->listaCodiCopy = $listaCodiCopy;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
			$unidades_chuck = array_chunk(Unidad::where( 'LisCodi', $this->listaCodiCopy )->get()->toArray() , 50) ;
			foreach( $unidades_chuck as $unidades ){
				foreach( $unidades as $unidad ){
				$data = $unidad;
				// $data["Unicodi"] = Unidad::getUniCodi($unidad['Id']);
				unset($data["Unicodi"]);
        $data["LisCodi"] = $this->listaPrecioCopy;
				Unidad::create($data);
				}
			}
    }
}
