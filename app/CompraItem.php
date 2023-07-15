<?php

namespace App;

use App\Models\Compra\Method\CompraItemMethod;
use App\Unidad;
use App\Models\Traits\InventaryTrait;
use App\Traits\ModelTrait;
use App\Util\ModelUtil\ModelUtil;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class CompraItem extends Model
{
	use 
	ModelUtil,
  CompraItemMethod,
	UsesTenantConnection,
	InventaryTrait;

  protected $table = "compras_detalle";
  protected $primaryKey = "Linea";
  protected $keyType = "string";  
  public $timestamps = false;   
  public $incrementing = false; 
  protected $fillable = [
  	'DetItem',
  	'CpaOper',
  	'UniCodi',
  	'DetUnid',
  	'Detcodi',  
  	'Detnomb',
  	'MarNomb',
  	'DetCant',
  	'DetPrec', 
  	'DetDct1', 
  	'DetDct2', 
  	'DetImpo', 
  	'DetPeso', 
  	'DetEsta', 
  	'DetEsPe', 
  	'DetPerc', 
  	'DetCSol', 
  	'DetCDol', 
  	'DetIgvv', 
  	'DetCGia', 
  	'CccCodi',
  	'Detfact',
  	'DetSdCa'
  ];

  public static function boot() {
    parent::boot();
    static::creating(function (CompraItem $item) {
      $linea = self::max('Linea');
      $item->Linea = $linea ? agregar_ceros( $linea, 8 , 1 ) : "00000001"; 
		});

    static::created(function (CompraItem $item) {
      $item->DetIgvv = $item->DetIgvv ?? get_option('Logigv');;
      $item->DetEsta = "V";
  		$item->DetEsPe = "0";
  		$item->DetPerc = "0";
  		$item->DetCSol = is_null($item->DetCSol) ? "0" : $item->DetCSol;
  		$item->DetCDol = is_null($item->DetCDol) ? "0" : $item->DetCDol;
  		$item->Detfact = is_null($item->Detfact) ? "1" : $item->Detfact;
  		$item->CccCodi = "000";
  		$item->DetSdCa = $item->DetCant;
    	$item->save();
    	$item->calculate();
    }); 

  }

	public function calculate()
	{
  	$descuento = $this->DetDct1 + $this->DetDct2;
  	$importe_total = $this->DetPrec * $this->DetCant;

  	if( $descuento > 0 ){
  		$importe_total = $importe_total - (($importe_total / 100 ) * $descuento);
  	}
  	$this->DetPeso = $this->unidad->UniPeso * $this->DetCant;
  	$this->DetImpo = $importe_total; 
  	$this->save();
	}
	
	public function calculateIGV()
	{
		$igv = $this->DetIgvv;
		return math()->porc($igv , $this->DetImpo);
	}

	public function nextLinea()
	{
  	return self::max('Linea');
  }

	public function producto()
	{
		return $this->belongsTo( Producto::class, 'Detcodi' , 'ProCodi' );
  }

	public function unidad()
	{
  	return $this->belongsTo( Unidad::class , 'UniCodi', 'Unicodi' )->where('empcodi' , $this->compra->EmpCodi);
  }

	public function compra()
	{
  	return $this->belongsTo( Compra::class, 'CpaOper' , 'CpaOper');
	}

}