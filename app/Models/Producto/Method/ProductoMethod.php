<?php 

namespace App\Models\Producto\Method;

use App\Moneda;
use App\Producto;
use App\VentaItem;
use App\CompraItem;
use App\CotizacionItem;
use App\GuiaSalidaItem;
use App\Models\TomaInventario\TomaInventario;
use App\Models\TomaInventario\TomaInventarioDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ProductoMethod
{
	public function isDefaultUnit($unit)
	{
		return substr( $unit , -2 ) === "01";
	}

  /**
   * Si el producto ha sido usado en algun sitio
   */
  public function useInDocument()
  {
    $result = [
      'sitio' => null,
      'codigo_sitio' => null,
      'success' => true,
    ];


    if (VentaItem::where('DetCodi', $this->ProCodi )->count()) {
      $result['success'] = false;
      $result['sitio'] = 'Venta';
      $result['codigo_sitio'] = 'venta';
    }

    elseif (GuiaSalidaItem::where('DetCodi', $this->ProCodi)->count()) {
      $result['success'] = false;
      $result['sitio'] = 'Guia';
      $result['codigo_sitio'] = 'guia';

    }

    elseif (CotizacionItem::where('DetCodi', $this->ProCodi)->count()) {
      $result['success'] = false;
      $result['sitio'] = 'Cotizacion';
      $result['codigo_sitio'] = 'cotizacion';
    }

    elseif ( $detalle =  TomaInventarioDetalle::where('Id' , $this->ID)->first()){

      if($detalle->tomaInventario->isPendiente())
      $result['success'] = false;
      $result['sitio'] = 'Toma de Inventario';
      $result['codigo_sitio'] = 'toma_inventario';
    }
  

    return (object) $result;
  }


	public function getUnidadPrincipal()
	{
		return $this->unidades->first();
	}

	public function updateUnidadPrincipal()
	{
		$unidad =  $this->getUnidadPrincipal();
    
		$unidad->update([
			'UniAbre' => $this->unpcodi,
			'UniPUCD' => $this->ProPUCD,
			'UniPUCS' => $this->ProPUCS,
			'UniMarg' => $this->ProMarg,
			'UNIPUVD' => $this->ProPUVD,
			'UNIPUVS' => $this->ProPUVS,
      'UNIPMVD' => $this->ProPMVD,
      'UNIPMVS' => $this->ProPMVS,      
			'UniPeso' => $this->ProPeso
		]);
	}




	public function getFormatEdicion($precio, $cantidad)
	{
		$unidadPrincipal = $this->getUnidadPrincipal();
		$importe = $precio * $cantidad;
		$igv = $importe * 0.18;
		$precio_venta = $importe - $igv;



    
    
		return [
			"Unidades" =>  $this->unidades,
			"Marca" => $this->marca->MarNomb,
			"MarCodi" => $this->marca->MarCodi,
			"DetCodi" =>  $this->ProCodi,  
			"TieCodi" => $this->tiecodi,
			"DetPeso" => $this->ProPeso,
			"DetBase" => $this->BaseIGV,
			"UniCodi" => $unidadPrincipal->Unicodi,
			"DetUni" =>  $this->ProCodi,
			"DetUniNomb" => $unidadPrincipal->withListaName(),
			"DetNomb" => $this->ProNomb,
			"DetCant" => $cantidad,
			"DetPrec" => $precio,
      "icbper" => $this->icbper,
			"DetDcto" => "0",
			"DetDeta" => null,
      "incluye_igv" => $this->incluye_igv,
			"DetImpo" => $importe,
			"DetPvta" => $precio_venta
		];

	}

  /**
   * Si se le aplica el impuesto a la bolsa
   */
  public function isBolsa()
  {
    return (bool) $this->icbper;
  }

  /**
   * Si tiene afectaciòn de isc
   * @return float
   */
  public function iscPorc()
  {
    return $this->ISC;
  }

	public function hasCodeSunat()
	{
		return (bool) $this->profoto2;
	}

	public function getCodeSunat()
	{
		return $this->profoto2;
	}

	/**
	 * Actualizar el ultimo costo
	 * 
	 */
	public function updateProductUltimoCosto()
	{
		$ultimo_costo = 0;
		$ultima_compra = Producto::getLastCompra($this->ProCodi, null, null , false);

		if( $ultima_compra ){
      $ultimo_costo = $this->isSol() ? ( $ultima_compra->DetCSol / $ultima_compra->DetCant) : ($ultima_compra->DetCDol / $ultima_compra->DetCant);
		}		
		else {
			$ultimo_costo = $this->isSol() ? $this->ProPUCS : $this->ProPUCD;
		}

		$this->update([
      'ProUltC' => $ultimo_costo
		]);

	}

}