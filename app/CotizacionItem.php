<?php

namespace App;

use App\Helpers\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;
use App\Models\Venta\Traits\CalculatorItem;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Cotizacion\Relationship\CotizacionItemRelationship;

class CotizacionItem extends Model
{
	use 
	HasCompositePrimaryKey,
	UsesTenantConnection,
	CotizacionItemRelationship;

	protected $table = 'cotizaciones_detalle';
	public $timestamps = false;
	public $incremeting = false;
	public $calculateTotals = null;
	protected $primaryKey = ["DetItem" , "CotNume", "EmpCodi"];
  protected $keyType = "string";  
	public $fillable = [
    'DetItem', 'CotNume', 'DetUnid', 'UniCodi', 'DetCodi', 'DetNomb', 'MarNomb', 'DetCant', 'DetPrec', 'DetImpo', 'DetPeso','EmpCodi','DetEsta','DetDeta',		'DetIGVV','DetCSol','DetCDol',	'DetFact', 'DetDcto','DetBase','DetISC','DetISCP','DetIGVP','DetPercP', 'icbper_unit' , 'icbper_value'
  ];

	public static function findByNume($nume){
		return self::where('CotNume' , $nume)->first();
	}

	public function isSol()
	{
		// return $this->
	}

	public function getCalculos()
	{
		if($this->calculateTotals){
			return $this->calculateTotals;
		}

		$calculator = new CalculatorItem($this);

		$calculator->setValues( 
			$this->DetPrec,
			$this->DetCant,
			$this->incluye_igv,
			$this->DetBase,
			$this->DetDcto,
			(int) $this->icbper_unit,
			$this->DetISCP,
			$this->DetFact,
			1,
			true
		);

		$calculator->calculate();

		return $this->calculateTotals = $calculator->getCalculos();
	} 

	/**
	 * Calcular el precio unitario del item 
	 * 
	 */
	public function precioUnitario()
	{
		return decimal($this->getCalculos()['precio_unitario']);
	}

	public function valorUnitario()
	{
		return decimal($this->getCalculos()['valor_unitario']);
	}

  public function getPrecio($pUnitario = true)
  {
    return $pUnitario ? 
      decimal($this->getCalculos()['precio_unitario']) : 
      decimal($this->getCalculos()['valor_unitario']);
  }


	/**
	 * Si se le aplica igv
	 * 
	 * @return Bool
	 */
	public function hasIGV(): Bool
	{
		return (bool) (int) $this->DetIGVV;
	}
	
	public function cotizacion()
	{
		return $this->belongsTo( Cotizacion::class, 'CotNume' , 'CotNume' );
	}

  public function producto(){

		return $this->belongsTo( Producto::class, 'DetCodi', 'ProCodi' )->withoutGlobalScope('noEliminados');
  }

  public function producto_prop($prop)
  {
	  return $this->producto ? $this->producto[$prop] : "00";
  }


  public static function  guardarFromVenta($items, $id_cotizacion, $totales)
  {
     return self::guardar( $items, $id_cotizacion, true, $totales );
  }


  public function getAbsoluteCantidad()
  {
    return $this->DetCant * $this->DetFact;
  }

  public function eliminateReserved()
  {
    return $this->producto->eliminateReserved($this->getAbsoluteCantidad());
  }


  public function deleteComplete( $deleteReserved = true )
  {
    if($deleteReserved){
      $this->eliminateReserved();
    }
    $this->delete();
  }

  // public static function guardar( $items , $id_cotizacion , $save = true, $totales )
  public static function guardar( $items , $cotizacion , $save = true, $totales )
  {
    // Eliminar los detalles si una cotizacion nueva
		if( $save == false ){
      $cotizacion->deleteItems();
		}

		$cant_items = 1;
    $index = 0;
		foreach( $items as $item )
		{
      $total = $totales[$index];      
      $producto = $total['producto'];
      $unidad = $total['unidad'];
			$detItem = $cant_items < 10 ? ("0".$cant_items) : $cant_items;
			$cant_items++;
			$cotizacion_item = new CotizacionItem();
			$cotizacion_item->DetItem  = (string) $detItem;
			$cotizacion_item->CotNume  = $cotizacion->getId();
			$cotizacion_item->DetUnid  = $unidad->UniAbre;
			$cotizacion_item->UniCodi  = $item['UniCodi'];
			$cotizacion_item->DetCodi  = $item['DetCodi'];
			$cotizacion_item->DetNomb  = $item['DetNomb'];
			$cotizacion_item->MarNomb  = $producto->marcodi;
			$cotizacion_item->DetCant  = $item['DetCant'];
			$cotizacion_item->DetPrec  = $item['DetPrec'];
			$cotizacion_item->DetPeso  = $producto->ProPeso * $item['DetCant'];
			$cotizacion_item->DetEsta  = "01";
			$cotizacion_item->DetDeta  = $item['DetDeta'] ?? '';
			$cotizacion_item->DetIGVV  = $total['igv_unitario']; 
			$cotizacion_item->EmpCodi  = $cotizacion->EmpCodi;
			$cotizacion_item->DetCDol  = $total['costo_dolares'];
      $cotizacion_item->DetCSol  = $total['costo_soles'];
			$cotizacion_item->DetFact  = $total['factor'];
      $cotizacion_item->DetDcto  = $item['DetDcto'];
			$cotizacion_item->DetDctoV  = $total['descuento'];
			$cotizacion_item->DetBase  = $total['base_imponible'];  //  $item['DetBase'];
			$cotizacion_item->DetISCP  = $total['isc_porc']; // $item['DetISC'];
			$cotizacion_item->DetPercP = 0;
			$cotizacion_item->icbper_unit  = $total['bolsa_unit'];
			$cotizacion_item->incluye_igv  = $item['incluye_igv'];
			$cotizacion_item->DetIGVP = $total['igv_total']; //  $igv_total;
			$cotizacion_item->DetDctoV = $total['descuento'];
			$cotizacion_item->DetISC = $total['isc'];
			$cotizacion_item->icbper_value = $total['bolsa'];
			$cotizacion_item->DetImpo = $total['total'];
			$cotizacion_item->save();

      if($cotizacion->isPreventa()){
        $cantidad_real = $item['DetCant'] * $total['factor'];
        $save ? 
        $producto->agregateReserved($cantidad_real) :
        $producto->refresh()->agregateReserved($cantidad_real);

      }
      $index++;
		}
	}


	public function isGravada()
	{
		return strtolower($this->DetBase) == "gravada";
	}

	public function getBase()
	{
		return $this->DetImpo - ($this->DetISC + $this->DetIGVP);
	}

	public function DetDetaFormat()
	{
		return str_replace('+', '<br/>', $this->DetDeta);
	}

}