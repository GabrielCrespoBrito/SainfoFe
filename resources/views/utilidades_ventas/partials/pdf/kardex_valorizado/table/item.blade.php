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
	$mes_actual = (int) substr($mes,4);
	$new_mescodi = "";


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
  ->join('productos', 'guia_detalle.DetCodi', '=', 'productos.ProCodi')
  ->where('guia_detalle.DetCodi',  $code  )	    
  ->where('guias_cab.EmpCodi', $empresa['id']  )
  ->where('guias_cab.mescodi', $new_mescodi )
  ->select('guia_detalle.*','guias_cab.*', 'productos.ProNomb', 'productos.ID' , 'productos.tiecodi' , 'productos.unpcodi')
  ->get();

  $last_mouth_exists =  (boolean) $last_mouth->count();

  if( $last_mouth_exists ){

  	$last_mouth_data = [ "tipo" => ENTRADA , ENTRADA =>  $info_data, SALIDA =>  $info_data , SALDO => $info_data ];


  	foreach( $last_mouth as $item ){
  		$accion = isSalida($item->EntSal) ? SALIDA : ENTRADA;
	  	$last_mouth_data[$accion][CANTIDAD]    = $item->Detcant;
	  	$last_mouth_data[$accion][COSTO]       = $item->DetPrec;
	  	$last_mouth_data[$accion][COSTO_TOTAL] = $item->Detcant * $item->DetPrec;
  	}

  	$lmd = &$last_mouth_data;

  	// Saldo 
		$lmd[SALDO][CANTIDAD]    = $lmd[ENTRADA][CANTIDAD] - $lmd[SALIDA][CANTIDAD];
		$lmd[SALDO][COSTO_TOTAL] = $lmd[ENTRADA][COSTO_TOTAL] - $lmd[SALIDA][COSTO_TOTAL];
		$lmd[SALDO][COSTO] = dividir($lmd[SALDO][COSTO_TOTAL],$lmd[SALDO][CANTIDAD]);

		// Para saber si va en entrada o en salida		
		if( $lmd[SALDO][COSTO_TOTAL] < 0 ){
			$lmd['tipo'] = SALIDA;
			$lmd[SALDO][CANTIDAD] = abs($lmd[SALDO][CANTIDAD]);
			$lmd[SALDO][COSTO_TOTAL] = abs($lmd[SALDO][COSTO_TOTAL]);
			$lmd[SALDO][COSTO] = abs($lmd[SALDO][COSTO]);
		}
  }
@endphp
{{-- Calculo del mes anterior --}}



{{-- Si existe el mes anterior poner la columna de datos en la tabla --}}
@if( $last_mouth_exists  )

	@php
		$fecha = "{$year_actual}-{$m}-01";				

		$lmdFixed = [ENTRADA => $info_data , SALIDA => $info_data , SALDO => $info_data ];

		$accion = $lmd['tipo'];

		$lmdFixed[SALDO][CANTIDAD]    = $lmdFixed[$accion][CANTIDAD]    = $lmd[SALDO][CANTIDAD];
		$lmdFixed[SALDO][COSTO]       = $lmdFixed[$accion][COSTO]       = $lmd[SALDO][COSTO];
		$lmdFixed[SALDO][COSTO_TOTAL] = $lmdFixed[$accion][COSTO_TOTAL] = $lmd[SALDO][COSTO_TOTAL];
		// ----------------------------------

		// dd("lmdFixed", $lmdFixed );

		$entrada = $lmdFixed[ENTRADA];
		$salida = $lmdFixed[SALIDA];
		$saldo = $lmdFixed[SALDO];
		// ----------------------------------
		$info_total[SALDO][CANTIDAD] = $info_total[$accion][CANTIDAD] = $lmdFixed[SALDO][CANTIDAD];
		$info_total[SALDO][COSTO] = $info_total[$accion][COSTO] = $lmdFixed[SALDO][COSTO];
		$info_total[SALDO][COSTO_TOTAL] = $info_total[$accion][COSTO_TOTAL] = $lmdFixed[SALDO][COSTO_TOTAL];
	@endphp
	

	@include('reportes.partials.pdf.kardex_valorizado.table.item.row' , [ 
		'data' => [
			$fecha, '-', 'Stock',	'ini' ,	'16', 
			$entrada[CANTIDAD] , $entrada[COSTO] , $entrada[COSTO_TOTAL],
			$salida[CANTIDAD] , $salida[COSTO] , $salida[COSTO_TOTAL],
			$saldo[CANTIDAD] , $saldo[COSTO] , $saldo[COSTO_TOTAL],
			]])
@endif



{{-- Iterando los items --}}

@foreach( $items as $item ) 

	@php

		// Información de la columna
		$info_row = [
			ENTRADA => $info_data, 
			SALIDA 	=> $info_data, 
			SALDO   => $info_data 
		];

		// Si es entrada o salida
		$accion = isSalida($item->EntSal) ? SALIDA : ENTRADA;
		$is_salida = isSalida($item->EntSal);

		// Si es entrada = Compra
		if( isSalida($item->EntSal) ){
			$venta = App\Venta::find( $item->vtaoper , $item->EmpCodi );
			$tipo_documento = $venta->TidCodi;
			$serie = $venta->VtaSeri;
			$numero = $venta->VtaNume;
			$tipo_operacion = "01";
		}		
		// Si es salida = Venta
		else {
			$compra = App\Compra::find( $item->cpaOper, $item->EmpCodi );
			$tipo_documento = $compra->TidCodi;
			$serie = $compra->CpaSerie;
			$numero = $compra->CpaNumee;
			$tipo_operacion = "02";
		}


		// Cantidad , costo y costo_total de la fila correspondiente		
		$info_row[$accion][CANTIDAD] = $item->Detcant;		
	 	$info_row[$accion][COSTO] = $is_salida ?  $info_total[SALDO][COSTO] : $item->DetPrec;
	 	$info_row[$accion][COSTO_TOTAL] = $info_row[$accion][CANTIDAD] * $info_row[$accion][COSTO];


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

		// dump("saldo costo total sumando", $info_total[SALDO][COSTO_TOTAL] );

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


@include('reportes.partials.pdf.kardex_valorizado.table.item.totales', compact('info_total' ))



