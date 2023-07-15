<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VentaAmazon extends Model
{
    protected $table = "ventas_amazon";
    public $fillable = ["VtaOper", 'XML' , 'PDF', 'CDR' , 'Estatus'];
    public $timestamps = false;   
    public function venta(){
    	return $this->belongsTo( Venta::class, 'VtaOper' , 'VtaOper' )
    	->where('EmpCodi', $this->EmpCodi );
    }


    public function updatedstatus()
    {
        $intStatus =  (int) ($this->XML && $this->PDF && $this->CDR);
        $this->update(['Estatus' => $intStatus ]);
        return $intStatus;
    }

    public function updateOthers(){
    	// $venta = $this->venta;
    	$venta = Venta::find($this->VtaOper, $this->EmpCodi);
    	if( $venta->isBoleta() ){
    		$resumen_detalle = $venta->anulacion;
    		if( $resumen_detalle ){    			
    			$resumen = $resumen_detalle->resumen;
    			foreach( $resumen->items as $item ){
    				$documento = $item->boleta();
    				if( $venta->VtaOper != $documento->VtaOper ){
    					$venta_amazon = self::where('VtaOper', $documento->VtaOper )->where('EmpCodi', $venta->EmpCodi )->first();
    					$venta_amazon = is_null($venta_amazon) ? new self : $venta_amazon;
    					$venta_amazon->VtaOper = $documento->VtaOper;
    					$venta_amazon->CDR = $this->CDR;
    					$venta_amazon->XML = $this->XML;
    					$venta_amazon->Estatus = 0;
    					$venta_amazon->save();    						
    					$venta_amazon->updatedstatus();
    					FileHelper( $documento->empresa->EmpLin1 )->saveVenta( $documento->VtaOper , false );
    				}
    			}
    		}
    	}    		
    }

}
