<?php

namespace App\Models\Venta\Traits;

trait VentaItemCalculates
{

  /**
    * Valor de venta antes de restarle el descuento, sin incluir igv, ni isc
    *
    * @return float
  */
  public function valorBrutoVenta() : float 
  {
    return $this->precioUnitario()  * $this->DetCant;
  }

  /**
    * Valor de venta antes de restarle el descuento, sin incluir igv, ni isc
    *
    * @return float
  */
  public function valorBrutoWithDescuento() : float 
  {
    return $this->valorBrutoVenta() - math()->porc( $this->DetDcto , $this->valorBrutoVenta() );
  }


  public function totalizeFromDatabase()
  {
    $data = $this->lote;
      return [
        'base_imponible' => $this->DetBase,
        'cantidad' => $this->DetCant,
        'precio' => $this->DetPrec,

        'precio_unitario' => $data['precio_unitario'],
        'valor_unitario' => $data['valor_unitario'],

        'valor_noonorosa' => $data['valor_noonorosa'],
        'valor_venta_bruto' => $data['valor_venta_bruto'],
        'descuento' => $this->DetDctoV,

        'valor_venta_por_item' => $data['valor_venta_por_item'],
        'valor_venta_por_item_igv' => $data['valor_venta_por_item_igv'],

        // IGV
        'igv_total' => $this->DetIGVP,
        'igv_unitario' => $this->DetIGVV,

        // ISC
        'isc' => $this->DetISC,
        'isc_porc' => $this->DetISCP,

        // Impuestos totales
        'impuestos_totales' => $data['impuestos_totales'],

        'bolsa' => $this->icbper_value,
        'bolsa_unit' => $this->icbper_unit,
        'total' => $this->DetImpo,
      ];
  }

  /**
   * Calculos para completar la data del xml
   */

  public function calculos()
  {
    return $this->lote ? $this->totalizeFromDatabase() : $this->totalizeCalculate();
  }


  /**
   * Calculos para completar la data del xml
   */

  public function totalizeCalculate()
  {
    $isGravado = $this->isGravada();
    $math = math();
    $igv_bace_uno = $math->factorDivider($this->DetIGVV);

    if ($isGravado) {
      if ($this->incluye_igv) {
        $precio_unitario = $this->DetPrec;
        $valor_unitario =  $this->DetPrec / $igv_bace_uno;
      } else {
        $precio_unitario = $this->DetPrec * $igv_bace_uno;
        $valor_unitario = $this->DetPrec;
      }
    } else {
      $precio_unitario = $this->DetPrec;
      $valor_unitario = $this->DetPrec;
    }


    $valor_noonorosa = $this->isGratuita() ? $this->DetPrec : 0;

    if ($this->DetISCP) {
      $valor_unitario =  $valor_unitario / $math->factorDivider($this->DetISCP);
    }

    $valor_venta_bruto = $valor_unitario * $this->DetCant;


    $valor_venta_por_item =  $valor_venta_bruto - $this->DetDctoV;
    $valor_venta_por_item_igv = $valor_venta_por_item + $this->DetISC;

    $impuestos_totales = $this->DetISC + $this->DetIGVP + $this->icbper_value;

    return $this->data_calculate = [
      'base_imponible' => $this->DetBase,
      'cantidad' => $this->DetCant,
      'precio' => $this->DetPrec,

      'precio_unitario' => $precio_unitario,
      'valor_unitario' => $valor_unitario,

      'valor_noonorosa' => $valor_noonorosa,
      'valor_venta_bruto' => fixedValue($valor_venta_bruto),
      'descuento' => $this->DetDctoV,

      'valor_venta_por_item' => fixedValue($valor_venta_por_item),
      'valor_venta_por_item_igv' => $valor_venta_por_item_igv,

      # IGV
      'igv_total' => $this->DetIGVP,
      'igv_unitario' => $this->DetIGVV,

      # ISC
      'isc' => $this->DetISC,
      'isc_porc' => $this->isc_porc,

      // Impuestos totales
      'impuestos_totales' => $impuestos_totales,

      'bolsa' => $this->icbper_value,
      'bolsa_unit' => $this->icbper_unit,
      'total' => $this->DetImpo,
    ];
  }


