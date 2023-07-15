<?php
namespace App\Models\Venta\Traits;


trait VentaCalculates
{
  
  /**
   * Suma del descuento de todos los items
   */
  public function itemsDiscount()
  {
    $val = 0;

    foreach( $this->items->all() as $item ){
      $val += $item->descuentoBruto();
    }

    return $val;
  }

  public function globalDiscount()
  {
    return $this->VtaDcto - $this->itemsDiscount();
  }

  /**
   * Descuento total de todo el documento
   * 
   * @return float
   */
  public function descuentoBruto()
  {
    return math()->porc( $this->DetDcto , $this->valorBrutoVenta());
  }

  /**
   * Valor de venta de todos los items
   * 
   * @return float
  */  
  public function valorBrutoVenta()
  {
    $val = 0;

    foreach ($this->items->all() as $item) {
      $val += $item->valorBrutoWithDescuento();
    }
    
    return $val;
  }

  /**
   * Sumatoria de isc de los items
   * 
   * @return float
   */
  public function totalISC() : float
  {
    return (float) $this->items->sum('DetISC');
  }

  public function calcularTotales()
  {
    return $this->fillCalculate();

    $items = $this->items;
    // $include_igv = $this->empresa->PrecIIGV;
    $descuentoItems = 0;
    $icbper_total = 0;

    // $importe_gravada_igv = decimal(
    //   $items
    //   ->where( 'DetBase' , self::GRAVADA )    
    //   ->where( 'incluye_igv' , "1" )    
    // ->sum('DetImpo'));

    $cantidad_total = $items->sum('DetCant');  
    $importe_gravada = decimal($items->where('DetBase', self::GRAVADA)->sum('DetImpo'));  
    $importe_inafecta = decimal($items->where( 'DetBase' , self::INAFECTA )->sum('DetImpo'));
    $importe_gratuita = decimal($items->where( 'DetBase' , self::GRATUITA )->sum('DetImpo'));
    $importe_exonerada = decimal($items->where('DetBase', self::EXONERADA)->sum('DetImpo'));
    $isc_total = decimal($items->sum('DetISC'));
    $igv_total = decimal($items->sum('DetIGVV'));
    $icbper_total = decimal($items->sum('icbper_value'));
    $totalISC = $this->totalISC();
    
    $importe_total = $importe_gravada + $importe_inafecta + $importe_exonerada;
    $total = $importe_total + $importe_gratuita;
    
    $itemsWithDiscount = $items->where('DetBase' , '!=', self::GRATUITA )->where('DetDcto' , '>' , '0');
    if( $itemsWithDiscount->count() ){
      foreach( $itemsWithDiscount->all() as $item ){
        $descuentoItems += $item->descuentoBruto();
      }
    }
    
    $igv = 0;
    $base = 0;
    $igv_porcentaje = get_option('Logigv');

    // if ($include_igv) {
    // if( (int) $importe_gravada_igv){
    //     $base = ($importe_gravada_igv / ((float) ("1." . $igv_porcentaje)));
    //     $igv = $importe_gravada_igv - $base;
    // }


    // if ($include_igv) {
    //   if( (int) $importe_gravada){
    //     $base = ($importe_gravada / ((float) ("1." . $igv_porcentaje)));
    //     $igv = $importe_gravada - $base;
    //   }
    // }
    //     
    // else {
    //   $igv = $importe_gravada * ("0." . $igv_porcentaje);
    //   $importe_total += $igv;
    //   $base = $importe_gravada;
    //   $vtaimpo = ($igv + $base) + $importe_exonerada + $importe_gratuita + $importe_inafecta;
    // }

    # Descuento global
    if ( $this->hasDescuento() ) {
      $percDiscountGlobal = $this->descuentoPerc();
      $importe_gravada -= math()->porc( $percDiscountGlobal ,$importe_gravada);
      $importe_inafecta -= math()->porc($percDiscountGlobal , $importe_inafecta);
      $importe_exonerada -= math()->porc($percDiscountGlobal , $importe_exonerada);
      $igv -= math()->porc($percDiscountGlobal  , $igv );
      $vtaimpo = $importe_gravada + $importe_inafecta + $importe_exonerada;
      $importe_total = $importe_total - math()->porc( $percDiscountGlobal  , $importe_total );
      $base -=  math()->porc($percDiscountGlobal, $base);
    }
    
    $this->Vtacant = decimal($cantidad_total);
    $this->VtaIGVV = decimal($igv);
    $this->Vtabase = decimal($base);
    $this->VtaSald = decimal($importe_total);
    $this->VtaImpo = decimal($importe_total);
    $this->VtaTota = $total;    
    $this->VtaInaf = $importe_inafecta;
    $this->VtaExon = $importe_exonerada;
    $this->VtaGrat = $importe_gratuita;    
    $this->icbper = $icbper_total;
    $this->VtaISC = $isc_total;
    // $this->VtaSdCa = $this->Vtacant;
    
    if( $this->hasDetraccion() ){
      $this->VtaDetrTota = decimal(($this->VtaImpo / 100) * $this->VtaDetrPorc);
    }

    if($this->hasPercepcion()){
      $this->VtaPerc = $this->calculatePer();
      $this->VtaImpo += $this->VtaPerc;
      $this->VtaTota += $this->VtaPerc;
    }

  }

