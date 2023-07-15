<?php 

namespace App\Models\Traits\InventaryHandler;

use App\Compra;
use App\Cotizacion;
use App\Models\Traits\InventaryHandler\Agregator;
use App\Models\Traits\InventaryHandler\Reducer;
use App\Venta;
use Illuminate\Database\Eloquent\Model;

class InventaryHandler
{
	// Document 
	protected $document;

	// Items from documento
	protected $items;	

	// Item actual
	protected $current_item;		

	// Clases para agregar o reducir de los inventarios
	protected $reducer;
	protected $agregater;

	function __construct( Model $document, $item = null )
	{
		$this->document = $document;		
		$this->reducer = new Reducer($document);
		$this->agregater = new Agregator($document);

		if( $item ){
			$this->setCurrentItem($item);
		}
		else {
			$this->items = $this->document->items;
		}
	}

	public function reduce(){
		$this->reducer->make( $this->getCurrentItem() );
	}

	public function agregate(){
		$this->agregater->make( $this->getCurrentItem() );
	}

	public function setCurrentItem($item){
		$this->current_item = $item;
	}

	public function getCurrentItem(){
		return $this->current_item;
	}

	protected function reduceInventary(){
	}

	protected function agregateInventary(){
	}

	protected function resolverAction()
	{
		if( $this->document instanceof Compra ){
			$this->agregate();
		}
		
		if( $this->document instanceof Venta ){
			$this->reduce();
		}

		if( $this->document instanceof Cotizacion ){
			$this->reduce();
		}

	}

	public function defaultAction(){
		foreach ( $this->items as $item ) {
			$this->setCurrentItem($item);
			$this->resolverAction($item);
		}
	}
}