  public function makeCalculos( $incluye_igv, $icbper_unit, $precio , $cantidad, $igv_porcentaje, $isc_porcentaje, $dcto_porcentaje )
  {
    $data = $this->getValorOrPrecioUnitario();

    $precio_unitario = $data->precio;
    $valor_unitario = $data->valor;
    $valor_noonorosa = $data->valor_noonorosa;
    $total_valor_venta = $valor_unitario * $cantidad;
    $descuento = $this->hasDescuento() ?  (($total_valor_venta / 100) * $this->DetDcto) : 0;
    $valor_venta_por_item = $total_valor_venta - $descuento;
    $isc = $this->hasISC() ? $valor_venta_por_item * math()->porcFactor($this->DetISCP) : 0;
    $valor_venta_por_item_igv = $valor_venta_por_item + $isc;
    $igv = $this->isGravada() ? ($valor_venta_por_item_igv * math()->porcFactor($this->DetIGVV)) : 0;
    $icbper = $this->hasICBPER() ? $this->icbper_unit * $cantidad : 0;
    $impuestos_totales = $isc + $igv + $icbper;

    return (object) [
      'base_imponible' => $this->DetBase,
      'cantidad' => $cantidad,
      'precio_unitario' => $precio_unitario,
      'valor_unitario' => $valor_unitario,
      'valor_noonorosa' => $valor_noonorosa,
      'total_valor_venta' => $total_valor_venta,    
      'descuento_porc' => $this->DetDcto,
      'descuento' => $descuento,
      'valor_venta_por_item' => $valor_venta_por_item,
      'isc' => $isc,
      'valor_venta_por_item_igv' => $valor_venta_por_item_igv,
      'igv' => $igv,
      'isc_porc' => math()->porcFactor($this->DetISCP),
      'icbper' => $icbper,
      'impuestos_totales' => $impuestos_totales,
    ];
            
  }

  /**
   * Calcular el precio unitario del item 
   * 
   */
  public function precioUnitario()
  {
    $val = $this->DetPrec;
    
    # @TODO
    if ( $this->hasIGV() && $this->incluye_igv ) {
    // Original
      return math()->dividerPorc( $this->DetPrec , $this->DetIGVV );
    }

    return $val;
  }

  /**
   * Valor unitario es el valor que tiene el item sin afectaciòn de IGV
   * 
   * @return int|float
   */

  public function valorUnitario()
  {
    $val = $this->DetPrec;


    if( $this->isGratuita() ){
      return 0;
    }

    if ( ! $this->isGravada()) {
      return $val;
    }
    
    return $this->incluye_igv ? 
      $this->DetPrec :
      math()->multiplicarPorc($this->DetPrec, $this->DetIGVV);
  }


  public function getValorOrPrecioUnitario()
  {
    $val = $this->DetPrec;

    // Si es gratuita
    if ($this->isGratuita()) {
      return (object) [
        'precio' => 0,
        'valor' => 0,
        'valor_noonorosa' => $val,
      ];
    }

    // Si es inafecta o exonerada
    if (!$this->isGravada()) {
      return (object) [
        'precio' => $val,
        'valor' => $val,
        'valor_noonorosa' => 0,
      ];
    }

    // Si es gravada, aplicar IGV
    $precio = $this->incluye_igv ? $this->DetPrec : math()->multiplicarPorc($this->DetPrec, $this->DetIGVV);    
    $valor = $this->incluye_igv ? $this->DetPrec / math()->factorDivider($this->DetIGVV) : $this->DetPrec;

    // Aplicar ISC
    $valor = $this->hasISC() ? $valor / math()->factorDivider($this->DetISCP) : $valor;

    return (object) [
      'precio' => $precio,
      'valor' => $valor,
      'valor_noonorosa' => 0,
    ];
  }


  /**
   * Calcular el valor del isc unitario
   * 
   */
  public function valorISCUnitario()
  {
    return math()->porc( $this->DetISCP , $this->precioUnitario());
  }