  public function fillCalculate()
  {
    $descuento_total = 0;
    $cantidad_total = 0;
    $isc_total = 0;
    $igv_total = 0;
    $icbper_total = 0;
    
    $gravada = 0;
    $exonerada = 0;
    $inafecta = 0;
    $gratuita = 0;
    $base = 0;
    $percepcion = 0;
    # Sumar items            
    foreach( $this->items as $item ){

      $descuento_total += $item->DetDctoV;
      $isc_total    += $item->DetISC;
      $igv_total    += $item->DetIGVP;
      $icbper_total += $item->icbper_value;
      $cantidad_total += $item->DetCant;

      switch ($item->DetBase) {
        case self::GRAVADA:
          $gravada += $item->DetImpo;
          $base += $item->getBase();
          break;
        case self::INAFECTA:
          $inafecta += $item->DetImpo;
          break;
        case self::EXONERADA:
          $exonerada += $item->DetImpo;
          break;
        case self::GRATUITA:
          $gratuita += $item->DetImpo;
          break;
      }
    }

    $importe = $gravada + $exonerada + $inafecta + $icbper_total;

    # Si tiene un cargo global, bien sea un descuento global o percepción
    if ($this->hasGlobalCargo()) {      

      # Descuento
      if( $this->hasDescuento() ){
        $percDiscountGlobal = $this->descuentoPerc();
        $gravada -= math()->porc($percDiscountGlobal, $gravada);
        $inafecta -= math()->porc($percDiscountGlobal, $inafecta);
        $exonerada -= math()->porc($percDiscountGlobal, $exonerada);
        $igv_total -= math()->porc($percDiscountGlobal, $igv_total);
        $importe = $importe - math()->porc($percDiscountGlobal, $importe);
        $base -=  math()->porc($percDiscountGlobal, $base);
      }

      # Percepción
      else {
        $percepcion = ( $importe / 100 ) * $this->VtaPPer;
        $importe += $percepcion;
      }
    }

    # Si tiene anticipo
    if ($this->hasAnticipo()) {
      $importe -= $this->VtaTotalAnticipo;
    }
    
    $total = $importe + $gratuita;
    
    $this->Vtacant = decimal($cantidad_total);
    $this->VtaIGVV = decimal($igv_total);
    $this->Vtabase = decimal($base);
    $this->VtaSald = decimal($importe);
    $this->VtaDcto = decimal($descuento_total);
    $this->VtaImpo = decimal($importe);
    $this->VtaTota = decimal($total);
    $this->VtaInaf = decimal($inafecta);
    $this->VtaExon = decimal($exonerada);
    $this->VtaGrat = decimal($gratuita);
    $this->icbper = decimal($icbper_total);
    $this->VtaISC = decimal($isc_total);
    $this->VtaPerc = decimal($percepcion);
    // $this->VtaSdCa =  $this->Vtacant;
    
    # Calcular detracción
    if ($this->hasDetraccion()) {
      $this->VtaDetrTota = decimal(($importe / 100) * $this->VtaDetrPorc);
    }

    
    $this->save();
  }



  public function calculatePer()
  {
    return ($this->VtaImpo / 100)  * $this->VtaPPer;
  }

  public function importeWithoutPerc()
  {
    return $this->importe - $this->percepcion;
  }

  public function DctoCalc($valorBruto)
  {
    $descuento = $this->VtaDcto;

    return $descuento ? (($valorBruto / 100) * $descuento) : 0;
  }

  public function total_igv()
  {
    $total = $this->items->where('DetBase', self::GRAVADA)->sum('DetImpo');
    return  $this->dec(($total / 100) * 18);
  }

  public function igv_compra()
  {
    $total = $this->items->where('DetBase', self::GRAVADA)->sum('DetImpo');
    return $total - $this->dec($total / 1.18);
  }


  public function totalGravada()
  {
    return $this->items->where('DetBase', self::GRAVADA)->sum('DetImpo');
  }

  public function totalGratuita()
  {
    return $this->items->where('DetBase', self::GRATUITA)->sum('DetImpo');
  }

  public function totalExonerada()
  {
    return $this->items->where('DetBase', self::EXONERADA)->sum('DetImpo');
  }

  public function getTotalInafecta($tipo)
  {
    return $this->items->where('DetBase', self::INAFECTA)->sum('DetImpo');
  }
}