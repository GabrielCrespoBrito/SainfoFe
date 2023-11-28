<?php 

namespace App\Producto;;

use App\M;
use App\Unidad;
use App\GuiaSalida;
use App\GuiaSalidaItem;
use Illuminate\Support\Facades\DB;

trait InventaryTrait 
{
	/**
	 * Agregar o reducir al stock especificado
	 * 
	 * @param float $cant 
	 * @param int $stockNumber
	 * @param bool $agregate
	 * 
	 * @return void
	 */
	public function reduceAgregateToStock( $cant, $stockNumber, $agregate = true)
	{
		$stock = 'prosto' . $stockNumber;
		$value = ($agregate ? $this->{$stock} + $cant : $this->{$stock} - $cant);
		$this->update([ $stock => $value ]);
	}

	// Inventario
	public function reduceInventary( $cant, $stock = 1 )
	{
		$this->reduceAgregateToStock($cant, $stock,false);
	}

	public function agregateInventary( float $cant, $stock = 1 )
	{
		$this->reduceAgregateToStock($cant, $stock, true);
	}

  public function getTotalInventario()
  {
    return 
      $this->prosto1 +
      $this->prosto2 +
      $this->prosto3 +
      $this->prosto4 +
      $this->prosto5 +
      $this->prosto6 +
      $this->prosto7 +
      $this->prosto8 +
      $this->prosto9;
    }

	/**
	 * Inventario de reserva
	 *  
	 * */ 
	public function agregateReserved( float $cant )
	{
		$this->reduceAgregateToStock($cant, "10", true);
	}

	public function reduceReserved( float $cant )
	{
		$this->reduceAgregateToStock($cant, "10", false);
	}

	// Ejecutar reserva
	public function makeReserved( float $cant )
	{
		$this->agregateReserved($cant);
	}

	// Desacer reserva
	public function eliminateReserved( float $cant )
	{
		$this->reduceReserved($cant);		
	}

	// Efectuar reserva
	public function liberateReserved( float $cant )
	{
		$this->reduceReserved($cant);		
	}


	public function cleanInventarios( $loccodi = null, $save = false)
	{
		if( $loccodi ){
			$local_codi = substr( $loccodi, -1 , 1);
			$local_campo = 'prosto' . $local_codi;
			$this->{$local_campo} = "0";
		}
		else {
			$this->prosto1 = "0";
			$this->prosto2 = "0";
			$this->prosto3 = "0";
			$this->prosto4 = "0";
			$this->prosto5 = "0";
			$this->prosto6 = "0";
			$this->prosto7 = "0";
			$this->prosto8 = "0";
			$this->prosto9 = "0";
		}

		if( $save ){
			$this->save();
		}		
	}


	public function setStockInventarios( array $stocks )
	{
		foreach( $stocks as $codi => $stock_cantidad ){
			$local_campo = 'prosto' . $codi;
			$this->{$local_campo} = $stock_cantidad;
		}

		$this->save();
	}

	
	public function reProcessInventario( $local = null, $fecha = null )
	{
		// Limpiar total del stock
		$this->cleanInventarios($local);

		// Calcular stock reales
		$groupDetalles = $this->getMovimientos($local,$fecha);
		$groupDetalles = $groupDetalles
		->select( 'guias_cab.EntSal', 'guia_detalle.UniCodi', 'guias_cab.LocCodi', 'unidad.UniEnte', 'unidad.UniMedi', 'guia_detalle.DetFact',  'guia_detalle.Detcant')
    ->get()
		->groupBy(['LocCodi', 'EntSal' , 'UniCodi']);	

		$cantidades = [];
		foreach( $groupDetalles as $loccodi => $groupDetalleEntradaSalidas ){
			$num_stock = substr( $loccodi, -1 , 1);
			$cantidades[$num_stock] = 0;
			foreach( $groupDetalleEntradaSalidas as $entradaSalida => $groupDetalleUnidades ){
				$isIngreso = $entradaSalida == GuiaSalida::INGRESO;
				foreach( $groupDetalleUnidades as $unicodi => $detalle_unidades ){
					$detalle = $detalle_unidades->first();
					$factor = $detalle->DetFact;
					$cantidad_parcial = $detalle_unidades->sum('Detcant');
					$cantidad_real = $cantidad_parcial * $factor;
					if( $isIngreso ){
						$cantidades[$num_stock] += $cantidad_real;
					}	
					else {
						$cantidades[$num_stock] -= $cantidad_real;
					}
				}
			}
		}

		$this->setStockInventarios($cantidades);
	}

	public function getMovimientos( $loccodi, $fecha )
	{
    $groupDetalles = \DB::connection('tenant')->table('guia_detalle')
    ->join('guias_cab' , 'guias_cab.GuiOper' , '=' , 'guia_detalle.GuiOper' )
    ->join('unidad', function ($join) {
        $join
        ->on('unidad.UniCodi', '=', 'guia_detalle.UniCodi')
        ->on('unidad.empcodi', '=', 'guia_detalle.empcodi');
      })
    ->where('guias_cab.EmpCodi', '=' , $this->empcodi )
    ->where('guia_detalle.DetCodi', '=' , $this->ProCodi );

		if( $loccodi ){
			$groupDetalles->where('guias_cab.Loccodi', '=' , $loccodi );
		}

		if( $fecha ){
			$groupDetalles->where('guias_cab.GuiFemi', '<=' , $fecha );
		}

		return $groupDetalles;
	} 


	public function reProcess()
	{
    GuiaSalidaItem::updateStock2($this->ProCodi);
	}
}