  /**
   * Calcular el precio unitario del item aplicandole el isc
   * 
   */
  public function priceAmmount()
  {
    # Si es gratuito, sera su precio

    if( $this->isGratuito())
    {
      return $this->DetPrec;
    } 

    $val = $this->precioUnitario();

    # Si tiene igv, sumarle al precio unitario
    if( $this->hasISC() )
    {
      $val += $this->valorISCUnitario();
    }

    // Sumarle el igv al precio unitario que ya llevemos
    if( $this->hasIGV() )
    {
      $val += math()->porc( $this->DetIGVV , $val );      
    }

    return $val;
  }


  /**
   * Calcular el valor de venta del isc por item
   * 
   */
  public function valorISC()
  {
   return math()->porc( $this->DetISCP , $this->valorBrutoWithDescuento());
  }

  /**
   * Calcular el valor de venta con el isc
   * 
   */
  public function valorVentaItemWithISC()
  {
    return $this->valorBrutoWithDescuento() + $this->valorISC();
  }

  /**
   * Calcular el valor de venta con el isc
   * 
   */
  public function valorVentaItemWithISCDiscountAnticipo()
  {
    return $this->valorVentaItemWithISC() -  $this->VtaTotalAnticipo;
  }

  /**
   * Calcular del igv del item 
   * 
   */
  public function valorVentaItemIGV()
  {
    $val = 0;

    // Si no existe igv no hay nada que calcular
    if( !$this->hasIGV() ){
      return $val;
    }

    // Base para hacer el calculo
    $val = $this->valorVentaRealDato();

    // Sumar isc en caso de tenerlo
    if( $this->hasISC() ){
      $val += $this->calculateRealISC();
    }

    return $val * math()->porcFactor($this->DetIGVV);
  }

  /**
   * Calcular el total de los impuestos aplicados al item igv + isc 
   * 
   * 
   */
  public function valorImpuestosTotales()
  {
    $val = $this->valorVentaItemIGV();

    if( $this->hasISC() ){
      $val += $this->valorISC();
    }

    return $val;
  }


  public function porcentajeIGV()
  {
    return decimal($this->DetIGVV);    
  }

  public function impuestoBrutoItem(){
    return "14.45";
  }


  public function calcularData()
  {
    // $this->DetISC = $this->calculateISC();
    // $this->DetISCDetISC = $this->calculateISC();
    // $this->DetISCDetISC = $this->calculateISC();
    // $this->DetISCDetISC = $this->calculateISC();
    // $this->DetISCDetISC = $this->calculateISC();

    # Cantidad * Precio
    $importe_simple = $this->calculateImporte();
    # Porcentaje de ISC
    $porc_isc = $this->DetISCP;
    # Porcentaje de Descuento
    $porc_dcto = $this->DetDcto;
    # Cantidad de bolsas
    $icbper_unit = config('app.parametros.bolsa');
    # IGV
    $igc_factor = 1.18;
    $igv_porcentaje = 18;


    // $this->DetISC = $iscTotal;
    // $this->DetIGVP = $igvTotal;
    // $this->icbper_value = $icbper_total;    
    $this->DetImpo = ($this->calculateImporte() + $this->DetISC + $this->icbper_value) -  $this->calculateDcto();


    // ORIGINAL
    $this->DetISC = $this->calculateISC();
    $this->DetImpo = ($this->calculateImporte() + $this->DetISC + $this->icbper_value) -  $this->calculateDcto();
    $this->save();
  }

