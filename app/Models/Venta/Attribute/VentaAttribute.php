<?php

namespace App\Models\Venta\Attribute;

trait VentaAttribute
{
	public function getVtaFvtaReverseAttribute()
	{
		$date = explode('-' , $this->VtaFvta );
		return str_concat( '/' , $date[2] , $date[1] , $date[0] );
	}

	public function getVtaImpoAttribute($value)
	{
		return fixedValue($value);
	}

  public function getCompleteCorrelativo()
  {
    return sprintf('%s-%s-%s', $this->TidCodi, $this->VtaSeri, $this->VtaNumee);
  }

  public function getImporteAttribute()
  {
    return $this->VtaImpo;
  }

  public function importe()
  {
    return $this->VtaImpo;
  }  

  public function getVtaDctottribute($value)
  {
    return fixedValue($value);
  }

  public function getVtaIGVVttribute($value)
  {
    return fixedValue($value);
  }

  public function getVtabaseAttribute($value)
  {
    return fixedValue($value);
  }

	public function setVtaTcamAttribute($value)
	{
		$this->attributes['VtaTcam'] = decimal($value, 3);
	}

	public function getVtaISCaAttribute($value)
	{
		return $this->dec($value);
	}

	public function getVtaTotaAttribute($value)
	{
		return $this->dec($value);
	}  

	public function getdescuentoAttribute()
	{
		return $this->VtaDcto;
	}


	public function getcorrelativeAttribute()
	{
		return $this->VtaSeri . '-' . $this->VtaNumee;
	}

	public function setVtaNumeAnticipoAttribute($val)
	{
		$this->attributes['VtaNumeAnticipo'] = strtoupper($val);
	}

	public function getFechaAttribute()
	{
		return $this->VtaFvta;
	}

	public function getIdAttribute()
	{
		return $this->VtaOper;
	}

	public function getTipocambioAttribute()
	{
		return $this->VtaTcam;
	}

	public function getVtaSPerAttribute($value)
	{
		return (int) $value;
	}

  public function setCuenCodiAttribute($totales)
  {
    $total_base_isc = $totales->total_base_isc ?? 0;
    $total_base_percepcion = $totales->total_base_percepcion ?? 0;
    
    $data = [
      'total_valor_bruto_venta' => fixedValue($totales->total_valor_bruto_venta),
      'total_valor_venta' => fixedValue($totales->total_valor_venta),
      'total_base_isc' => fixedValue($total_base_isc),
      'total_base_percepcion' => fixedValue($total_base_percepcion),
      'valor_venta_por_item_igv' => fixedValue($totales->valor_venta_por_item_igv),
      'descuento_global' => fixedValue($totales->descuento_global),
      'retencion' => fixedValue($totales->retencion),
      'igv_porc' => $totales->igv_unitario ?? get_option('Logigv'),
      'total_importe' => fixedValue($totales->total_importe),
      'impuestos_totales' => fixedValue($totales->impuestos_totales),
    ];

      if(isset($totales->total_peso)){
			$data['total_peso'] = $totales->total_peso;
		}

		$this->attributes['CuenCodi'] = json_encode($data);
  }

}
