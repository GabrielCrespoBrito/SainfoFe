@include('reportes.partials.pdf.kardex_valorizado.table.item.header', compact('code', 'items') )

@php
	// Formato de la información , 3 columnas
	$info_data = [ CANTIDAD => 0, COSTO => 0, COSTO_TOTAL => 0  ];
	// registrar información de las 3 columnas
	$info_total = [ ENTRADA => $info_data , SALIDA => $info_data , SALDO => $info_data ];
@endphp

{{-- Calculo del mes anterior  --}}
@php

	$year_actual = (int) substr($mes,0,4);    
	$mes_actual  = (int) substr($mes,4);
	$new_mescodi = "";

	// *************** \\ 

	if( $mes_actual == 1 ){
		$year_actual = ($year_actual - 1);
		$m = "12";
		$new_mescodi = $year_actual . $m;
	}
	else {
		$m = $mes_actual <= 10 ? ("0" . ($mes_actual-1)) : ($mes_actual-1) ;
		$new_mescodi = $year_actual . $m;
	}

  $last_mouth =
  DB::table('guia_detalle')
  ->join('guias_cab', 'guias_cab.GuiOper', '=', 'guia_detalle.GuiOper')
    ->join('productos' , function($join){
      $join
      ->on('productos.ProCodi', '=', 'guia_detalle.DetCodi' )      
      ->on('productos.empcodi', '=', 'guias_cab.EmpCodi' );
    }) 
    ->join('unidad' , function($join){
      $join
      ->on('unidad.UniCodi', '=', 'guia_detalle.UniCodi' )      
      ->on('unidad.empcodi', '=', 'guias_cab.EmpCodi' );
    })     
  ->where('guia_detalle.DetCodi',  $code  )	    
  ->where('guias_cab.EmpCodi', $empresa['id']  )
  ->where('guias_cab.mescodi', $new_mescodi )
  ->select('guia_detalle.*','guias_cab.*', 'productos.ProNomb', 'productos.ID' , 'productos.tiecodi' , 'productos.unpcodi', 'unidad.*')
  ->get();

	// dump($last_mouth);

  $last_mouth_exists = (boolean) $last_mouth->count();

  if( $last_mouth_exists ){

		$last_mouth_data = $last_info_total = [ 
		ENTRADA =>  $info_data, 
		SALIDA =>  $info_data , 
		SALDO => $info_data 
	];


  	foreach( $last_mouth as $item ){
			
  		$accion = isSalida($item->EntSal) ? SALIDA : ENTRADA;
			$is_salida = isSalida($item->EntSal);

			$quantity = get_real_quantity( $item->UniEnte, $item->UniMedi , $item->Detcant );
			$price = $item->DetPrec;

	  	$last_mouth_data[$accion][CANTIDAD] += $quantity;
			// $last_mouth_data[$accion][COSTO] += $is_salida ? $last_mouth_data[SALDO][COSTO] : $price;
	  	$last_mouth_data[$accion][COSTO] = $is_salida ? $last_info_total[SALDO][COSTO] : $price;
			$last_mouth_data[$accion][COSTO_TOTAL] += $quantity * $last_mouth_data[$accion][COSTO];

			// -----------------------------------------
			// dump($accion, $quantity ) ---------------
			// -----------------------------------------
			############# Calcular Saldo #############

			## new code
			## /new code end



			### Cantidad
			$last_mouth_data[SALDO][CANTIDAD] =  
			($last_mouth_data[ENTRADA][CANTIDAD] - $last_mouth_data[SALIDA][CANTIDAD]);
			###
			
			### Costo total
			$last_mouth_data[SALDO][COSTO_TOTAL]  = 
			$last_mouth_data[ENTRADA][COSTO_TOTAL] - $last_mouth_data[SALIDA][COSTO_TOTAL];
			###

			### Costo
			$last_mouth_data[SALDO][COSTO] = 
			dividir($last_mouth_data[SALDO][COSTO_TOTAL] , $last_mouth_data[SALDO][CANTIDAD]);
			###


  	}
		// UniPUCS
  	$lmd = &$last_mouth_data;

  	// Saldo 
		// $lmd[SALDO][CANTIDAD] = $lmd[ENTRADA][CANTIDAD] - $lmd[SALIDA][CANTIDAD];
		// $lmd[SALDO][COSTO_TOTAL] = $lmd[ENTRADA][COSTO_TOTAL] - $lmd[SALIDA][COSTO_TOTAL];
		// $lmd[SALDO][COSTO] = dividir($lmd[SALDO][COSTO_TOTAL] , $lmd[SALDO][CANTIDAD]);

		// Para saber si va en entrada o en salida		
		if( $lmd[SALDO][COSTO_TOTAL] < 0 ){
			$lmd['tipo'] = SALIDA;
			$lmd[SALDO][CANTIDAD] = ($lmd[SALDO][CANTIDAD]);
			$lmd[SALDO][COSTO_TOTAL] = ($lmd[SALDO][COSTO_TOTAL]);
			$lmd[SALDO][COSTO] = ($lmd[SALDO][COSTO]);
		}
  }
@endphp
{{-- Calculo del mes anterior --}}