  public function fillCalculate( $save = true, $tipo_igv = null )
  {
    
    # Precio
    $precio = $this->DetPrec;
    # Cantidad
    $cantidad = $this->DetCant;    
    # ISC
    $isc_porcentaje = $this->DetISCP;
    $isc_factor = math()->factorDivider($isc_porcentaje);
    # IGV
    $igv_porcentaje = $this->DetIGVV;
    $igv_factor = math()->factorDivider($igv_porcentaje);
    # Porcentaje de Descuento
    $dcto_porcentaje = $this->DetDcto;
    # Valor de bolsa por unidad
    $icbper_unit = $this->icbper_unit;

    # ---------- Totales ----------
    $isc_total = 0;
    $igv_total = 0;
    $isc_unitario = 0;
    $descuento = 0;


    // Si el item no es exonerado y se ha indicado que su precio tiene igv se hace la operación  de conversión
    $valorUnitario = $precio;
    
    if( $this->isGravada() ){
      $valorUnitario = $this->incluye_igv ? ($precio / $igv_factor)  : $precio;
    }

    # Si tiene isc a el valor unitario habra que aplicarle el isc
    if( $isc_porcentaje ){
      $valorUnitario = $valorUnitario / $isc_factor;
      $isc_unitario = ($valorUnitario/100) * $isc_porcentaje;
      $isc_total =  $isc_unitario * $cantidad;
    }

    // Valor de venta Bruto
    $valorDeVentaBruto = $valorUnitario * $cantidad;

    // Si tiene descuento, calculador y aplicarselo al valor bruto
    if($dcto_porcentaje){
      $descuento = ($valorDeVentaBruto / 100) * $dcto_porcentaje;
      $valorDeVentaBruto -= $descuento;
    }

    // Calcular IGV    
    if( $igv_porcentaje ) {
      $igv_total = (($valorDeVentaBruto + $isc_total) / 100) * $igv_porcentaje;
    }

    $icbper_value = $icbper_unit ? ($icbper_unit) * $cantidad : 0;

    // Calcular total
    $valorDeVentaBruto = $valorDeVentaBruto + $isc_total + $igv_total + $icbper_value;

    $this->DetIGVP = $igv_total;
    $this->DetDctoV = $descuento;
    $this->DetISC = $isc_total;    
    $this->icbper_value = $icbper_value;
    $this->DetImpo = $valorDeVentaBruto;


    $tipo_igv = $tipo_igv ??  10;

    $this->fillTipoIGV(  $tipo_igv );


    if($save){
      $this->save();
    }

    return $this;
  }

  // Check if REALDEAL

  /**
   * Calcular descuento
   * 
   * @return float
   */
  public function calculateDcto() : float
  {
    return (float) ($this->calculateImporte()  / 100 *  $this->DetDcto);
  }


  /**
   * Calcular isc real en base a igvs, etc
   * 
   * @param String 
   * @return float
   */
  public function calculateRealISC(): float
  {
    return $this->valorVentaRealDato() * math()->porcFactor($this->DetISCP);
  }


  /**
   * Calcular simple del isc para el detalle
   * 
   * @return float
   */
  public function calculateISC() : float
  {
    return (float) ($this->calculateImporte()  / 100 * $this->DetISCP);
  }


  /**
   * Calcular descuento
   * 
   * @return float
   */
  public function calculateImporte() : float
  {
    return (float) ($this->DetCant * $this->DetPrec);
  }

  /**
   * Descuento total del item
   * 
   * @return float
   */
  public function descuentoBruto() : float
  {
    return math()->porc( $this->DetDcto, $this->valorBrutoVenta());
  }

  public function valorVentaRealDato()
  {
    return decimal($this->valorBrutoVenta() - $this->descuentoBruto());
  }


  public function valorVentaItemDesc()
  {
    $total = $this->DetImpo;

    if($this->venta->hasDescuento()){
     return $total - ($total / 100 * $this->venta->descuentoPerc());
    }

    return $total; 
  }


  // Valor total de los items despues de restarle el descuento
  public function valorVentaItem($taxSubTotal = false)
  {
    if($this->isGratuito() || ($taxSubTotal && $this->isExonerada() ) ){
      return "0.00";
    }

    return $this->valorVentaRealDato();
  }



  /**
   * El valor unitario es el valor sin afectaciòn del IGV y ISC
   * Si es gratuito se considera 0
   * Si es inafecto o exonerado es el mismo precio suministrado
   * Si tiene ISC se le quita esa afectaciòn
   * 
   * @return float
   */

  public function valorUnitarioPorItem()
  {
    $val = $this->DetPrec;

    if( $this->isGratuita() ){
      return 0;
    }

    if( ! $this->Gravada() ){
      return $val;
    }

    $igv_bace_uno = config('app.parametors.igv_bace_uno');

    // Aplicar  IGV
    $val = $this->incluye_igv ? $val / $igv_bace_uno : $val;

    // Aplicar ISC
    $val = $this->hasIGV ? $val / $this->iscDivider() : $val; 

    return (float) $val;
  }
  
  public function tieneDescuento(){
    return $this->DetDcto > 0;
  }  

  public function getBase()
  {
    return $this->DetImpo - ($this->DetISC + $this->DetIGVP);
  }

}