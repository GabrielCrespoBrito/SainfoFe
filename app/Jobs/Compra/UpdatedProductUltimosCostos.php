<?php

namespace App\Jobs\Compra;

use App\Compra;
use Illuminate\Support\Collection;

class UpdatedProductUltimosCostos
{

		protected $items_original;
		protected $compra;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $items_original, Compra $compra = null )
    {
			$this->items_original = $items_original;
			$this->compra = $compra;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
			$items = $this->items_original;

			// Si existe la compra, significa que estamos actualizando los items.
			if( $this->compra ){
				$items = $items->merge( $this->compra->items );
			}

			foreach( $items as $item ){
				$item->producto->updateProductUltimoCosto();
			}
    }
}
