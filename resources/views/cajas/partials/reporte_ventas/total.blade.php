@php    
  $isSubTotal =  $isSubTotal ?? true;
  $grupoColumna =  $isSubTotal ? 'subtotal' : 'global';

  if($isSubTotal){
    $nombreColumna = $columnaNombre;
    $pagadoNombre = "Sub Total Pagado" ;  
    $saldoNombre = "Sub Total Saldo" ;  
    $importeNombre = "Sub Total" ;  
  }  
  else {
    $nombreColumna = $cajaNumero;  
    $pagadoNombre = "Total Pagado" ;  
    $saldoNombre = "Total Saldo" ;  
    $importeNombre = "Total" ;  
  }
@endphp

@include('cajas.partials.reporte_ventas.tr_total', [
  'column1' => $nombreColumna,
  'column2' => $pagadoNombre,
  'column3' => $saldoNombre,
  'column4' => $importeNombre,
  'classColumn' => 'totalNombre',
  'grupoColumna' => $grupoColumna
])

@include('cajas.partials.reporte_ventas.tr_total', [
  'column1' => "US$",
  'column2' => $pagoDolar,
  'column3' => $saldoDolar,
  'column4' => $importeDolar,  
  'classColumn' => 'valores',
  'grupoColumna' => $grupoColumna

])

@include('cajas.partials.reporte_ventas.tr_total', [
  'column1' => "S/.",
  'column2' => $pagoSol,
  'column3' => $saldoSol,
  'column4' => $importeSol,  
  'classColumn' => 'valores ultimo',
  'grupoColumna' => $grupoColumna,
])