{{-- Si existe el mes anterior poner la columna de datos en la tabla --}}
@if( $last_mouth_exists  )

	@php
		$fecha = "{$year_actual}-{$m}-01";				

		$lmdFixed = [ENTRADA => $info_data , SALIDA => $info_data , SALDO => $info_data ];

		// $accion = $lmd['tipo'];

		// $lmdFixed[SALDO][CANTIDAD] = $lmdFixed[$accion][CANTIDAD] = $lmd[SALDO][CANTIDAD];
		// $lmdFixed[SALDO][COSTO] = $lmdFixed[$accion][COSTO] = $lmd[SALDO][COSTO];
		// $lmdFixed[SALDO][COSTO_TOTAL] = $lmdFixed[$accion][COSTO_TOTAL] = $lmd[SALDO][COSTO_TOTAL];
		// -----------------------------

		// $entrada = $lmdFixed[ENTRADA];
		// $salida = $lmdFixed[SALIDA];
		// $saldo = $lmdFixed[SALDO];

		$entrada = $lmd[ENTRADA];
		$salida = $lmd[SALIDA];
		$saldo = $lmd[SALDO];

		// -----------------------------
		// $info_total[SALDO][CANTIDAD] = $info_total[$accion][CANTIDAD] = $lmdFixed[SALDO][CANTIDAD];
		// $info_total[SALDO][COSTO] = $info_total[$accion][COSTO] = $lmdFixed[SALDO][COSTO];
		// $info_total[SALDO][COSTO_TOTAL] = $info_total[$accion][COSTO_TOTAL] = $lmdFixed[SALDO][COSTO_TOTAL];
		
	@endphp
	
	@include('reportes.partials.pdf.kardex_valorizado.table.item.row' , [ 
		'data' => [
			$fecha, '-', 'Stock',	'ini' ,	'16', 
			// $entrada[CANTIDAD] , $entrada[COSTO] , $entrada[COSTO_TOTAL],
			// $salida[CANTIDAD] , $salida[COSTO] , $salida[COSTO_TOTAL],
			"" , "" , "" ,
			"" , "" , "" ,
			$saldo[CANTIDAD] , $saldo[COSTO] , $saldo[COSTO_TOTAL],
			]])
@endif


{{-- Iterando los items --}}

{{-- @dd( "items" , $items ) --}}

@foreach( $items as $item ) 

	@php
		// Información de la columna
		$info_row = [
			ENTRADA => $info_data, 
			SALIDA 	=> $info_data, 
			SALDO   => $info_data 
		];

		// Si ebbs entrada o salida
		$accion = isSalida($item->EntSal) ? SALIDA : ENTRADA;

		$is_salida = isSalida($item->EntSal);

		// Si es entrada = Compra
		if( $item->vtaoper ){
			$venta = App\Venta::find( $item->vtaoper , $item->EmpCodi );
			$tipo_documento = $venta->TidCodi;
			$serie = $venta->VtaSeri;
			$numero = $venta->VtaNume;
			$tipo_operacion = "01";
		}

		// Si es salida = Venta
		else {
			$compra = App\Compra::find( $item->cpaOper );
			if( is_null($compra) ){
				continue;
			}
			
			$tipo_documento = $compra->TidCodi;
			$serie = $compra->CpaSerie;
			$numero = $compra->CpaNumee;
			$tipo_operacion = "02";
		}

		$quantity = get_real_quantity( $item->UniEnte, $item->UniMedi , $item->Detcant );
		// $price = $is_salida ? get_real_price( $item->UniEnte, $item->UniMedi, $item->UniPUCS) * $quantity;
		
		// $quantity = $item->Detcant;
		$price = $item->DetPrec;

		// dump( $quantity , $price);
		
		// Cantidad , costo y costo_total de la fila correspondiente		
		$info_row[$accion][CANTIDAD] = $quantity; 		
	 	$info_row[$accion][COSTO] = $is_salida ? $info_total[SALDO][COSTO] : $price;
	 	$info_row[$accion][COSTO_TOTAL] = $quantity *  $info_row[$accion][COSTO];

	 	// REGISTRAR TOTALES ----------------

	 	// Sumar totales por acción (salida, entrada)
		$info_total[$accion][CANTIDAD]    += $info_row[$accion][CANTIDAD];
		$info_total[$accion][COSTO]       += $info_row[$accion][COSTO];
		$info_total[$accion][COSTO_TOTAL] += $info_row[$accion][COSTO_TOTAL];

		// Calcular totales del saldo

		// cantidad
		$info_total[SALDO][CANTIDAD]  = 
		$info_total[ENTRADA][CANTIDAD] - 	$info_total[SALIDA][CANTIDAD];		

		// costo total
		$info_total[SALDO][COSTO_TOTAL]  = 
		$info_total[ENTRADA][COSTO_TOTAL] - $info_total[SALIDA][COSTO_TOTAL];

		// costo
		$info_total[SALDO][COSTO]  = 
		dividir($info_total[SALDO][COSTO_TOTAL],$info_total[SALDO][CANTIDAD]);


		// Saldo de la fila
		$info_row[SALDO][CANTIDAD] = $info_total[SALDO][CANTIDAD];		
		$info_row[SALDO][COSTO_TOTAL] = $info_total[SALDO][COSTO_TOTAL];
		$info_row[SALDO][COSTO] = dividir($info_total[SALDO][COSTO_TOTAL],$info_total[SALDO][CANTIDAD]) ;

		$entrada = $info_row[ENTRADA];
		$salida  = $info_row[SALIDA];
		$saldo   = $info_row[SALDO];

	@endphp

@include('reportes.partials.pdf.kardex_valorizado.table.item.row' , [
	'data' => [
			$item->GuiFDes, $tipo_documento, $serie, $numero ,	$tipo_operacion, 
			$entrada[CANTIDAD], $entrada[COSTO], $entrada[COSTO_TOTAL],
			$salida[CANTIDAD],  $salida[COSTO],  $salida[COSTO_TOTAL],
			$saldo[CANTIDAD],   $saldo[COSTO],   $saldo[COSTO_TOTAL]
	]])
@endforeach

@include('reportes.partials.pdf.kardex_valorizado.table.item.totales', compact('info_total'))